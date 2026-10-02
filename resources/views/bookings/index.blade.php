@extends('layouts.app')
@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{ trans('lang.booking_plural') }}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ trans('lang.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('lang.booking_table') }}</li>
                </ol>
            </div>
            <div>
            </div>
        </div>
        <div class="container-fluid">
            <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
                {{ trans('lang.processing') }}
            </div>
            <div class="admin-top-section">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex top-title-section pb-4 justify-content-between">
                            <div class="d-flex top-title-left align-self-center">
                                <span class="icon mr-3"><img src="{{ asset('images/booking.png') }}"></span>
                                <h3 class="mb-0">{{ trans('lang.booking_plural') }}</h3>
                                <span class="counter ml-3 total_count"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-list">
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-pills mb-3 " role="tablist">
                            <li class="nav-item">
                                <a class="nav-link new_booking_list" data-toggle="pill" href="#new_booking_list" role="tab">{{ trans('lang.new_bookings') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link today_booking_list" data-toggle="pill" href="#today_booking_list" role="tab">{{ trans('lang.today') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link upcoming_booking_list" data-toggle="pill" href="#upcoming_booking_list" role="tab">{{ trans('lang.upcoming') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link completed_booking_list" data-toggle="pill" href="#completed_booking_list" role="tab">{{ trans('lang.completed') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link canceled_booking_list" data-toggle="pill" href="#canceled_booking_list" role="tab">{{ trans('lang.canceled') }}</a>
                            </li>
                        </ul>
                        <div class="card border">
                            <div class="card-header d-flex justify-content-between align-items-center border-0">
                                <div class="card-header-title">
                                    <h3 class="text-dark-2 mb-2 h4">{{ trans('lang.booking_table') }}</h3>
                                    <p class="mb-0 text-dark-2">{{ trans('lang.booking_table_text') }}</p>
                                </div>
                                <div class="card-header-right d-flex align-items-center">
                                    <div class="card-header-btn mr-3">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane" id="new_booking_list" role="tabpanel">
                                        <div class="table-responsive m-t-10">
                                            <div class="dropdown text-right">
                                                <button class="btn btn-outline-primary dropdown-toggle custom-export-btn" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-cloud-download"></i> {{ trans('lang.export_as') }}
                                                </button>
                                                <ul class="dropdown-menu " aria-labelledby="exportDropdown">
                                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportBookingData('new_bookings','excel')">{{ trans('lang.export_excel') }}</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportBookingData('new_bookings','pdf')">{{ trans('lang.export_pdf') }}</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportBookingData('new_bookings','csv')">{{ trans('lang.export_csv') }}</a></li>
                                                </ul>
                                            </div>
                                            <table id="newBookingTable" class="display nowrap table table-hover table-striped table-bordered table table-striped" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>{{ trans('lang.booking_id') }}</th>
                                                        <th>{{ trans('lang.order_user_id') }}</th>
                                                        <th>{{ trans('lang.status') }}</th>
                                                        <th>{{ trans('lang.amount') }}</th>
                                                        <th>{{ trans('lang.booking_date') }}</th>
                                                        <th>{{ trans('lang.created_at') }}</th>
                                                        <th>{{ trans('lang.service') }}</th>
                                                        <th>{{ trans('lang.actions') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="new_bookings_row"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="today_booking_list" role="tabpanel">
                                        <div class="table-responsive m-t-10">
                                            <div class="dropdown text-right">
                                                <button class="btn btn-outline-primary dropdown-toggle custom-export-btn" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-cloud-download"></i> {{ trans('lang.export_as') }}
                                                </button>
                                                <ul class="dropdown-menu " aria-labelledby="exportDropdown">
                                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportBookingData('today_bookings','excel')">{{ trans('lang.export_excel') }}</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportBookingData('today_bookings','pdf')">{{ trans('lang.export_pdf') }}</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportBookingData('today_bookings','csv')">{{ trans('lang.export_csv') }}</a></li>
                                                </ul>
                                            </div>
                                            <table id="todayBookingTable" class="display nowrap table table-hover table-striped table-bordered table table-striped" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>{{ trans('lang.booking_id') }}</th>
                                                        <th>{{ trans('lang.order_user_id') }}</th>
                                                        <th>{{ trans('lang.status') }}</th>
                                                        <th>{{ trans('lang.amount') }}</th>
                                                        <th>{{ trans('lang.booking_date') }}</th>
                                                        <th>{{ trans('lang.created_at') }}</th>
                                                        <th>{{ trans('lang.service') }}</th>
                                                        <th>{{ trans('lang.actions') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="today_bookings_row"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="upcoming_booking_list" role="tabpanel">
                                        <div class="table-responsive m-t-10">
                                            <div class="dropdown text-right">
                                                <button class="btn btn-outline-primary dropdown-toggle custom-export-btn" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-cloud-download"></i> {{ trans('lang.export_as') }}
                                                </button>
                                                <ul class="dropdown-menu " aria-labelledby="exportDropdown">
                                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportBookingData('upcoming_bookings','excel')">{{ trans('lang.export_excel') }}</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportBookingData('upcoming_bookings','pdf')">{{ trans('lang.export_pdf') }}</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportBookingData('upcoming_bookings','csv')">{{ trans('lang.export_csv') }}</a></li>
                                                </ul>
                                            </div>
                                            <table id="upcomingBookingTable" class="display nowrap table table-hover table-striped table-bordered table table-striped" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>{{ trans('lang.booking_id') }}</th>
                                                        <th>{{ trans('lang.order_user_id') }}</th>
                                                        <th>{{ trans('lang.status') }}</th>
                                                        <th>{{ trans('lang.amount') }}</th>
                                                        <th>{{ trans('lang.booking_date') }}</th>
                                                        <th>{{ trans('lang.created_at') }}</th>
                                                        <th>{{ trans('lang.service') }}</th>
                                                        <th>{{ trans('lang.actions') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="upcoming_bookings_row"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="completed_booking_list" role="tabpanel">
                                        <div class="table-responsive m-t-10">
                                            <div class="dropdown text-right">
                                                <button class="btn btn-outline-primary dropdown-toggle custom-export-btn" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-cloud-download"></i> {{ trans('lang.export_as') }}
                                                </button>
                                                <ul class="dropdown-menu " aria-labelledby="exportDropdown">
                                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportBookingData('completed_bookings','excel')">{{ trans('lang.export_excel') }}</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportBookingData('completed_bookings','pdf')">{{ trans('lang.export_pdf') }}</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportBookingData('completed_bookings','csv')">{{ trans('lang.export_csv') }}</a></li>
                                                </ul>
                                            </div>
                                            <table id="completedBookingTable" class="display nowrap table table-hover table-striped table-bordered table table-striped" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>{{ trans('lang.booking_id') }}</th>
                                                        <th>{{ trans('lang.order_user_id') }}</th>
                                                        <th>{{ trans('lang.status') }}</th>
                                                        <th>{{ trans('lang.amount') }}</th>
                                                        <th>{{ trans('lang.booking_date') }}</th>
                                                        <th>{{ trans('lang.created_at') }}</th>
                                                        <th>{{ trans('lang.service') }}</th>
                                                        <th>{{ trans('lang.actions') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="completed_bookings_row"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="canceled_booking_list" role="tabpanel">
                                        <div class="table-responsive m-t-10">
                                            <div class="dropdown text-right">
                                                <button class="btn btn-outline-primary dropdown-toggle custom-export-btn" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-cloud-download"></i> {{ trans('lang.export_as') }}
                                                </button>
                                                <ul class="dropdown-menu " aria-labelledby="exportDropdown">
                                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportBookingData('cancel_bookings','excel')">{{ trans('lang.export_excel') }}</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportBookingData('cancel_bookings','pdf')">{{ trans('lang.export_pdf') }}</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportBookingData('cancel_bookings','csv')">{{ trans('lang.export_csv') }}</a></li>
                                                </ul>
                                            </div>
                                            <table id="cancelBookingTable" class="display nowrap table table-hover table-striped table-bordered table table-striped" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>{{ trans('lang.booking_id') }}</th>
                                                        <th>{{ trans('lang.order_user_id') }}</th>
                                                        <th>{{ trans('lang.status') }}</th>
                                                        <th>{{ trans('lang.amount') }}</th>
                                                        <th>{{ trans('lang.booking_date') }}</th>
                                                        <th>{{ trans('lang.created_at') }}</th>
                                                        <th>{{ trans('lang.service') }}</th>
                                                        <th>{{ trans('lang.actions') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="cancel_bookings_row"></tbody>
                                            </table>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
    <script type="text/javascript">
        var database = firebase.firestore();
        var offest = 1;
        var pagesize = 10;
        var end = null;
        var endarray = [];
        var start = null;
        var provider_id = "<?php echo $id; ?>";
        var append_list = '';
        var user_number = [];
        let filteredRecords=[];
        var currentDateTime = new Date();
        var startOfToday = new Date(currentDateTime);
        startOfToday.setHours(0, 0, 0, 0);
        var endOfToday = new Date(currentDateTime);
        endOfToday.setHours(23, 59, 59, 999);
        var startTimestamp = firebase.firestore.Timestamp.fromDate(startOfToday);
        var endTimestamp = firebase.firestore.Timestamp.fromDate(endOfToday);
        var newBookingRef = database.collection('provider_orders').where('provider.author', "==", provider_id).where('status', '==', 'Order Placed').orderBy('createdAt', 'desc');
        var todayBookingRef = database.collection('provider_orders').where('newScheduleDateTime', '>=', startTimestamp).where('newScheduleDateTime', '<=', endTimestamp).where('provider.author', "==", provider_id).where('status', 'in', ['Order Accepted', 'Order Assigned', 'Order Ongoing']);
        var upcomingBookingRef = database.collection('provider_orders').where('provider.author', "==", provider_id).where('status', 'in', ['Order Accepted', 'Order Assigned']).where('newScheduleDateTime', '>=', endTimestamp);
        var completedBookingRef = database.collection('provider_orders').where('provider.author', "==", provider_id).where('status', '==', 'Order Completed').orderBy('createdAt', 'desc');
        var cancelBookingRef = database.collection('provider_orders').where('provider.author', "==", provider_id).where('status', 'in', ['Order Cancelled', 'Order Rejected']).orderBy('createdAt', 'desc');
        var section_id = '<?php if (@$_COOKIE['ondemand_section_id']) {
            echo @$_COOKIE['ondemand_section_id'];
        } else {
            echo '';
        } ?>';
        if (section_id != '') {
            newBookingRef = newBookingRef.where('sectionId', '==', section_id);
            todayBookingRef = todayBookingRef.where('sectionId', '==', section_id);
            upcomingBookingRef = upcomingBookingRef.where('sectionId', '==', section_id);
            completedBookingRef = completedBookingRef.where('sectionId', '==', section_id);
            cancelBookingRef = cancelBookingRef.where('sectionId', '==', section_id);
        }
        var orderStatus = '<?php if (isset($_GET['status'])) {
            echo $_GET['status'];
        } else {
            echo '';
        } ?>';
        var currentCurrency = '';
        var currencyAtRight = false;
        var decimal_degits = 0;
        var refCurrency = database.collection('currencies').where('isActive', '==', true);
        refCurrency.get().then(async function(snapshots) {
            var currencyData = snapshots.docs[0].data();
            currentCurrency = currencyData.symbol;
            currencyAtRight = currencyData.symbolAtRight;
            if (currencyData.decimal_degits) {
                decimal_degits = currencyData.decimal_degits;
            }
        });
        $(document).on('click', '.new_booking_list', function() {
            getNewBookings();
        });
        $(document).on('click', '.today_booking_list', function() {
            getTodayBookings();
        });
        $(document).on('click', '.upcoming_booking_list', function() {
            getUpcomingBookings();
        });
        $(document).on('click', '.completed_booking_list', function() {
            getCompletedBookings();
        });
        $(document).on('click', '.canceled_booking_list', function() {
            getCancelBookings();
        });
        $(document).ready(function() {
            $(document.body).on('click', '.redirecttopage', function() {
                var url = $(this).attr('data-url');
                window.location.href = url;
            });
            if (orderStatus == "order-placed") {
                $('.new_booking_list').addClass('active');
                $('#new_booking_list').addClass('active');
                getNewBookings();
            } else if (orderStatus == "order-today" || orderStatus == "order-ongoing") {
                $('.today_booking_list').addClass('active');
                $('#today_booking_list').addClass('active');
                getTodayBookings();
            } else if (orderStatus == "order-upcoming") {
                $('.upcoming_booking_list').addClass('active');
                $('#upcoming_booking_list').addClass('active');
                getUpcomingBookings();
            } else if (orderStatus == "order-completed") {
                $('.completed_booking_list').addClass('active');
                $('#completed_booking_list').addClass('active');
                getCompletedBookings();
            } else if (orderStatus == "order-canceled") {
                $('.canceled_booking_list').addClass('active');
                $('#canceled_booking_list').addClass('active');
                getCancelBookings();
            } else {
                $('.new_booking_list').addClass('active');
                $('#new_booking_list').addClass('active');
                getNewBookings();
            }
            database.collection('sections').where('serviceTypeFlag', '==', 'ondemand-service').get().then(async function(snapshots) {
                snapshots.docs.forEach((listval) => {
                    var data = listval.data();
                    $('#section_id').append($("<option></option>")
                        .attr("value", data.id)
                        .text(data.name));
                })
                checkSection = "{{ @$_COOKIE['ondemand_section_id'] }}";
                (checkSection != '') ? $('#section_id').val(checkSection): $('#section_id').val('');
            })
        });
        function resetDataTable(tableId, refVar) {
            if ($.fn.DataTable.isDataTable(tableId)) {
                let table = $(tableId).DataTable();
                table.destroy();
            }
            mainDataTable(tableId, refVar);
        }
        function getNewBookings() {
            resetDataTable('#newBookingTable', newBookingRef);
        }
        function getTodayBookings() {
            resetDataTable('#todayBookingTable', todayBookingRef);
        }
        function getUpcomingBookings() {
            resetDataTable('#upcomingBookingTable', upcomingBookingRef);
        }
        function getCompletedBookings() {
            resetDataTable('#completedBookingTable', completedBookingRef);
        }
        function getCancelBookings() {
            resetDataTable('#cancelBookingTable', cancelBookingRef);
        }
        function mainDataTable(tableName, refVar) {
            jQuery("#data-table_processing").show();
            const table = $(tableName).DataTable({
                pageLength: 10,
                processing: false,
                serverSide: true,
                responsive: true,
                ajax: async function(data, callback, settings) {
                    const start = data.start;
                    const length = data.length;
                    const searchValue = data.search.value.toLowerCase();
                    const orderColumnIndex = data.order[0].column;
                    const orderDirection = data.order[0].dir;
                    const orderableColumns = ['id', 'authorName', 'status', 'price', 'bookingDateTime', 'createdAt', 'serviceName', ''];
                    const orderByField = orderableColumns[orderColumnIndex];
                    if (searchValue.length >= 3 || searchValue.length === 0) {
                        $('#data-table_processing').show();
                    }
                    try {
                        const querySnapshot = await refVar.get();
                        if (querySnapshot.empty) {
                            $('.total_count').text(0);
                            $('#data-table_processing').hide();
                            callback({
                                draw: data.draw,
                                recordsTotal: 0,
                                recordsFiltered: 0,
                                data: []
                            });
                            return;
                        }
                        let records = [];
                        filteredRecords = [];
                        await Promise.all(querySnapshot.docs.map(async (doc) => {
                            let childData = doc.data();
                            childData.id = doc.id;
                            var authorName = (childData.author != undefined) ? (childData.author.firstName + ' ' + childData.author.lastName) : '';
                            childData.authorName = authorName ? authorName : '';
                            var price = buildHTMLProductstotal(childData);
                            if (childData.status != 'Order Completed' && childData.provider.priceUnit == 'Hourly') {
                                var perHourPrice = parseFloat(childData.provider.price);
                                if (childData.provider.disPrice != null && childData.provider.disPrice != undefined && childData.provider.disPrice != '' && childData.provider.disPrice != '0') {
                                    perHourPrice = parseFloat(childData.provider.disPrice)
                                }
                                if (currencyAtRight) {
                                    perHourPrice = perHourPrice.toFixed(decimal_degits) + "" + currentCurrency;
                                } else {
                                    perHourPrice = currentCurrency + "" + perHourPrice.toFixed(decimal_degits);
                                }
                                price = perHourPrice + '/hr';
                            }
                            if (childData.hasOwnProperty("scheduleDateTime")) {
                                childData.bookingDateTime = childData.scheduleDateTime;
                            }
                            if (childData.hasOwnProperty("newScheduleDateTime") && childData.newScheduleDateTime != null && childData.newScheduleDateTime != '') {
                                childData.bookingDateTime = childData.newScheduleDateTime;
                            }
                            childData.price = price ? price : 0.00;
                            childData.serviceName = childData.provider.title;
                            if (searchValue) {
                                var bookingDate = '';
                                var bookingTime = '';
                                if (childData.hasOwnProperty("scheduleDateTime")) {
                                    bookingDate = childData.scheduleDateTime.toDate().toDateString();
                                    bookingTime = childData.scheduleDateTime.toDate().toLocaleTimeString('en-US');
                                }
                                if (childData.hasOwnProperty("newScheduleDateTime") && childData.newScheduleDateTime != null && childData.newScheduleDateTime != '') {
                                    bookingDate = childData.newScheduleDateTime.toDate().toDateString();
                                    bookingTime = childData.newScheduleDateTime.toDate().toLocaleTimeString('en-US');
                                }
                                var bookingDateTime = bookingDate + ' ' + bookingTime;
                                var date = '';
                                var time = '';
                                if (childData.hasOwnProperty("createdAt") && childData.createdAt != '') {
                                    try {
                                        date = childData.createdAt.toDate().toDateString();
                                        time = childData.createdAt.toDate().toLocaleTimeString('en-US');
                                    } catch (err) {
                                    }
                                }
                                var createdAt = date + ' ' + time;
                                if (
                                    (childData.id && childData.id.toLowerCase().includes(searchValue)) ||
                                    (authorName && authorName.toLowerCase().includes(searchValue)) ||
                                    (childData.status && childData.status.toLowerCase().includes(searchValue)) ||
                                    (childData.price && childData.price.toLowerCase().includes(searchValue)) ||
                                    (serviceName && serviceName.toLowerCase().includes(searchValue)) ||
                                    (createdAt && createdAt.toString().toLowerCase().indexOf(searchValue) > -1) ||
                                    (bookingDateTime && bookingDateTime.toString().toLowerCase().indexOf(searchValue) > -1)
                                ) {
                                    filteredRecords.push(childData);
                                }
                            } else {
                                filteredRecords.push(childData);
                            }
                        }));
                        filteredRecords.sort((a, b) => {
                            let aValue = a[orderByField] ? a[orderByField].toString().toLowerCase().trim() : '';
                            let bValue = b[orderByField] ? b[orderByField].toString().toLowerCase().trim() : '';
                            if (orderByField === 'createdAt' && a[orderByField] != '' && b[orderByField] != '') {
                                try {
                                    aValue = a[orderByField] ? new Date(a[orderByField].toDate()).getTime() : 0;
                                    bValue = b[orderByField] ? new Date(b[orderByField].toDate()).getTime() : 0;
                                } catch (err) {}
                            }
                            if (orderByField === 'bookingDateTime' && a[orderByField] != '' && b[orderByField] != '') {
                                try {
                                    aValue = a[orderByField] ? new Date(a[orderByField].toDate()).getTime() : 0;
                                    bValue = b[orderByField] ? new Date(b[orderByField].toDate()).getTime() : 0;
                                } catch (err) {}
                            }
                            if (orderByField === 'price') {
                                const parseAmount = (amountString) => {
                                    return parseFloat(amountString.replace(/[$,]/g, ''));
                                };
                                aValue = a[orderByField] ? parseAmount(a[orderByField]) : 0;
                                bValue = b[orderByField] ? parseAmount(b[orderByField]) : 0;
                            }
                            if (orderDirection === 'asc') {
                                return (aValue > bValue) ? 1 : -1;
                            } else {
                                return (aValue < bValue) ? 1 : -1;
                            }
                        });
                        const totalRecords = filteredRecords.length;
                        $('.total_count').text(totalRecords);
                        const paginatedRecords = filteredRecords.slice(start, start + length);
                        const formattedRecords = await Promise.all(paginatedRecords.map(async (childData) => {
                            return await buildHTML(childData);
                        }));
                        $('#data-table_processing').hide();
                        callback({
                            draw: data.draw,
                            recordsTotal: totalRecords,
                            recordsFiltered: totalRecords,
                            filteredData: filteredRecords,
                            data: formattedRecords
                        });
                    } catch (error) {
                        console.error("Error fetching data from Firestore:", error);
                        $('#data-table_processing').hide();
                        callback({
                            draw: data.draw,
                            recordsTotal: 0,
                            recordsFiltered: 0,
                            data: []
                        });
                    }
                },
                order: [
                    ['5', 'desc']
                ],
                columnDefs: [{
                        targets: [4, 5],
                        type: 'date',
                        render: function(data) {
                            return data;
                        }
                    },
                    {
                        orderable: false,
                        targets: [0, 6, 7]
                    },
                ],
                "language": datatableLang,
                destroy: true,
                initComplete: function() {
                    $('.dataTables_filter input').attr('placeholder', '{{trans('lang.search_here')}}').attr('autocomplete', 'new-password').val('');
                    $('.dataTables_filter label').contents().filter(function() {
                        return this.nodeType === 3;
                    }).remove();
                }
            });
        }
        async function buildHTML(val) {
            var html = [];
            newdate = '';
            var id = val.id;
            var route1 = '{{ route('bookings.edit', ':id') }}';
            route1 = route1.replace(':id', id);
            var printRoute = '{{ route('bookings.print', ':id') }}';
            printRoute = printRoute.replace(':id', id);
            html.push('<td><a href="' + route1 + '">' + val.id + '</a></td>');
            html.push('<td>' + val.authorName + '</td>');
            if (val.status == 'Order Placed') {
                html.push('<td class="order_placed"><span>' + val.status + '</span></td>');
            } else if (val.status == 'Order Assigned') {
                html.push('<td class="order_assigned"><span>' + val.status + '</span></td>');
            } else if (val.status == 'Order Ongoing') {
                html.push('<td class="order_ongoing"><span>' + val.status + '</span></td>');
            } else if (val.status == 'Order Accepted') {
                html.push('<td class="order_accept"><span>' + val.status + '</span></td>');
            } else if (val.status == 'Order Rejected') {
                html.push('<td class="order_rejected"><span>' + val.status + '</span></td>');
            } else if (val.status == 'Order Completed') {
                html.push('<td class="order_completed"><span>' + val.status + '</span></td>');
            } else if (val.status == 'Order Cancelled') {
                html.push('<td class="order_rejected"><span>' + val.status + '</span></td>');
            } else {
                html.push('<td class="order_completed"><span>' + val.status + '</span></td>');
            }
            var price = 0;
            html.push('<td>' + val.price + '</td>');
            var bookingDate = '';
            var bookingTime = '';
            if (val.hasOwnProperty("scheduleDateTime")) {
                bookingDate = val.scheduleDateTime.toDate().toDateString();
                bookingTime = val.scheduleDateTime.toDate().toLocaleTimeString('en-US');
            }
            if (val.hasOwnProperty("newScheduleDateTime") && val.newScheduleDateTime != null && val.newScheduleDateTime != '') {
                bookingDate = val.newScheduleDateTime.toDate().toDateString();
                bookingTime = val.newScheduleDateTime.toDate().toLocaleTimeString('en-US');
            }
            html.push('<td class="dt-time">' + bookingDate + ' ' + bookingTime + '</td>');
            var date = '';
            var time = '';
            if (val.hasOwnProperty("createdAt") && val.createdAt != '') {
                try {
                    date = val.createdAt.toDate().toDateString();
                    time = val.createdAt.toDate().toLocaleTimeString('en-US');
                } catch (err) {
                }
            }
            html.push('<td class="dt-time">' + date + ' ' + time + '</td>');
            html.push('<td>' + val.serviceName + '</td>');
            html.push('<span class="action-btn"><a href="' + printRoute + '"><i class="mdi mdi-printer" style="font-size:20px;"></i></a><a href="' + route1 + '"><i class="mdi mdi-lead-pencil"></i></a><a id="' + val.id + '" class="delete-btn" name="order-delete" href="javascript:void(0)"><i class="mdi mdi-delete"></i></a></span>');
            return html;
        }
        $(document).on("click", "a[name='order-delete']", function(e) {
            var id = this.id;
            database.collection('provider_orders').doc(id).delete().then(function(result) {
                window.location.href = '{{ url()->current() }}';
            });
        });
        function buildHTMLProductstotal(snapshotsProducts) {
            var adminCommission = snapshotsProducts.adminCommission;
            var discount = snapshotsProducts.discount;
            var couponCode = snapshotsProducts.couponCode;
            var extras = snapshotsProducts.extras;
            var extras_price = snapshotsProducts.extraCharges;
            var status = snapshotsProducts.status;
            var products = snapshotsProducts;
            var totalProductPrice = 0;
            var total_price = 0;
            var intRegex = /^\d+$/;
            var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
            var val = products;
            var sub_total = parseFloat(val.provider.price);
            if (val.provider.disPrice != null && val.provider.disPrice != undefined && val.provider.disPrice != '' && val.provider.disPrice != '0') {
                sub_total = parseFloat(val.provider.disPrice)
            }
            var price = sub_total;
            sub_total = parseFloat(val.quantity) * sub_total;
            total_price += parseFloat(sub_total);
            if (intRegex.test(discount) || floatRegex.test(discount)) {
                discount = parseFloat(discount).toFixed(decimal_degits);
                total_price -= parseFloat(discount);
                if (currencyAtRight) {
                    discount_val = discount + "" + currentCurrency;
                } else {
                    discount_val = currentCurrency + "" + discount;
                }
            }
            var tax = 0;
            taxlabel = '';
            taxlabeltype = '';
            if (snapshotsProducts.hasOwnProperty('taxSetting')) {
                var total_tax_amount = 0;
                for (var i = 0; i < snapshotsProducts.taxSetting.length; i++) {
                    var data = snapshotsProducts.taxSetting[i];
                    if (data.type && data.tax) {
                        if (data.type == "percentage") {
                            tax = (data.tax * total_price) / 100;
                            taxlabeltype = "%";
                        } else {
                            tax = data.tax;
                            taxlabeltype = "fix";
                        }
                        taxlabel = data.title;
                    }
                    total_tax_amount += parseFloat(tax);
                }
                total_price = parseFloat(total_price) + parseFloat(total_tax_amount);
            }
            if (currencyAtRight) {
                var total_price_val = parseFloat(total_price).toFixed(decimal_degits) + "" + currentCurrency;
            } else {
                var total_price_val = currentCurrency + "" + parseFloat(total_price).toFixed(decimal_degits);
            }
            return total_price_val;
        }
        function clickLink(value) {
            setCookie('ondemand_section_id', value, 30);
            location.reload();
        }
          function exportBookingData(fileName,format) {
                    var columns = [];
                    columns = [{
                            key: 'id',
                            header: "{{ trans('lang.booking_id') }}"
                        },
                        {
                            key: 'authorName',
                            header: "{{ trans('lang.order_user_id') }}"
                        },
                        {
                            key: 'status',
                            header: "{{ trans('lang.status') }}"
                        },
                        {
                            key: 'price',
                            header: "{{ trans('lang.amount') }}"
                        },
                        {
                            key: 'bookingDateTime',
                            header: "{{ trans('lang.booking_date') }}"
                        },
                        {
                            key: 'createdAt',
                            header: "{{ trans('lang.created_at') }}"
                        },
                        {
                            key: 'sectionName',
                            header: "{{ trans('lang.section') }}"
                        },
                    ];
                    const filteredData = filteredRecords;
                    const fieldTypes = {};
                    const dataMapper = (record) => {
                        return columns.map((col) => {
                            const value = record[col.key];
                            if (!fieldTypes[col.key]) {
                                if (value === true || value === false) {
                                    fieldTypes[col.key] = 'boolean';
                                } else if (value && typeof value === 'object' && value.seconds) {
                                    fieldTypes[col.key] = 'date';
                                } else if (typeof value === 'number') {
                                    fieldTypes[col.key] = 'number';
                                } else if (typeof value === 'string') {
                                    fieldTypes[col.key] = 'string';
                                } else {
                                    fieldTypes[col.key] = 'string';
                                }
                            }
                            switch (fieldTypes[col.key]) {
                                case 'boolean':
                                    return value ? 'Yes' : 'No';
                                case 'date':
                                    return value ? new Date(value.seconds * 1000).toLocaleString() : '-';
                                case 'number':
                                    return typeof value === 'number' ? value : 0;
                                case 'string':
                                default:
                                    return value || '-';
                            }
                        });
                    };
                    const tableData = filteredData.map(dataMapper);
                    const data = [columns.map(col => col.header), ...tableData];
                    const columnWidths = columns.map((_, colIndex) =>
                        Math.max(...data.map(row => row[colIndex]?.toString().length || 0))
                    );
                    if (format === 'csv') {
                        const csv = data.map(row => row.map(cell => {
                            if (typeof cell === 'string' && (cell.includes(',') || cell.includes('\n') || cell.includes('"'))) {
                                return `"${cell.replace(/"/g, '""')}"`;
                            }
                            return cell;
                        }).join(',')).join('\n');
                        const blob = new Blob([csv], {
                            type: 'text/csv;charset=utf-8;'
                        });
                        saveAs(blob, `${fileName}.csv`);
                    } else if (format === 'excel') {
                        const ws = XLSX.utils.aoa_to_sheet(data, {
                            cellDates: true
                        });
                        ws['!cols'] = columnWidths.map(width => ({
                            wch: Math.min(width + 5, 30)
                        }));
                        const wb = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(wb, ws, 'Data');
                        XLSX.writeFile(wb, `${fileName}.xlsx`);
                    } else if (format === 'pdf') {
                        const {
                            jsPDF
                        } = window.jspdf;
                        const doc = new jsPDF('l', 'mm', 'a4'); // Landscape for more width
                        doc.setFontSize(12);
                        doc.text(fileName, 14, 16);
                        doc.autoTable({
                            head: [columns.map(col => col.header)],
                            body: tableData,
                            startY: 20,
                            theme: 'striped',
                            styles: {
                                cellPadding: 1,
                                fontSize: 8,
                                overflow: 'linebreak',
                            },
                            columnStyles: {
                                0: {
                                    cellWidth: 'auto'
                                }, // Adjust first column automatically
                            },
                            margin: {
                                top: 30,
                                bottom: 30
                            },
                            pageBreak: 'auto', // Ensures page break for long content
                        });
                        doc.save(`${fileName}.pdf`);
                    } else {
                        console.error('Unsupported format');
                    }
                }
    </script>
@endsection
