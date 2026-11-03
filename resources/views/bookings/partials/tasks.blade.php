{{-- Multi-day jobs with tasks (client point 88, 3 Nov 2026): the booking's
     task card and the add / edit window. Shown by initBookingTasks()
     (bookings/partials/tasks_script) when the booking or its service uses
     tasks. Spec: APP-SPEC-MULTI-DAY-TASKS.md (admin docs). --}}
<div class="booking-tasks mt-4" id="booking_tasks" style="display:none">
    <div class="card">
        <div class="card-header bg-white d-flex flex-wrap align-items-center">
            <h3 class="mb-0 mr-3">{{ trans('lang.tasks') }}</h3>
            <span class="text-muted" id="tasks_progress"></span>
            <div class="ml-auto d-flex flex-wrap align-items-center non-printable">
                <label class="mb-0 mr-2" for="task_payment">{{ trans('lang.task_payment') }}</label>
                <select class="form-control form-control-sm w-auto" id="task_payment">
                    <option value="end">{{ trans('lang.task_payment_end') }}</option>
                    <option value="per_task">{{ trans('lang.task_payment_per_task') }}</option>
                </select>
                <button type="button" class="btn btn-primary btn-sm ml-2" id="task_add_btn"><i class="fa fa-plus"></i> {{ trans('lang.task_add') }}</button>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-danger" id="tasks_error" style="display:none"></div>
            <div class="text-muted mb-3" id="tasks_total"></div>
            <div id="tasks_list"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="task_modal_title">{{ trans('lang.task_add') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" id="task_form_error" style="display:none"></div>
                <div class="form-group">
                    <label for="task_title">{{ trans('lang.task_title') }}</label>
                    <input type="text" class="form-control" id="task_title" maxlength="200">
                </div>
                <div class="form-group">
                    <label for="task_description">{{ trans('lang.task_description') }}</label>
                    <textarea class="form-control" id="task_description" rows="3"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="task_date">{{ trans('lang.task_date') }}</label>
                        <input type="date" class="form-control" id="task_date">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="task_price">{{ trans('lang.task_price') }}</label>
                        <input type="number" class="form-control" id="task_price" min="0" step="any">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="task_worker">{{ trans('lang.task_worker') }}</label>
                        <select class="form-control" id="task_worker">
                            <option value="">{{ trans('lang.task_no_worker') }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="task_files">{{ trans('lang.task_files') }}</label>
                    <input type="file" class="form-control-file" id="task_files" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt">
                    <div class="mt-2" id="task_existing_files"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="task_save"><i class="fa fa-save"></i> {{ trans('lang.task_save') }}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('lang.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
