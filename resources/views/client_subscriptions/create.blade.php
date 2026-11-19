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
                <li class="breadcrumb-item active">{{ trans('lang.client_subscription_create') }}</li>
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
    $(document).ready(function () {
        setPlanPoints(['']);   // one empty row to start
    });

    $(".save_plan_btn").click(async function () {
        $(".error_top").hide();
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
            var id = database.collection('tmp').doc().id;
            var photo = await storePlanImage();
            await database.collection('provider_subscription_plans').doc(id).set(Object.assign({
                'id': id,
                'providerId': providerId,
                'photo': photo,
                'createdAt': firebase.firestore.FieldValue.serverTimestamp()
            }, form.fields, place.fields));
            window.location.href = "{{ route('client-subscriptions') }}";
        } catch (e) {
            showError(String(e));
        }
    });
</script>
@endsection
