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
                <li class="breadcrumb-item active">{{ trans('lang.client_subscription_subscribers') }}</li>
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
                            <span class="icon mr-3"><i class="mdi mdi-account-multiple mdi-24px"></i></span>
                            <h3 class="mb-0">{{ trans('lang.client_subscription_subscribers') }}</h3>
                            <span class="counter ml-3 total_count"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('client_subscriptions.partials.tabs', ['active' => 'subscribers'])

        <div class="table-list">
            <div class="row">
                <div class="col-12">
                    <div class="card border">
                        <div class="card-header border-0">
                            <div class="card-header-title">
                                <h3 class="text-dark-2 mb-2 h4">{{ trans('lang.client_subscription_subscribers') }}</h3>
                                <p class="mb-0 text-dark-2">{{ trans('lang.client_subscription_subscribers_text') }}</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive m-t-10">
                                <table id="subscribers_table" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('lang.client_subscriber_name') }}</th>
                                            <th>{{ trans('lang.client_plan_title') }}</th>
                                            <th>{{ trans('lang.client_subscription_start') }}</th>
                                            <th>{{ trans('lang.client_subscription_expiry') }}</th>
                                            <th>{{ trans('lang.client_subscription_status') }}</th>
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
    $(document).ready(function () {
        jQuery("#data-table_processing").show();
        loadSubscribers().catch(function (e) { showError(String(e)); });
    });

    async function loadSubscribers() {
        var snapshots = await database.collection('provider_subscriptions').where('providerId', '==', providerId).get();
        var subs = snapshots.docs.map(function (d) { return d.data(); });
        subs.sort(function (a, b) { return toTime(b.startDate) - toTime(a.startDate); });
        await spdLoadClientNames(subs.map(function (s) { return s.customerId; }));
        var rows = subs.map(buildSubscriberRow);
        $('.total_count').text(rows.length);
        $('#subscribers_table').DataTable({
            data: rows,
            pageLength: 10,
            responsive: true,
            order: [],   // already sorted newest first
            language: {emptyTable: @json(trans('lang.client_subscriber_none'))}
        });
        jQuery("#data-table_processing").hide();
    }

    // The plan name comes from the snapshot taken when the client paid.
    function buildSubscriberRow(s) {
        return [
            spdEsc(clientNames[s.customerId] || ''),
            spdEsc(s.plan && s.plan.title ? s.plan.title : ''),
            formatDate(s.startDate),
            formatDate(s.expiryDate),
            statusBadge(s)
        ];
    }
</script>
@endsection
