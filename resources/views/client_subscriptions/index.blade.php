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
                <li class="breadcrumb-item active">{{ trans('lang.client_subscription_plans') }}</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">{{ trans('lang.processing') }}</div>
        <div class="error_top" style="display:none"></div>
        <div class="admin-top-section">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex top-title-section pb-4 justify-content-between">
                        <div class="d-flex top-title-left align-self-center">
                            <span class="icon mr-3"><i class="mdi mdi-wallet-membership mdi-24px"></i></span>
                            <h3 class="mb-0">{{ trans('lang.client_subscription_plans') }}</h3>
                            <span class="counter ml-3 total_count"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('client_subscriptions.partials.tabs', ['active' => 'plans'])

        <div class="table-list">
            <div class="row">
                <div class="col-12">
                    <div class="card border">
                        <div class="card-header d-flex justify-content-between align-items-center border-0">
                            <div class="card-header-title">
                                <h3 class="text-dark-2 mb-2 h4">{{ trans('lang.client_subscription_plans') }}</h3>
                                <p class="mb-0 text-dark-2">{{ trans('lang.client_subscription_plans_text') }}</p>
                            </div>
                            <div class="card-header-right d-flex align-items-center">
                                <div class="card-header-btn mr-3">
                                    <a class="btn-primary btn rounded-full" href="{!! route('client-subscriptions.create') !!}">
                                        <i class="mdi mdi-plus mr-2"></i>{{ trans('lang.client_subscription_create') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive m-t-10">
                                <table id="plans_table" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('lang.client_plan_title') }}</th>
                                            <th>{{ trans('lang.client_plan_price') }}</th>
                                            <th>{{ trans('lang.client_plan_period') }}</th>
                                            <th>{{ trans('lang.client_plan_subscriber_count') }}</th>
                                            <th>{{ trans('lang.client_plan_enabled') }}</th>
                                            <th>{{ trans('lang.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
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
@include('client_subscriptions.partials.common_js')
<script type="text/javascript">
    var editUrlTemplate = "{{ route('client-subscriptions.edit', ':id') }}";

    $(document).ready(function () {
        jQuery("#data-table_processing").show();
        loadPlans().catch(function (e) { showError(String(e)); });
    });

    async function loadPlans() {
        await spdLoadCurrency(await spdLoadProvider());
        var plans = await database.collection('provider_subscription_plans').where('providerId', '==', providerId).get();
        var rows = await Promise.all(plans.docs.map(async function (doc) {
            var plan = doc.data();
            plan.id = plan.id || doc.id;
            // Counted by date, not by the stored status (nothing moves it to expired).
            var subs = await database.collection('provider_subscriptions').where('planId', '==', plan.id).get();
            var live = subs.docs.filter(function (s) { return isLiveSubscription(s.data()); }).length;
            return buildPlanRow(plan, live);
        }));
        $('.total_count').text(rows.length);
        $('#plans_table').DataTable({
            data: rows,
            pageLength: 10,
            responsive: true,
            order: [[0, 'asc']],
            columnDefs: [{orderable: false, targets: [5]}],
            language: {emptyTable: @json(trans('lang.client_plan_none'))}
        });
        jQuery("#data-table_processing").hide();
    }

    function buildPlanRow(plan, live) {
        var actions = '<a class="btn btn-sm btn-outline-primary mr-1" href="' + editUrlTemplate.replace(':id', encodeURIComponent(plan.id)) + '"><i class="mdi mdi-pencil"></i></a>' +
            '<button type="button" class="btn btn-sm btn-outline-danger delete_plan" data-id="' + spdEsc(plan.id) + '"><i class="mdi mdi-delete"></i></button>';
        return [
            spdEsc(plan.title || ''),
            spdEsc(formatPrice(plan.price)),
            spdEsc(spdPeriod(plan.expiryDay)),
            live,
            plan.isEnable === true ? '<span class="badge badge-success">' + spdEsc(@json(trans('lang.client_plan_enabled'))) + '</span>' : '<span class="badge badge-danger">&mdash;</span>',
            actions
        ];
    }

    // Deleting a plan leaves existing subscriptions alone: they hold their own snapshot of the plan.
    $(document).on('click', '.delete_plan', async function () {
        if (!confirm(@json(trans('lang.client_plan_delete_confirm')))) {
            return;
        }
        jQuery("#data-table_processing").show();
        var id = $(this).attr('data-id');
        var doc = await database.collection('provider_subscription_plans').doc(id).get();
        if (!doc.exists || doc.data().providerId !== providerId) {
            showError(@json(trans('lang.client_plan_not_found')));
            return;
        }
        await database.collection('provider_subscription_plans').doc(id).delete();
        window.location.reload();
    });
</script>
@endsection
