<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" <?php if (
    str_replace('_', '-', app()->getLocale()) ==
    'ar' || @$_COOKIE['is_rtl'] == 'true'
) { ?> dir="rtl" <?php } ?>>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('images/spideli-circle.png') }}">
        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
        <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <?php if (str_replace('_', '-', app()->getLocale()) == 'ar' || @$_COOKIE['is_rtl'] == 'true') { ?>
        <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
        <?php } ?>
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <?php if (str_replace('_', '-', app()->getLocale()) == 'ar' || @$_COOKIE['is_rtl'] == 'true') { ?>
        <link href="{{ asset('css/style_rtl.css') }}" rel="stylesheet">
        <?php } ?>
        <link href="{{ asset('css/icons/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/plugins/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
        <link href="{{ asset('css/colors/blue.css') }}" rel="stylesheet">
        <link href="{{ asset('css/chosen.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap-tagsinput.css') }}" rel="stylesheet">
        <!-- Datatable css -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <!-- @yield('style') -->
        <?php if (isset($_COOKIE['provider_panel_color'])) { ?>
        <style type="text/css">
            .topbar {
                background: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .sidebar-nav ul li a {
                border-bottom: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .sidebar-nav ul li a:hover i {
                color: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .vendor_payout_create-inner fieldset legend {
                background: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            a {
                color: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            a:hover,
            a:focus {
                color: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            a.link:hover,
            a.link:focus {
                color: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            html body blockquote {
                border-left: 5px solid<?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .text-warning {
                color: <?php echo $_COOKIE['provider_panel_color'];
                ?> !important;
            }
            .text-info {
                color: <?php echo $_COOKIE['provider_panel_color'];
                ?> !important;
            }
            .sidebar-nav ul li a:hover {
                color: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .btn-primary {
                background: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
                border: 1px solid<?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .sidebar-nav>ul>li.active>a {
                color: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
                border-left: 3px solid<?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .sidebar-nav>ul>li.active>a i {
                color: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .bg-info {
                background-color: <?php echo $_COOKIE['provider_panel_color'];
                ?> !important;
            }
            .bellow-text ul li>span {
                color: <?php echo $_COOKIE['provider_panel_color'];
                ?>
            }
            .table tr td.redirecttopage {
                color: <?php echo $_COOKIE['provider_panel_color'];
                ?>
            }
            ul.rating {
                color: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            nav-link.active {
                background-color: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .nav-tabs.card-header-tabs .nav-link:hover {
                background: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .nav-tabs .nav-item.show .nav-link,
            .nav-tabs .nav-link.active {
                color: #fff;
                background: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .btn-warning,
            .btn-warning.disabled {
                background: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
                border: 1px solid<?php echo $_COOKIE['provider_panel_color'];
                ?>;
                box-shadow: none;
            }
            .payment-top-tab .nav-tabs.card-header-tabs .nav-link.active,
            .payment-top-tab .nav-tabs.card-header-tabs .nav-link:hover {
                border-color: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .nav-tabs.card-header-tabs .nav-link span.badge-success {
                background: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .nav-tabs.card-header-tabs .nav-link.active span.badge-success,
            .nav-tabs.card-header-tabs .nav-link:hover span.badge-success,
            .sidebar-nav ul li a.active,
            .sidebar-nav ul li a.active:hover,
            .sidebar-nav ul li.active a.has-arrow:hover,
            .topbar ul.dropdown-user li a:hover {
                color: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .sidebar-nav ul li a.has-arrow:hover::after,
            .sidebar-nav .active>.has-arrow::after,
            .sidebar-nav li>.has-arrow.active::after,
            .sidebar-nav .has-arrow[aria-expanded="true"]::after,
            .sidebar-nav ul li a:hover {
                border-color: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            [type="checkbox"]:checked+label::before {
                border-right: 2px solid<?php echo $_COOKIE['provider_panel_color'];
                ?>;
                border-bottom: 2px solid<?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .btn-primary:hover,
            .btn-primary.disabled:hover {
                background: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
                border: 1px solid<?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .btn-primary.active,
            .btn-primary:active,
            .btn-primary:focus,
            .btn-primary.disabled.active,
            .btn-primary.disabled:active,
            .btn-primary.disabled:focus,
            .btn-primary.active.focus,
            .btn-primary.active:focus,
            .btn-primary.active:hover,
            .btn-primary.focus:active,
            .btn-primary:active:focus,
            .btn-primary:active:hover,
            .open>.dropdown-toggle.btn-primary.focus,
            .open>.dropdown-toggle.btn-primary:focus,
            .open>.dropdown-toggle.btn-primary:hover,
            .btn-primary.focus,
            .btn-primary:focus,
            .btn-primary:not(:disabled):not(.disabled).active:focus,
            .btn-primary:not(:disabled):not(.disabled):active:focus,
            .show>.btn-primary.dropdown-toggle:focus,
            .btn-warning:hover,
            .btn-warning:hover,
            .btn-warning.disabled:hover,
            .btn-warning.active.focus,
            .btn-warning.active:focus,
            .btn-warning.active:hover,
            .btn-warning.focus:active,
            .btn-warning:active:focus,
            .btn-warning:active:hover,
            .open>.dropdown-toggle.btn-warning.focus,
            .open>.dropdown-toggle.btn-warning:focus,
            .open>.dropdown-toggle.btn-warning:hover,
            .btn-warning.focus,
            .btn-warning:focus {
                background: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
                border-color: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
                box-shadow: 0 0 0 0.2rem<?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .language-options select option,
            .pagination>li>a.page-link:hover {
                background: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .nav-tabs.card-header-tabs .active.nav-item .nav-link {
                background: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .print-btn button {
                border: 2px solid<?php echo $_COOKIE['provider_panel_color'];
                ?>;
                color: <?php echo $_COOKIE['provider_panel_color'];
                ?>;
            }
            .business-analytics .card-box i {
                background: <?php echo $_COOKIE['provider_panel_color']; ?>;
            }
            .order-status span.count {
                color: <?php echo $_COOKIE['provider_panel_color']; ?>;
            }
        </style>
        <?php } ?>
        <?php $id = Auth::user()->getvendorId(); ?>
        <script type="text/javascript">
            var cuser_id = '<?php echo $id; ?>';
        </script>
    </head>
    <body>
        <div id="app" class="fix-header fix-sidebar card-no-border">
            <div id="main-wrapper">
                <div id="data-table_processing" class="page-overlay" style="display:none;">
                    <div class="overlay-text">
                        <img src="{{asset('images/spinner.gif')}}">
                    </div>
                </div>
                <header class="topbar">
                    <nav class="navbar top-navbar navbar-expand-md navbar-light">
                        @include('layouts.header')
                    </nav>
                </header>
                <aside class="left-sidebar non-printable">
                    <!-- Sidebar scroll-->
                    <div class="scroll-sidebar">
                        @include('layouts.menu')
                    </div>
                    <!-- End Sidebar scroll-->
                </aside>
            </div>
            <main class="py-4">
                @yield('content')
            </main>
            <div class="modal fade" id="notification_order" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered notification-main" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title order_subject" id="exampleModalLongTitle"></h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h6><span id="auth_accept_name" class="order_message"></span></h6>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary"><a href="#" id="notification_url">{{trans('lang.go')}}</a></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="notification_book_table_order" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered notification-main" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title dinein_order_subject" id="exampleModalLongTitle"></h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h6><span id="auth_accept_name_book_table" class="dinein_order_msg"></span></h6>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary"><a href="{{ url('booktable') }}" id="notification_book_table_url">{{trans('lang.go')}}</a>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="notification_accepted_order" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered notification-main" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title driver_accepted_subject" id="exampleModalLongTitle"></h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h6><span id="np_accept_name" class="driver_accepted_msg"></span></h6>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary"><a href="#" id="notification_accepted_a">{{trans('lang.go')}}</a></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('js/waves.js') }}"></script>
        <script src="{{ asset('js/sidebarmenu.js') }}"></script>
        <script src="{{ asset('assets/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
        <script src="{{ asset('js/custom.min.js') }}"></script>
        <script src="{{ asset('js/jquery.resizeImg.js') }}"></script>
        <!-- <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> -->
        <!-- <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script> -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
        <script type="text/javascript">
            jQuery(window).scroll(function() {
                var scroll = jQuery(window).scrollTop();
                if (scroll <= 60) {
                    jQuery("body").removeClass("sticky");
                } else {
                    jQuery("body").addClass("sticky");
                }
            });
            const datatableLang = {
                "decimal":        "",
                "emptyTable":     "{{ trans('lang.no_record_found') }}",
                "info":           "{{ trans('lang.datatable_info') }}", 
                "infoEmpty":      "{{ trans('lang.datatable_info_empty') }}", 
                "infoFiltered":   "{{ trans('lang.datatable_info_filtered') }}", 
                "lengthMenu":     "{{ trans('lang.datatable_length_menu') }}",
                "loadingRecords": "{{ trans('lang.loading') }}",
                "processing":     "{{ trans('lang.processing') }}",
                "search":         "{{ trans('lang.search') }}",
                "zeroRecords":    "{{ trans('lang.no_record_found') }}",
                "paginate": {
                    "first":      "{{ trans('lang.first') }}",
                    "last":       "{{ trans('lang.last') }}",
                    "next":       "{{ trans('lang.next') }}",
                    "previous":   "{{ trans('lang.previous') }}"
                },
                "aria": {
                    "sortAscending":  ": {{ trans('lang.sort_asc') }}",
                    "sortDescending": ": {{ trans('lang.sort_desc') }}"
                }
            };
        </script>
        <script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-app.js"></script>
        <script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-firestore.js"></script>
        <script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-storage.js"></script>
        <script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-auth.js"></script>
        <script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-database.js"></script>
        <script src="{{ asset('js/geofirestore.js') }}"></script>
        <script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
        <script src="{{ asset('js/chosen.jquery.js') }}"></script>
        <script src="{{ asset('js/bootstrap-tagsinput.js') }}"></script>
        <script src="{{ asset('js/crypto-js.js') }}"></script>
        <script src="{{ asset('js/jquery.cookie.js') }}"></script>
        <script src="{{ asset('js/jquery.validate.js') }}"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <!-- Datatable script -->
        <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.24/jspdf.plugin.autotable.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        @yield('scripts')
        <script type="text/javascript">
            var languages_list_main = [];
            var database = firebase.firestore();
            var geoFirestore = new GeoFirestore(database);
            var refLogo = database.collection('settings').doc("globalSettings");
            refLogo.get().then(async function(snapshots) {
                var globalSettings = snapshots.data();
                $("#logo_web").attr('src', globalSettings.providerLogo);
            });
            var version = database.collection('settings').doc("Version");
            version.get().then(async function(snapshots) {
                var version_data = snapshots.data();
                if (version_data == undefined) {
                    database.collection('settings').doc('Version').set({});
                }
                try {
                    $('.web_version').html("V:" + version_data.web_version);
                } catch (error) {
                }
            });
            var section_id = '';
            var service_type = '';
            database.collection('users').doc(cuser_id).get().then(async function(usersnapshots) {
                var userData = usersnapshots.data();
                var username = userData.firstName + ' ' + userData.lastName;
                $('#username').text(username);
                $('#email').text(userData.email);
                if (userData.hasOwnProperty('profilePictureURL') && userData.profilePictureURL != "") {
                    $('.userimage').attr('src', userData.profilePictureURL);
                }
            });
            var langcount = 0;
            var languages_list = database.collection('settings').doc('languages');
            languages_list.get().then(async function(snapshotslang) {
                snapshotslang = snapshotslang.data();
                if (snapshotslang != undefined) {
                    snapshotslang = snapshotslang.list;
                    languages_list_main = snapshotslang;
                    snapshotslang.forEach((data) => {
                        if (data.isActive == true) {
                            langcount++;
                            $('#language_dropdown').append($("<option></option>").attr("value", data.slug)
                                .text(data.title));
                        }
                    });
                    if (langcount > 1) {
                        $("#language_dropdown_box").css('visibility', 'visible');
                    }
                    <?php if (session()->get('locale')) { ?>
                    $("#language_dropdown").val("<?php echo session()->get('locale'); ?>");
                    <?php } ?>
                }
            });
            var url = "{{ route('changeLang') }}";
            $(".changeLang").change(function() {
                var slug = $(this).val();
                languages_list_main.forEach((data) => {
                    if (slug == data.slug) {
                        if (data.is_rtl == undefined) {
                            setCookie('is_rtl', 'false', 365);
                        } else {
                            setCookie('is_rtl', data.is_rtl.toString(), 365);
                        }
                        window.location.href = url + "?lang=" + slug;
                    }
                });
            });
            function setCookie(cname, cvalue, exdays) {
                const d = new Date();
                d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                let expires = "expires=" + d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            }
            async function loadGoogleMapsScript() {
                var globalKeySnapshot = await database.collection('settings').doc("googleMapKey").get();
                var globalKeyData = globalKeySnapshot.data();
                googleMapKey = globalKeyData.key;
                const script = document.createElement('script');
                script.src = "https://maps.googleapis.com/maps/api/js?key=" + googleMapKey + "&libraries=places";
                script.onload = function() {
                    navigator.geolocation.getCurrentPosition(GeolocationSuccessCallback, GeolocationErrorCallback);
                };
                document.head.appendChild(script);
            }
            const GeolocationSuccessCallback = (position) => {
                if (position.coords != undefined) {
                    default_latitude = position.coords.latitude
                    default_longitude = position.coords.longitude
                    setCookie('default_latitude', default_latitude, 365);
                    setCookie('default_longitude', default_longitude, 365);
                }
            };
            const GeolocationErrorCallback = (error) => {
                console.log('Error: You denied for your default Geolocation', error.message);
                setCookie('default_latitude', '23.022505', 365);
                setCookie('default_longitude', '72.571365', 365);
            };
            loadGoogleMapsScript();
            var orderPlacedSubject = '';
            var orderPlacedMsg = '';
            var orderCancelledSubject = '';
            var orderCancelledMsg = '';
            database.collection('dynamic_notification').get().then(async function(snapshot) {
                if (snapshot.docs.length > 0) {
                    snapshot.docs.map(async (listval) => {
                        val = listval.data();
                        if (val.type == "booking_placed") {
                            orderPlacedSubject = val.subject;
                            orderPlacedMsg = val.message;
                        } else if (val.type == "service_cancelled") {
                            orderCancelledSubject = val.subject;
                            orderCancelledMsg = val.message;
                        }
                    });
                }
            });
            var pageloadded = 0;
            var route1 = '{{ route('bookings.edit', ':id') }}';
            database.collection('provider_orders').where('provider.author', "==", cuser_id).onSnapshot(function(doc) {
                if (pageloadded) {
                    doc.docChanges().forEach(function(change) {
                        val = change.doc.data();
                        if (change.type == "added") {
                            if (val.status == "Order Placed") {
                                $('.order_subject').text(orderPlacedSubject);
                                $('.order_message').text(orderPlacedMsg);
                                jQuery("#notification_order").modal('show');
                            }
                        } else if (change.type == "modified") {
                            if (val.status == "Order Cancelled") {
                                $('.order_subject').text(orderCancelledSubject);
                                $('.order_message').text(orderCancelledMsg);
                                jQuery("#notification_order").modal('show');
                            }
                        }
                        if (route1) {
                            jQuery("#notification_url").attr("href", route1.replace(':id', val.id));
                        }
                    })
                } else {
                    pageloadded = 1;
                }
            });
            database.collection('settings').doc("notification_setting").get().then(async function(snapshots) {
                var data = snapshots.data();
                serviceJson = data.serviceJson;
                if (serviceJson != '' && serviceJson != null) {
                    $.ajax({
                        type: 'POST',
                        data: {
                            serviceJson: btoa(serviceJson),
                        },
                        url: "{{ route('storeServiceFile') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            checkFlag = true;
                        }
                    });
                }
            });
            function exportData(dt, format, config) {
                const {
                    columns,
                    fileName = 'Export',
                } = config;
                const filteredRecords = dt.ajax.json().filteredData;
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
                const tableData = filteredRecords.map(dataMapper);
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
            
            function encodeGeohash(latitude, longitude, precision = 10) {
                const BASE32 = "0123456789bcdefghjkmnpqrstuvwxyz";
                let idx = 0;
                let bit = 0;
                let even = true;
                let geohash = "";
                let latMin = -90, latMax = 90;
                let lonMin = -180, lonMax = 180;
                while (geohash.length < precision) {
                    if (even) {
                        let mid = (lonMin + lonMax) / 2;
                        if (longitude > mid) {
                            idx = idx * 2 + 1;
                            lonMin = mid;
                        } else {
                            idx = idx * 2;
                            lonMax = mid;
                        }
                    } else {
                        let mid = (latMin + latMax) / 2;
                        if (latitude > mid) {
                            idx = idx * 2 + 1;
                            latMin = mid;
                        } else {
                            idx = idx * 2;
                            latMax = mid;
                        }
                    }
                    even = !even;
                    if (++bit == 5) {
                        geohash += BASE32.charAt(idx);
                        bit = 0;
                        idx = 0;
                    }
                }
                return geohash;
            }



        /* ---- Addresses: never print the word "null" ----------------------
         *
         * Bug report 02 point 18: *"123 Yaounde St, null, Tsinga"*.
         *
         * There are TWO sources of that word and this handles both.
         *
         * 1. THE PANELS PRODUCE IT. Every address join here is guarded with
         *    `hasOwnProperty('address')`, which is TRUE when the field exists
         *    and holds null - so `'' + order.address.address` appends the
         *    string "null". Live on 2 Oct: `address` is null on 56 of 123
         *    orders and `landmark` on 66, so this is the common case by far.
         *
         * 2. IT IS BAKED INTO THE STORED TEXT. `locality` arrives from the
         *    phone already joined, with "null" where the geocoder had no
         *    component: "18, null, Yaounde, Region du Centre, null, Cameroun".
         *    27 orders carry that exact string. NO GUARD CAN FIX THOSE - the
         *    word is inside the value - so the segments are dropped here
         *    instead, which repairs the history on screen without a migration.
         *
         * Keeping both in one place matters: there are 25 of these joins
         * across the three panels, and they had all drifted apart.
         * ------------------------------------------------------------------ */

        /* One address component, cleaned. Splits on commas because the value
         * is often itself a joined string (see 2 above). */
        function spideliCleanAddressPart(value) {
            if (value === null || value === undefined) {
                return '';
            }

            var text = String(value).trim();

            if (text === '') {
                return '';
            }

            var parts = text.split(',').map(function (part) {
                return part.trim();
            }).filter(function (part) {
                var lower = part.toLowerCase();
                return part !== '' && lower !== 'null' && lower !== 'undefined' && lower !== 'nil';
            });

            return parts.join(', ');
        }

        /* The whole address as one line. `keys` picks which parts and in what
         * order; the default is how an address reads aloud.
         *
         * Returns '' when there is nothing to show, so the caller can hide the
         * row rather than print an empty label. */
        function spideliFormatAddress(address, keys) {
            if (!address || typeof address !== 'object') {
                return '';
            }

            var order = keys || ['address', 'locality', 'landmark'];
            var seen = {};
            var out = [];

            order.forEach(function (key) {
                var cleaned = spideliCleanAddressPart(address[key]);

                if (cleaned === '') {
                    return;
                }

                /* The same text is often held in two of these fields. Print it
                 * once: "Tsinga, Tsinga" reads like a different kind of bug. */
                var fingerprint = cleaned.toLowerCase();

                if (seen[fingerprint]) {
                    return;
                }

                seen[fingerprint] = true;
                out.push(cleaned);
            });

            return out.join(', ');
        }


        /* ---- Reading a Google place without throwing ------------------------
         *
         * Related to bug report 02 point 27. The client reports the WORKER
         * CREATION SCREEN CRASHING on location input. Their wording - "closes
         * automatically" - describes the phone, so the reported fault is the
         * app's. THIS PANEL HAS THE SAME FAULT ANYWAY, and it was found while
         * checking.
         *
         * What was here:
         *
         *     place.address_components
         *         .filter(f => JSON.stringify(f.types) === JSON.stringify(['locality','political']))
         *         [0].long_name
         *
         * That demands the `types` list match EXACTLY - same members, same
         * order, nothing extra. Google varies all three routinely. When nothing
         * matches, filter() returns [] and [0].long_name THROWS.
         *
         * Measured against address shapes Google really returns:
         *
         *     a tidy city address .................. survives
         *     a Yaounde plus-code .................. survives
         *     no locality (rural or regional) ...... THROWS
         *     locality with one extra type ......... THROWS
         *     types in a different order ........... THROWS
         *     a city-state (no state level) ........ THROWS
         *     a business picked by name ............ THROWS
         *
         * The tidy case working is exactly why this was never noticed.
         *
         * WHY IT LOOKS LIKE A CRASH RATHER THAN A WARNING: the listener dies
         * before the line that writes lat and lng onto the input. The address
         * box fills in, the coordinates never do, and the save then reads
         * parseFloat(attr('lng')) as NaN and rejects the address. The user
         * picks a real address and is told it is invalid.
         * ------------------------------------------------------------------ */

        /* One component, by type. Matches on MEMBERSHIP of the types list, not
         * on the whole list, and never throws. */
        function spideliPlaceComponent(place, type) {
            if (!place || !place.address_components || !place.address_components.length) {
                return '';
            }

            for (var i = 0; i < place.address_components.length; i++) {
                var component = place.address_components[i];

                if (!component || !component.types || !component.types.length) {
                    continue;
                }

                for (var j = 0; j < component.types.length; j++) {
                    if (component.types[j] === type) {
                        return component.long_name || component.short_name || '';
                    }
                }
            }

            return '';
        }

        /* The town, under whichever name this country uses for it. Plenty of
         * addresses have no `locality` at all. */
        function spideliPlaceCity(place) {
            return spideliPlaceComponent(place, 'locality')
                || spideliPlaceComponent(place, 'postal_town')
                || spideliPlaceComponent(place, 'administrative_area_level_2')
                || spideliPlaceComponent(place, 'sublocality_level_1')
                || spideliPlaceComponent(place, 'sublocality');
        }

        /* Writes a chosen place onto the address input the old code wrote to -
         * same attributes, same names, so nothing downstream changes.
         *
         * Returns whether COORDINATES were set. A place typed but never chosen
         * from the list has no geometry, and the caller needs to know that
         * rather than save a worker with no position. */
        function spideliApplyPlaceToAddressInput(input, place) {
            var field = $(input);

            if (!field.length || !place) {
                return false;
            }

            var address = place.formatted_address || place.name || '';

            if (address !== '') {
                field.val(address);
            }

            field.attr('city', spideliPlaceCity(place));
            field.attr('state', spideliPlaceComponent(place, 'administrative_area_level_1'));
            field.attr('country', spideliPlaceComponent(place, 'country'));

            var geometry = place.geometry;
            var location = geometry && geometry.location;

            if (!location || typeof location.lat !== 'function' || typeof location.lng !== 'function') {
                /* Left as they were rather than cleared: on an edit screen the
                 * record already has a position, and wiping it because someone
                 * clicked the box would be worse than leaving it alone. */
                return false;
            }

            field.attr('lat', location.lat());
            field.attr('lng', location.lng());

            return true;
        }

        /* Attaches the autocomplete ONCE.
         *
         * The old code called initialize() from a click handler on the input,
         * so every click built another Autocomplete with another listener on
         * the same box - they stacked up for as long as the page was open. */
        function spideliAttachPlaceAutocomplete(id, onPicked) {
            var input = document.getElementById(id);

            if (!input || typeof google === 'undefined' || !google.maps || !google.maps.places) {
                return;
            }

            if ($(input).data('spideli-places')) {
                return;
            }

            $(input).data('spideli-places', true);

            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                var hasCoordinates = spideliApplyPlaceToAddressInput(input, place);

                if (!hasCoordinates) {
                    console.warn('that place has no coordinates; pick one from the list rather than typing it');
                }

                if (typeof onPicked === 'function') {
                    onPicked(place, hasCoordinates);
                }
            });
        }
        </script>
    </body>
</html>
