<script>
    /* ---- Multi-day jobs with tasks (client point 88, 3 Nov 2026) ----------
     *
     * Spec: APP-SPEC-MULTI-DAY-TASKS.md (admin docs). A booking whose service
     * has tasksEnabled is a task job (hasTasks). Its tasks and their comments
     * are sub-collections:
     *   provider_orders/{id}/tasks/{taskId}
     *   provider_orders/{id}/tasks/{taskId}/comments/{commentId}
     * and the booking keeps tasksCount, tasksDone, tasksTotal, tasksPaid in
     * step, in the same transaction as each task write.
     *
     * The provider adds tasks (also while the work goes on), edits or deletes
     * one that is not done and not paid, gives each its own worker, marks it
     * done and comments. The customer comments and pays (website); the admin
     * only views.
     * ------------------------------------------------------------------ */
    var bookingTasks = { order: null, tasks: [], workers: {}, editing: null, keptFiles: [], unsubscribe: null, comments: {} };
    var TASK_OPEN_STATUSES = ['Order Placed', 'Order Accepted', 'Order Assigned', 'Order Ongoing'];
    var taskText = {
        progress: {!! json_encode(trans('lang.task_progress')) !!},
        total: {!! json_encode(trans('lang.task_total')) !!},
        none: {!! json_encode(trans('lang.task_none')) !!},
        pending: {!! json_encode(trans('lang.task_status_pending')) !!},
        inProgress: {!! json_encode(trans('lang.task_status_in_progress')) !!},
        done: {!! json_encode(trans('lang.task_status_done')) !!},
        paid: {!! json_encode(trans('lang.task_paid')) !!},
        unpaid: {!! json_encode(trans('lang.task_unpaid')) !!},
        edit: {!! json_encode(trans('lang.task_edit')) !!},
        add: {!! json_encode(trans('lang.task_add')) !!},
        remove: {!! json_encode(trans('lang.task_delete')) !!},
        removeConfirm: {!! json_encode(trans('lang.task_delete_confirm')) !!},
        markDone: {!! json_encode(trans('lang.task_mark_done')) !!},
        comments: {!! json_encode(trans('lang.task_comments')) !!},
        commentAdd: {!! json_encode(trans('lang.task_comment_add')) !!},
        commentPlaceholder: {!! json_encode(trans('lang.task_comment_placeholder')) !!},
        noWorker: {!! json_encode(trans('lang.task_no_worker')) !!},
        required: {!! json_encode(trans('lang.task_error_required')) !!},
        locked: {!! json_encode(trans('lang.task_locked')) !!},
        paymentLocked: {!! json_encode(trans('lang.task_payment_locked')) !!},
        closed: {!! json_encode(trans('lang.task_booking_closed')) !!},
        removeFile: {!! json_encode(trans('lang.task_remove_file')) !!},
        roles: {
            customer: {!! json_encode(trans('lang.task_role_customer')) !!},
            provider: {!! json_encode(trans('lang.task_role_provider')) !!},
            worker: {!! json_encode(trans('lang.task_role_worker')) !!}
        }
    };

    function taskEscape(text) {
        return String(text == null ? '' : text).replace(/[&<>"']/g, function (ch) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ch];
        });
    }

    function taskMoney(amount) {
        var n = parseFloat(amount) || 0;
        return currencyAtRight ? n.toFixed(decimal_degits) + currentCurrency : currentCurrency + n.toFixed(decimal_degits);
    }

    function taskDateText(value) {
        if (!value) {
            return '';
        }
        var d = typeof value.toDate === 'function' ? value.toDate() : new Date(value);
        return isNaN(d.getTime()) ? '' : d.toLocaleDateString();
    }

    function taskDateInput(value) {
        if (!value) {
            return '';
        }
        var d = typeof value.toDate === 'function' ? value.toDate() : new Date(value);
        if (isNaN(d.getTime())) {
            return '';
        }
        return d.getFullYear() + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + ('0' + d.getDate()).slice(-2);
    }

    function tasksEditable() {
        return bookingTasks.order && TASK_OPEN_STATUSES.indexOf(bookingTasks.order.status) !== -1;
    }

    function taskShowError(message) {
        $('#tasks_error').text(message).toggle(!!message);
    }

    /* Called once the booking and its service are loaded (bookings/edit). */
    function initBookingTasks(order, service) {
        if (!(order.hasTasks === true || (service && service.tasksEnabled === true))) {
            return;
        }
        bookingTasks.order = order;
        $('#booking_tasks').show();
        $('#task_payment').val(order.taskPayment === 'per_task' ? 'per_task' : 'end');
        if (!tasksEditable()) {
            $('#task_add_btn').hide();
            $('#task_payment').prop('disabled', true);
        }
        database.collection('providers_workers').where('providerId', '==', order.provider.author).get().then(function (snap) {
            snap.docs.forEach(function (doc) {
                var w = doc.data();
                var name = ((w.firstName || '') + ' ' + (w.lastName || '')).trim() || doc.id;
                bookingTasks.workers[w.id || doc.id] = name;
                $('#task_worker').append($('<option></option>').attr('value', w.id || doc.id).text(name));
            });
            renderBookingTasks();
        });
        var bookingRef = database.collection('provider_orders').doc(id);
        bookingRef.onSnapshot(function (snap) {
            if (snap.exists) {
                bookingTasks.order = Object.assign({}, bookingTasks.order, snap.data());
                renderBookingTasks();
            }
        });
        bookingTasks.unsubscribe = bookingRef.collection('tasks').orderBy('order').onSnapshot(function (snap) {
            bookingTasks.tasks = snap.docs.map(function (doc) { return Object.assign({ id: doc.id }, doc.data()); });
            renderBookingTasks();
        }, function (err) {
            taskShowError(err.message || String(err));
        });
    }

    function renderBookingTasks() {
        var tasks = bookingTasks.tasks;
        var done = tasks.filter(function (t) { return t.status === 'done'; }).length;
        var total = tasks.reduce(function (s, t) { return s + (parseFloat(t.price) || 0); }, 0);
        $('#tasks_progress').text(tasks.length ? taskText.progress.replace(':done', done).replace(':count', tasks.length) : '');
        $('#tasks_total').text(tasks.length ? taskText.total.replace(':total', taskMoney(total)) : '');
        var anyPaid = tasks.some(function (t) { return t.paid === true; });
        $('#task_payment').prop('disabled', !tasksEditable() || anyPaid).attr('title', anyPaid ? taskText.paymentLocked : '');
        if (!tasks.length) {
            $('#tasks_list').html('<p class="text-muted mb-0">' + taskEscape(taskText.none) + '</p>');
            return;
        }
        var editable = tasksEditable();
        var html = '';
        tasks.forEach(function (t) {
            var locked = t.status === 'done' || t.paid === true;
            var statusText = t.status === 'done' ? taskText.done : (t.status === 'in_progress' ? taskText.inProgress : taskText.pending);
            var statusClass = t.status === 'done' ? 'badge-success' : (t.status === 'in_progress' ? 'badge-info' : 'badge-secondary');
            var files = (Array.isArray(t.files) ? t.files : []).map(function (f) {
                return '<a href="' + taskEscape(f.url) + '" target="_blank" rel="noopener" class="mr-3"><i class="fa ' + (f.type === 'image' ? 'fa-image' : 'fa-file') + '"></i> ' + taskEscape(f.name || 'file') + '</a>';
            }).join('');
            html += '<div class="border rounded p-3 mb-3 booking-task" data-id="' + taskEscape(t.id) + '">'
                + '<div class="d-flex flex-wrap align-items-start">'
                + '<div class="mr-3"><strong>' + taskEscape(t.title) + '</strong>'
                + '<div class="small text-muted">' + taskEscape(taskDateText(t.date)) + ' &middot; ' + taskEscape(t.workerId ? (bookingTasks.workers[t.workerId] || t.workerId) : taskText.noWorker) + '</div></div>'
                + '<div class="ml-auto text-nowrap"><strong>' + taskEscape(taskMoney(t.price)) + '</strong> '
                + '<span class="badge ' + statusClass + '">' + taskEscape(statusText) + '</span> '
                + '<span class="badge ' + (t.paid === true ? 'badge-success' : 'badge-light') + '">' + taskEscape(t.paid === true ? taskText.paid : taskText.unpaid) + '</span></div>'
                + '</div>'
                + (t.description ? '<p class="mt-2 mb-1" style="white-space:pre-line">' + taskEscape(t.description) + '</p>' : '')
                + (files ? '<div class="mt-1">' + files + '</div>' : '')
                + '<div class="mt-2 non-printable">'
                + (editable && !locked ? '<button type="button" class="btn btn-sm btn-outline-primary mr-1 task-edit">' + taskEscape(taskText.edit) + '</button>'
                    + '<button type="button" class="btn btn-sm btn-outline-danger mr-1 task-delete">' + taskEscape(taskText.remove) + '</button>' : '')
                + (editable && t.status !== 'done' ? '<button type="button" class="btn btn-sm btn-outline-success mr-1 task-done">' + taskEscape(taskText.markDone) + '</button>' : '')
                + '<button type="button" class="btn btn-sm btn-link task-comments-toggle">' + taskEscape(taskText.comments) + '</button>'
                + '</div>'
                + '<div class="task-comments mt-2" style="display:none">'
                + '<div class="task-comment-list small"></div>'
                + '<div class="input-group input-group-sm mt-2 non-printable"><input type="text" class="form-control task-comment-text" maxlength="1000" placeholder="' + taskEscape(taskText.commentPlaceholder) + '">'
                + '<div class="input-group-append"><button type="button" class="btn btn-primary task-comment-add">' + taskEscape(taskText.commentAdd) + '</button></div></div>'
                + '</div>'
                + '</div>';
        });
        $('#tasks_list').html(html);
        Object.keys(bookingTasks.comments).forEach(function (taskId) {
            var box = $('.booking-task[data-id="' + taskId + '"]');
            if (box.length) {
                box.find('.task-comments').show();
                renderTaskComments(taskId);
            }
        });
    }

    function taskById(taskId) {
        return bookingTasks.tasks.filter(function (t) { return t.id === taskId; })[0] || null;
    }

    /* ---- the payment choice: only while no task is paid ---- */
    $(document).on('change', '#task_payment', function () {
        var value = $(this).val() === 'per_task' ? 'per_task' : 'end';
        if (bookingTasks.tasks.some(function (t) { return t.paid === true; })) {
            $(this).val(bookingTasks.order.taskPayment || 'end');
            taskShowError(taskText.paymentLocked);
            return;
        }
        database.collection('provider_orders').doc(id).update({ hasTasks: true, taskPayment: value }).then(function () {
            taskShowError('');
        }).catch(function (err) { taskShowError(err.message || String(err)); });
    });

    /* ---- add / edit ---- */
    function openTaskModal(task) {
        bookingTasks.editing = task;
        bookingTasks.keptFiles = task && Array.isArray(task.files) ? task.files.slice() : [];
        $('#task_modal_title').text(task ? taskText.edit : taskText.add);
        $('#task_title').val(task ? task.title : '');
        $('#task_description').val(task ? task.description || '' : '');
        $('#task_date').val(task ? taskDateInput(task.date) : '');
        $('#task_price').val(task ? task.price : '');
        $('#task_worker').val(task && task.workerId ? task.workerId : '');
        $('#task_files').val('');
        $('#task_form_error').hide();
        renderKeptFiles();
        $('#taskModal').modal('show');
    }

    function renderKeptFiles() {
        $('#task_existing_files').html(bookingTasks.keptFiles.map(function (f, i) {
            return '<span class="badge badge-light mr-2 mb-1">' + taskEscape(f.name || 'file')
                + ' <a href="#" class="task-file-remove text-danger" data-index="' + i + '" title="' + taskEscape(taskText.removeFile) + '">&times;</a></span>';
        }).join(''));
    }

    $(document).on('click', '.task-file-remove', function (e) {
        e.preventDefault();
        bookingTasks.keptFiles.splice(parseInt($(this).data('index'), 10), 1);
        renderKeptFiles();
    });

    $(document).on('click', '#task_add_btn', function () {
        taskShowError('');
        openTaskModal(null);
    });

    $(document).on('click', '.task-edit', function () {
        var task = taskById($(this).closest('.booking-task').data('id'));
        if (task) {
            openTaskModal(task);
        }
    });

    /* Uploads the chosen files under the panel's images path. */
    function uploadTaskFiles(taskId) {
        var input = document.getElementById('task_files');
        var files = input && input.files ? Array.prototype.slice.call(input.files) : [];
        return Promise.all(files.map(function (file) {
            var safe = file.name.replace(/[^A-Za-z0-9._-]+/g, '_');
            var ref = firebase.storage().ref('images/tasks/' + id + '/' + taskId + '/' + Date.now() + '_' + safe);
            return ref.put(file).then(function (snap) {
                return snap.ref.getDownloadURL();
            }).then(function (url) {
                return { url: url, name: file.name, type: /^image\//.test(file.type) ? 'image' : 'document' };
            });
        }));
    }

    $(document).on('click', '#task_save', function () {
        var title = $.trim($('#task_title').val());
        var dateValue = $('#task_date').val();
        var priceRaw = $('#task_price').val();
        var price = parseFloat(priceRaw);
        if (!title || !dateValue || priceRaw === '' || !(price >= 0)) {
            $('#task_form_error').text(taskText.required).show();
            return;
        }
        if (!tasksEditable()) {
            $('#task_form_error').text(taskText.closed).show();
            return;
        }
        var editing = bookingTasks.editing;
        var bookingRef = database.collection('provider_orders').doc(id);
        var taskRef = editing ? bookingRef.collection('tasks').doc(editing.id) : bookingRef.collection('tasks').doc();
        var $btn = $(this).prop('disabled', true);
        uploadTaskFiles(taskRef.id).then(function (uploaded) {
            var parts = dateValue.split('-');
            var data = {
                title: title,
                description: $.trim($('#task_description').val()),
                date: firebase.firestore.Timestamp.fromDate(new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10), 12)),
                price: price,
                workerId: $('#task_worker').val() || null,
                files: bookingTasks.keptFiles.concat(uploaded),
                updatedAt: firebase.firestore.FieldValue.serverTimestamp()
            };
            return database.runTransaction(function (tx) {
                return Promise.all([tx.get(bookingRef), tx.get(taskRef)]).then(function (snaps) {
                    var booking = snaps[0].data() || {};
                    var counters = {
                        hasTasks: true,
                        taskPayment: booking.taskPayment === 'per_task' ? 'per_task' : ($('#task_payment').val() === 'per_task' ? 'per_task' : 'end'),
                        tasksCount: parseInt(booking.tasksCount, 10) || 0,
                        tasksTotal: parseFloat(booking.tasksTotal) || 0
                    };
                    if (editing) {
                        if (!snaps[1].exists) {
                            throw new Error(taskText.locked);
                        }
                        var current = snaps[1].data();
                        if (current.status === 'done' || current.paid === true) {
                            throw new Error(taskText.locked);
                        }
                        counters.tasksTotal += price - (parseFloat(current.price) || 0);
                        tx.update(taskRef, data);
                    } else {
                        counters.tasksCount += 1;
                        counters.tasksTotal += price;
                        tx.set(taskRef, Object.assign({
                            id: taskRef.id,
                            order: Date.now(),
                            status: 'pending',
                            doneAt: null,
                            doneBy: null,
                            paid: false,
                            paidAt: null,
                            createdBy: providerAuthor,
                            createdAt: firebase.firestore.FieldValue.serverTimestamp()
                        }, data));
                        if (booking.tasksDone == null) {
                            counters.tasksDone = 0;
                        }
                        if (booking.tasksPaid == null) {
                            counters.tasksPaid = 0;
                        }
                    }
                    counters.tasksTotal = Number(counters.tasksTotal.toFixed(2));
                    tx.update(bookingRef, counters);
                });
            });
        }).then(function () {
            $('#taskModal').modal('hide');
        }).catch(function (err) {
            $('#task_form_error').text(err.message || String(err)).show();
        }).then(function () {
            $btn.prop('disabled', false);
        });
    });

    /* ---- delete: only a task not done and not paid ---- */
    $(document).on('click', '.task-delete', function () {
        var taskId = $(this).closest('.booking-task').data('id');
        if (!confirm(taskText.removeConfirm)) {
            return;
        }
        var bookingRef = database.collection('provider_orders').doc(id);
        var taskRef = bookingRef.collection('tasks').doc(taskId);
        database.runTransaction(function (tx) {
            return Promise.all([tx.get(bookingRef), tx.get(taskRef)]).then(function (snaps) {
                if (!snaps[1].exists) {
                    return null;
                }
                var task = snaps[1].data();
                if (task.status === 'done' || task.paid === true) {
                    throw new Error(taskText.locked);
                }
                var booking = snaps[0].data() || {};
                tx.delete(taskRef);
                tx.update(bookingRef, {
                    tasksCount: Math.max(0, (parseInt(booking.tasksCount, 10) || 0) - 1),
                    tasksTotal: Number(Math.max(0, (parseFloat(booking.tasksTotal) || 0) - (parseFloat(task.price) || 0)).toFixed(2))
                });
                return task;
            });
        }).then(function (task) {
            if (!task) {
                return;
            }
            /* The task's comments and files go with it (best effort). */
            taskRef.collection('comments').get().then(function (snap) {
                snap.docs.forEach(function (doc) { doc.ref.delete(); });
            });
            (task.files || []).forEach(function (f) {
                try { firebase.storage().refFromURL(f.url).delete().catch(function () {}); } catch (e) {}
            });
        }).catch(function (err) { taskShowError(err.message || String(err)); });
    });

    /* ---- mark done (the worker can too, in the app) ---- */
    $(document).on('click', '.task-done', function () {
        var taskId = $(this).closest('.booking-task').data('id');
        var bookingRef = database.collection('provider_orders').doc(id);
        var taskRef = bookingRef.collection('tasks').doc(taskId);
        database.runTransaction(function (tx) {
            return Promise.all([tx.get(bookingRef), tx.get(taskRef)]).then(function (snaps) {
                if (!snaps[1].exists || snaps[1].data().status === 'done') {
                    return;
                }
                var booking = snaps[0].data() || {};
                tx.update(taskRef, {
                    status: 'done',
                    doneAt: firebase.firestore.FieldValue.serverTimestamp(),
                    doneBy: providerAuthor,
                    updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                });
                tx.update(bookingRef, { tasksDone: (parseInt(booking.tasksDone, 10) || 0) + 1 });
            });
        }).catch(function (err) { taskShowError(err.message || String(err)); });
    });

    /* ---- comments ---- */
    $(document).on('click', '.task-comments-toggle', function () {
        var taskId = $(this).closest('.booking-task').data('id');
        var box = $(this).closest('.booking-task').find('.task-comments');
        if (bookingTasks.comments[taskId]) {
            bookingTasks.comments[taskId].unsubscribe();
            delete bookingTasks.comments[taskId];
            box.hide();
            return;
        }
        box.show();
        var entry = { list: [] };
        entry.unsubscribe = database.collection('provider_orders').doc(id).collection('tasks').doc(taskId).collection('comments')
            .orderBy('createdAt').onSnapshot(function (snap) {
                entry.list = snap.docs.map(function (doc) { return doc.data(); });
                renderTaskComments(taskId);
            });
        bookingTasks.comments[taskId] = entry;
    });

    function renderTaskComments(taskId) {
        var entry = bookingTasks.comments[taskId];
        var box = $('.booking-task[data-id="' + taskId + '"] .task-comment-list');
        if (!entry || !box.length) {
            return;
        }
        box.html(entry.list.map(function (c) {
            var when = c.createdAt && typeof c.createdAt.toDate === 'function' ? c.createdAt.toDate().toLocaleString() : '';
            return '<div class="mb-1"><strong>' + taskEscape(taskText.roles[c.role] || c.role || '') + '</strong> '
                + '<span class="text-muted">' + taskEscape(when) + '</span><br>' + taskEscape(c.text) + '</div>';
        }).join(''));
    }

    $(document).on('click', '.task-comment-add', function () {
        var taskBox = $(this).closest('.booking-task');
        var input = taskBox.find('.task-comment-text');
        var text = $.trim(input.val());
        if (!text) {
            return;
        }
        var $btn = $(this).prop('disabled', true);
        database.collection('provider_orders').doc(id).collection('tasks').doc(taskBox.data('id')).collection('comments').add({
            userId: providerAuthor,
            role: 'provider',
            text: text,
            createdAt: firebase.firestore.FieldValue.serverTimestamp()
        }).then(function () {
            input.val('');
        }).catch(function (err) { taskShowError(err.message || String(err)); }).then(function () {
            $btn.prop('disabled', false);
        });
    });
</script>
