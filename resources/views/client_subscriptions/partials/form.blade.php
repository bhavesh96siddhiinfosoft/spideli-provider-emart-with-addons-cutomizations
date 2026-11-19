{{-- #114: shared by create and edit (as in the store panel). The plan belongs to the provider; its region and section
     come from the provider, never typed. --}}
<fieldset>
    <legend>{{ trans('lang.client_subscription_info') }}</legend>

    <div class="form-group row width-50">
        <label class="col-3 control-label">{{ trans('lang.client_plan_title') }}<span class="required-field"></span></label>
        <div class="col-7">
            <input type="text" class="form-control plan_title" maxlength="120">
            <div class="form-text text-muted">{{ trans('lang.client_plan_title_help') }}</div>
        </div>
    </div>

    <div class="form-group row width-50">
        <label class="col-3 control-label">{{ trans('lang.client_plan_price') }}<span class="required-field"></span></label>
        <div class="col-7">
            <input type="number" class="form-control plan_price" min="0" step="any">
            <div class="form-text text-muted">{{ trans('lang.client_plan_price_help') }}</div>
        </div>
    </div>

    <div class="form-group row width-50">
        <label class="col-3 control-label">{{ trans('lang.client_plan_period') }}</label>
        <div class="col-7">
            <select class="form-control plan_period">
                <option value="30">{{ trans('lang.client_plan_period_monthly') }}</option>
                <option value="365">{{ trans('lang.client_plan_period_annual') }}</option>
            </select>
            <div class="form-text text-muted">{{ trans('lang.client_plan_period_help') }}</div>
        </div>
    </div>

    <div class="form-group row width-100">
        <label class="col-3 control-label">{{ trans('lang.client_plan_description') }}</label>
        <div class="col-7">
            <textarea rows="5" class="form-control plan_description"></textarea>
            <div class="form-text text-muted">{{ trans('lang.client_plan_description_help') }}</div>
        </div>
    </div>

    <div class="form-group row width-100">
        <label class="col-3 control-label">{{ trans('lang.client_plan_image') }}</label>
        <div class="col-7">
            <input type="file" accept="image/*" onChange="handlePlanFileSelect(event)">
            <div class="plan_image_preview" style="display:none;">
                <img id="plan_image" src="" width="150px" height="150px">
            </div>
        </div>
    </div>

    <div class="form-group row width-100">
        <div class="form-check">
            <input type="checkbox" class="plan_enabled" id="plan_enabled" checked>
            <label class="col-3 control-label" for="plan_enabled">{{ trans('lang.client_plan_enabled') }}</label>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>{{ trans('lang.client_plan_points') }}</legend>
    <div class="form-group row width-100">
        <div class="col-12">
            <div id="plan_points_container"></div>
            <button type="button" class="btn btn-primary mt-2" onclick="addPlanPoint()">{{ trans('lang.client_plan_add_point') }}</button>
            <div class="form-text text-muted">{{ trans('lang.client_plan_points_help') }}</div>
        </div>
    </div>
</fieldset>
