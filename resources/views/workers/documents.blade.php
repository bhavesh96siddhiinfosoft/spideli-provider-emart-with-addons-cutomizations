@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{ trans('lang.worker_documents') }}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ trans('lang.dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('workers') }}">{{ trans('lang.worker_plural') }}</a></li>
                <li class="breadcrumb-item active">{{ trans('lang.document_verification') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                            <li class="nav-item">
                                <a class="nav-link active worker-doc-heading" href="{{ url()->current() }}">{{ trans('lang.worker_documents') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="error_top" style="display:none"></div>
                        <div class="alert alert-success doc-success" style="display:none"></div>
                        <div id="nothing_uploaded" class="alert alert-info" style="display:none;">
                            {{ trans('lang.document_nothing_uploaded') }}
                        </div>

                        <div class="table-responsive m-t-10 doc-body"></div>

                        <div class="modal fade" id="workerDocModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document" style="max-width: 50%;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <embed id="workerDocImage" src="" frameBorder="0" scrolling="auto" height="100%" width="100%" style="height: 540px;"></embed>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">{{ trans('lang.close') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var workerId = "<?php echo addslashes($id); ?>";
    var currentProviderId = "<?php echo addslashes($providerId); ?>";
    var database = firebase.firestore();
    var requiredDocsRef = database.collection('documents').where('enable', '==', true).where('type', '==', 'worker');
    var verifyRef = database.collection('documents_verify').doc(workerId);
    var workerData = null;

    function escapeHtml(text) {
        return String(text || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function showDocError(msg) {
        $('.doc-success').hide();
        $('.error_top').show().html('<p class="text-danger">' + escapeHtml(msg) + '</p>');
        window.scrollTo(0, 0);
    }

    function showDocSuccess(msg) {
        $('.error_top').hide();
        $('.doc-success').show().text(msg);
        window.scrollTo(0, 0);
    }

    function updateHeading(verified) {
        var fullName = workerData ? ((workerData.firstName || '') + ' ' + (workerData.lastName || '')).trim() : '';
        var base = "{{ trans('lang.worker_documents') }}" + (fullName ? ' - ' + escapeHtml(fullName) : '');
        $('.worker-doc-heading').html(base + spideliVerifiedBadge(verified === true));
    }

    $(document).ready(async function () {
        jQuery('#data-table_processing').show();

        $('#workerDocModal').on('show.bs.modal', function (event) {
            $(this).find('#workerDocImage').attr('src', $(event.relatedTarget).data('image'));
        });

        try {
            var workerSnap = await database.collection('providers_workers').doc(workerId).get();
            if (!workerSnap.exists) {
                showDocError("{{ trans('lang.worker_not_found') }}");
                jQuery('#data-table_processing').hide();
                return;
            }
            workerData = workerSnap.data() || {};
            if (currentProviderId && workerData.providerId && workerData.providerId !== currentProviderId) {
                showDocError("{{ trans('lang.worker_not_found') }}");
                jQuery('#data-table_processing').hide();
                return;
            }
            updateHeading(workerData.isDocumentVerify === true);
            await renderWorkerDocuments();
        } catch (err) {
            console.error('Could not load worker documents:', err);
            showDocError("{{ trans('lang.document_save_failed') }}");
        }

        jQuery('#data-table_processing').hide();
    });

    async function renderWorkerDocuments() {
        var requiredSnap = await requiredDocsRef.get();
        var verifySnap = await verifyRef.get();
        var uploadedList = (verifySnap.exists && Array.isArray(verifySnap.data().documents))
            ? verifySnap.data().documents
            : [];

        if (uploadedList.length === 0) {
            $('#nothing_uploaded').show();
        } else {
            $('#nothing_uploaded').hide();
        }

        var uploadedById = {};
        uploadedList.forEach(function (item) {
            if (item && item.documentId) {
                uploadedById[item.documentId] = item;
            }
        });

        var html = '<table class="table table-bordered table-striped">';
        html += '<thead><tr>';
        html += '<th>{{ trans("lang.document_name") }}</th>';
        html += '<th>{{ trans("lang.front_image") }}</th>';
        html += '<th>{{ trans("lang.back_image") }}</th>';
        html += '<th>{{ trans("lang.status") }}</th>';
        html += '<th>{{ trans("lang.actions") }}</th>';
        html += '</tr></thead><tbody>';

        if (requiredSnap.empty) {
            html += '<tr><td colspan="5" class="text-center text-muted">{{ trans("lang.document_nothing_uploaded") }}</td></tr>';
        } else {
            requiredSnap.docs.forEach(function (docSnap) {
                var reqDoc = docSnap.data() || {};
                var docId = reqDoc.id || docSnap.id;
                var up = uploadedById[docId] || null;
                var status = up && up.status ? up.status : 'pending';

                var badgeClass = 'badge-secondary';
                var statusLabel = "{{ trans('lang.pending') }}";
                if (status === 'approved') {
                    badgeClass = 'badge-success';
                    statusLabel = "{{ trans('lang.approved') }}";
                } else if (status === 'rejected') {
                    badgeClass = 'badge-danger';
                    statusLabel = "{{ trans('lang.rejected') }}";
                } else if (status === 'uploaded') {
                    badgeClass = 'badge-info';
                    statusLabel = "{{ trans('lang.uploaded') }}";
                }

                var frontCell = (up && up.frontImage)
                    ? '<a href="javascript:void(0)" data-toggle="modal" data-target="#workerDocModal" data-bs-toggle="modal" data-bs-target="#workerDocModal" data-image="' + escapeHtml(up.frontImage) + '"><img src="' + escapeHtml(up.frontImage) + '" style="max-width:70px;max-height:50px;object-fit:cover;border-radius:4px;"></a>'
                    : '<span class="text-muted">-</span>';
                var backCell = (up && up.backImage)
                    ? '<a href="javascript:void(0)" data-toggle="modal" data-target="#workerDocModal" data-bs-toggle="modal" data-bs-target="#workerDocModal" data-image="' + escapeHtml(up.backImage) + '"><img src="' + escapeHtml(up.backImage) + '" style="max-width:70px;max-height:50px;object-fit:cover;border-radius:4px;"></a>'
                    : '<span class="text-muted">-</span>';

                var actionCell = '<span class="text-muted">-</span>';
                if (up) {
                    actionCell = '<div class="d-flex align-items-center" style="gap:6px;">'
                        + '<select class="form-control form-control-sm worker-doc-status" data-doc-id="' + escapeHtml(docId) + '" style="max-width:140px;">'
                        + '<option value="approved"' + (status === 'approved' ? ' selected' : '') + '>{{ trans("lang.approved") }}</option>'
                        + '<option value="rejected"' + (status === 'rejected' ? ' selected' : '') + '>{{ trans("lang.rejected") }}</option>'
                        + '</select>'
                        + '<button type="button" class="btn btn-sm btn-primary save-worker-doc" data-doc-id="' + escapeHtml(docId) + '">{{ trans("lang.save") }}</button>'
                        + '</div>';
                }

                html += '<tr>';
                html += '<td>' + escapeHtml(reqDoc.title || docId) + '</td>';
                html += '<td>' + frontCell + '</td>';
                html += '<td>' + backCell + '</td>';
                html += '<td><span class="badge ' + badgeClass + '">' + escapeHtml(statusLabel) + '</span></td>';
                html += '<td>' + actionCell + '</td>';
                html += '</tr>';
            });
        }

        html += '</tbody></table>';
        $('.doc-body').html(html);
    }

    $(document).on('click', '.save-worker-doc', async function () {
        var docId = $(this).data('doc-id');
        var newStatus = $('.worker-doc-status[data-doc-id="' + docId + '"]').val();
        if (newStatus !== 'approved' && newStatus !== 'rejected') {
            return;
        }

        jQuery('#data-table_processing').show();
        try {
            var verifySnap = await verifyRef.get();
            var docs = (verifySnap.exists && Array.isArray(verifySnap.data().documents))
                ? verifySnap.data().documents.slice()
                : [];

            var updated = false;
            for (var i = 0; i < docs.length; i++) {
                if (docs[i] && docs[i].documentId === docId) {
                    docs[i].status = newStatus;
                    updated = true;
                    break;
                }
            }
            if (!updated) {
                jQuery('#data-table_processing').hide();
                return;
            }

            await verifyRef.update({ documents: docs });

            var requiredSnap = await requiredDocsRef.get();
            var requiredIds = requiredSnap.docs.map(function (d) { return (d.data() && d.data().id) || d.id; });
            var approvedIds = docs.filter(function (d) { return d && d.status === 'approved'; }).map(function (d) { return d.documentId; });
            var allApproved = requiredIds.length > 0 && requiredIds.every(function (id) { return approvedIds.indexOf(id) !== -1; });

            await database.collection('providers_workers').doc(workerId).update({ isDocumentVerify: allApproved });
            if (workerData) {
                workerData.isDocumentVerify = allApproved;
            }
            updateHeading(allApproved);
            await renderWorkerDocuments();
            showDocSuccess("{{ trans('lang.document_saved') }}");
        } catch (err) {
            console.error('Could not save worker document status:', err);
            showDocError("{{ trans('lang.document_save_failed') }}");
        }
        jQuery('#data-table_processing').hide();
    });
</script>
@endsection
