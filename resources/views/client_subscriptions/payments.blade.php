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
                <li class="breadcrumb-item active">{{ trans('lang.client_subscription_payments') }}</li>
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
                            <span class="icon mr-3"><i class="mdi mdi-cash-multiple mdi-24px"></i></span>
                            <h3 class="mb-0">{{ trans('lang.client_subscription_payments') }}</h3>
                            <span class="counter ml-3 total_count"></span>
                        </div>
                        <div class="align-self-center">
                            <strong>{{ trans('lang.client_payment_total_earning') }}:</strong> <span class="total_earning"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('client_subscriptions.partials.tabs', ['active' => 'payments'])

        <div class="table-list">
            <div class="row">
                <div class="col-12">
                    <div class="card border">
                        <div class="card-header border-0">
                            <div class="card-header-title">
                                <h3 class="text-dark-2 mb-2 h4">{{ trans('lang.client_subscription_payments') }}</h3>
                                <p class="mb-0 text-dark-2">{{ trans('lang.client_subscription_payments_text') }}</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive m-t-10">
                                <table id="payments_table" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('lang.client_subscriber_name') }}</th>
                                            <th>{{ trans('lang.client_plan_title') }}</th>
                                            <th>{{ trans('lang.client_payment_amount') }}</th>
                                            <th>{{ trans('lang.client_payment_commission') }}</th>
                                            <th>{{ trans('lang.client_payment_earning') }}</th>
                                            <th>{{ trans('lang.client_payment_method') }}</th>
                                            <th>{{ trans('lang.date') }}</th>
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
        loadPayments().catch(function (e) { showError(String(e)); });
    });

    async function loadPayments() {
        await spdLoadCurrency(await spdLoadProvider());
        var snapshots = await database.collection('provider_subscription_payments').where('providerId', '==', providerId).get();
        var payments = snapshots.docs.map(function (d) { return d.data(); });
        payments.sort(function (a, b) { return toTime(b.createdAt) - toTime(a.createdAt); });
        await spdLoadClientNames(payments.map(function (p) { return p.customerId; }));
        var total = 0;
        payments.forEach(function (p) { total += parseFloat(p.providerEarning || 0) || 0; });
        $('.total_earning').text(formatPrice(total));
        $('.total_count').text(payments.length);
        $('#payments_table').DataTable({
            data: payments.map(buildPaymentRow),
            pageLength: 10,
            responsive: true,
            order: [],   // already sorted newest first
            language: {emptyTable: @json(trans('lang.client_payment_none'))}
        });
        jQuery("#data-table_processing").hide();
    }

    // Amount, commission and earning exactly as recorded on the payment, never recalculated.
    function buildPaymentRow(p) {
        var earning = spdEsc(formatPrice(p.providerEarning));
        if (p.walletCredited === true) {
            earning += ' <span class="badge badge-success">' + spdEsc(@json(trans('lang.client_payment_credited'))) + '</span>';
        }
        return [
            spdEsc(clientNames[p.customerId] || ''),
            spdEsc(p.planTitle || ''),
            spdEsc(formatPrice(p.amount)),
            spdEsc(formatPrice(p.adminCommission)),
            earning,
            spdEsc(p.payment_method || ''),
            formatDate(p.createdAt)
        ];
    }
</script>
@endsection
