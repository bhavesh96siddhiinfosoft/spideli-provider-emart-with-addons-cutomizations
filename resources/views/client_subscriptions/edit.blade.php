@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{ trans('lang.client_subscription_plural') }}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ trans('lang.dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{!! route('client-subscriptions') !!}">{{ trans('lang.client_subscription_plans') }}</a></li>
                <li class="breadcrumb-item active">{{ trans('lang.client_subscription_edit') }}</li>
            </ol>
        </div>
        <div>
            <div class="card-body">
                <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">{{ trans('lang.processing') }}</div>
                <div class="error_top" style="display:none"></div>
                <div class="row vendor_payout_create">
                    <div class="vendor_payout_create-inner">
                        @include('client_subscriptions.partials.form')
                    </div>
                </div>
            </div>
            <div class="form-group col-12 text-center btm-btn">
                <button type="button" class="btn btn-primary save_plan_btn"><i class="fa fa-save"></i> {{ trans('lang.save') }}</button>
                <a href="{!! route('client-subscriptions') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('client_subscriptions.partials.common_js')
@include('client_subscriptions.partials.form_js')
<script type="text/javascript">
    var planId = @json($planId);
    var planLoaded = false;

    $(document).ready(function () {
        jQuery("#data-table_processing").show();
        loadPlan().catch(function (e) { showError(String(e)); });
    });

    async function loadPlan() {
        var doc = await database.collection('provider_subscription_plans').doc(planId).get();
        // A provider edits only their own plans.
        if (!doc.exists || doc.data().providerId !== providerId) {
            showError(@json(trans('lang.client_plan_not_found')));
            $('.save_plan_btn').prop('disabled', true);
            return;
        }
        fillPlanForm(doc.data());
        planLoaded = true;
        jQuery("#data-table_processing").hide();
    }

    function fillPlanForm(plan) {
        $('.plan_title').val(plan.title || '');
        $('.plan_price').val(plan.price || '');
        $('.plan_period').val(String(plan.expiryDay) === '365' ? '365' : '30');
        $('.plan_description').val(plan.description || '');
        $('.plan_enabled').prop('checked', plan.isEnable === true);
        if (plan.photo) {
            existingPhoto = plan.photo;
            $('#plan_image').attr('src', plan.photo);
            $('.plan_image_preview').show();
        }
        setPlanPoints(plan.plan_points);
    }

    // Clients who already subscribed keep the plan as it was when they paid (a snapshot on their subscription).
    $(".save_plan_btn").click(async function () {
        $(".error_top").hide();
        if (!planLoaded) {
            return;
        }
        var form = readPlanForm();
        if (form.error) {
            showError(form.error);
            return;
        }
        jQuery("#data-table_processing").show();
        try {
            var place = providerPlace(await spdLoadProvider());
            if (place.error) {
                showError(place.error);
                return;
            }
            var photo = await storePlanImage();
            await database.collection('provider_subscription_plans').doc(planId).update(Object.assign({'photo': photo}, form.fields, place.fields));
            window.location.href = "{{ route('client-subscriptions') }}";
        } catch (e) {
            showError(String(e));
        }
    });
</script>
@endsection
