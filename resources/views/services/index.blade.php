@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{ trans('lang.service_plural') }}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ trans('lang.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('lang.service_table') }}</li>
                </ol>
            </div>
            <div>
            </div>
        </div>
        <div class="row px-5 mb-2">
            <div class="col-12">
                <span class="font-weight-bold text-danger service-limit-note"></span>
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
                                <span class="icon mr-3"><img src="{{ asset('images/service.png') }}"></span>
                                <h3 class="mb-0">{{ trans('lang.service_plural') }}</h3>
                                <span class="counter ml-3 total_count"></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="table-list">
                <div class="row">
                    <div class="col-12">
                        <div class="card border">
                            <div class="card-header d-flex justify-content-between align-items-center border-0">
                                <div class="card-header-title">
                                    <h3 class="text-dark-2 mb-2 h4">{{ trans('lang.service_table') }}</h3>
                                    <p class="mb-0 text-dark-2">{{ trans('lang.service_table_text') }}</p>
                                </div>
                                <div class="card-header-right d-flex align-items-center">
                                    <div class="card-header-btn mr-3">
                                        <a class="btn-primary btn rounded-full" href="{!! route('services.create') !!}"><i class="mdi mdi-plus mr-2"></i>{{ trans('lang.service_create') }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive m-t-10">
                                    <table id="serviceTable" class="display nowrap table table-hover table-striped table-bordered table table-striped" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="delete-all"><input type="checkbox" id="is_active"><label class="col-3 control-label" for="is_active"><a id="deleteAll" class="do_not_delete" href="javascript:void(0)"><i class="mdi mdi-delete"></i> {{ trans('lang.all') }}</a></label></th>
                                                <th>{{ trans('lang.name') }}</th>
                                                <th>{{ trans('lang.category') }}</th>
                                                <th>{{ trans('lang.section') }}</th>
                                                <th>{{ trans('lang.price') }}</th>
                                                <th>{{ trans('lang.publish') }}</th>
                                                <th>{{ trans('lang.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="append_list1">
                                        </tbody>
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

@include('layouts.footer')
@section('scripts')
    <script type="text/javascript">
        var database = firebase.firestore();
        var offest = 1;
        var pagesize = 10;
        var end = null;
        var endarray = [];
        var start = null;
        var user_id = "<?php echo $id; ?>";
        var append_list = '';
        var user_number = [];
        var refData = database.collection('providers_services').where('author', "==", user_id);
        var ref = database.collection('providers_services').orderBy('createdAt', 'desc').where('author', "==", user_id);

        var currentCurrency = '';
        var currencyAtRight = false;
        var decimal_degits = 0;
        var itemLimit = '-1';
        var refCurrency = database.collection('currencies').where('isActive', '==', true);
        refCurrency.get().then(async function(snapshots) {
            var currencyData = snapshots.docs[0].data();
            currentCurrency = currencyData.symbol;
            currencyAtRight = currencyData.symbolAtRight;

            if (currencyData.decimal_degits) {
                decimal_degits = currencyData.decimal_degits;
            }
        });



        $(document).ready(function() {
            var order_status = jQuery('#order_status').val();
            var search = jQuery("#search").val();


            $(document.body).on('click', '.redirecttopage', function() {
                var url = $(this).attr('data-url');
                window.location.href = url;
            });
            jQuery('#search').hide();

            $(document.body).on('change', '#selected_search', function() {

                if (jQuery(this).val() == 'status') {
                    jQuery('#order_status').show();
                    jQuery('#search').hide();
                } else {

                    jQuery('#order_status').hide();
                    jQuery('#search').show();

                }
            });


            jQuery("#data-table_processing").show();
            append_list = document.getElementById('append_list1');
            append_list.innerHTML = '';
            ref.limit(pagesize).get().then(async function(snapshots) {
                html = '';
                if (snapshots.docs.length > 0) {
                    $('.total_count').text(snapshots.docs.length);

                } else {
                    $('.total_count').text(0);
                }
                html = await buildHTML(snapshots);
                jQuery("#data-table_processing").hide();
                if (html != '') {
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.push(snapshots.docs[0]);
                }
                if (snapshots.docs.length < pagesize) {

                    jQuery("#data-table_paginate").hide();
                } else {

                    jQuery("#data-table_paginate").show();
                }

                const table = $('#serviceTable').DataTable({
                    columnDefs: [

                        {
                            orderable: false,
                            targets: [0, 5, 6]
                        },
                        {
                            targets: 4,
                            type: "html-num-fmt"
                        },
                    ],
                    order: [
                        ['4', 'asc']
                    ],
                   "language": datatableLang,
                    responsive: true
                });
                table.on('search.dt', function() {
                    var filteredCount = table.rows({
                        search: 'applied'
                    }).count();
                    $('.total_count').text(filteredCount); // Update count
                });

            });



            database.collection('users').where('id', '==', user_id).get().then(async function(snapshots) {
                data = snapshots.docs[0].data();

                await database.collection('settings').doc("vendor").get().then(async function(snapshots) {
                    var subscriptionSetting = snapshots.data();
                    if (subscriptionSetting.subscription_model == true) {
                        subscriptionModel = true;
                    }
                });

                var commisionModel = false;
                if (data.hasOwnProperty('section_id') && data.section_id != '' && data.section_id != null) {
                    var commissionModel = database.collection('sections').where('id', '==', data.section_id);

                    await commissionModel.get().then((snapshots) => {
                            var commissionSetting =snapshots.docs[0].data();
                            if (commissionSetting && commissionSetting.adminCommision && commissionSetting.adminCommision.enable) {
                                commisionModel = true;
                            }
                    });

                }
                if (subscriptionModel || commisionModel) {
                    if (data.hasOwnProperty('subscription_plan') && data.subscription_plan != null && data.subscription_plan != '') {
                        itemLimit = data.subscription_plan.itemLimit;
                    }
                }

                if (itemLimit != '-1') {
                    $('.service-limit-note').html('{{ trans('lang.note') }} : {{ trans('lang.your_service_limit_is') }} ' + itemLimit + ' {{ trans('lang.so_only_first') }} ' + itemLimit +
                        ' {{ trans('lang.services_will_visible_to_customer') }}')
                }

            });


        });

        async function buildHTML(snapshots) {
            var html = '';
            await Promise.all(snapshots.docs.map(async (listval) => {
                var val = listval.data();
                var getData = await getListData(val);
                html += getData;
            }));
            return html;
        }

        async function getListData(val) {
            var html = '';
            html = html + '<tr>';
            newdate = '';
            var id = val.id;
            var route1 = '{{ route('services.edit', ':id') }}';
            route1 = route1.replace(':id', id);
            var catName = await getCategoryName(val.categoryId);
            if (catName == '') {
                catName = '{{ trans('lang.unknown') }}';
            }
            html = html + '<td class="delete-all"><input type="checkbox" id="is_open_' + id + '" class="is_open" dataId="' + id + '"><label class="col-3 control-label"\n' +
                'for="is_open_' + id + '" ></label></td>';

            html = html + '<td><a href="' + route1 + '">' + val.title + '</a></td>';

            html += '<td>' + catName + '</td>';

            if (val.hasOwnProperty("sectionId")) {
                var sectionName = await getSectionName(val.sectionId);
                if (sectionName == '') {
                    sectionName = '{{ trans('lang.unknown') }}';
                }
                html = html + '<td>' + sectionName + '</td>';
            } else {
                html = html + '<td></td>';
            }

            if (val.disPrice == "0") {
                if (val.priceUnit == "Hourly") {
                    if (currencyAtRight) {
                        html = html + '<td data-html="true" data-order="' + val.price + '">' + parseFloat(val.price).toFixed(decimal_degits) + '' + currentCurrency + '/hr</td>';
                    } else {
                        html = html + '<td data-html="true" data-order="' + val.price + '">' + currentCurrency + parseFloat(val.price).toFixed(decimal_degits) + '/hr</td>';
                    }
                } else {
                    if (currencyAtRight) {
                        html = html + '<td data-html="true" data-order="' + val.price + '">' + parseFloat(val.price).toFixed(decimal_degits) + '' + currentCurrency + '</td>';
                    } else {
                        html = html + '<td data-html="true" data-order="' + val.price + '">' + currentCurrency + parseFloat(val.price).toFixed(decimal_degits) + '</td>';
                    }
                }
            } else {
                if (val.priceUnit == "Hourly") {
                    if (currencyAtRight) {
                        html = html + '<td data-html="true" data-order="' + val.disPrice + '">' + parseFloat(val.disPrice).toFixed(decimal_degits) + '' + currentCurrency + '/hr  <s>' + parseFloat(val.price).toFixed(decimal_degits) + '' + currentCurrency + '/hr</s></td>';
                    } else {
                        html = html + '<td data-html="true" data-order="' + val.disPrice + '">' + '' + currentCurrency + parseFloat(val.disPrice).toFixed(decimal_degits) + '/hr  <s>' + currentCurrency + '' + parseFloat(val.price).toFixed(decimal_degits) + '/hr</s> </td>';
                    }
                } else {
                    if (currencyAtRight) {
                        html = html + '<td data-html="true" data-order="' + val.disPrice + '">' + parseFloat(val.disPrice).toFixed(decimal_degits) + '' + currentCurrency + '  <s>' + parseFloat(val.price).toFixed(decimal_degits) + '' + currentCurrency + '</s></td>';
                    } else {
                        html = html + '<td data-html="true" data-order="' + val.disPrice + '">' + '' + currentCurrency + parseFloat(val.disPrice).toFixed(decimal_degits) + ' <s>' + currentCurrency + '' + parseFloat(val.price).toFixed(decimal_degits) + '</s> </td>';
                    }
                }
            }

            if (val.publish) {
                html = html + '<td><label class="switch"><input type="checkbox" checked id="' + val.id + '" name="publish"><span class="slider round"></span></label></td>';
            } else {
                html = html + '<td><label class="switch"><input type="checkbox" id="' + val.id + '" name="publish"><span class="slider round"></span></label></td>';
            }

            html = html + '<td class="action-btn"><a href="' + route1 + '"><i class="mdi mdi-lead-pencil"></i></a><a id="' + val.id + '" name="service-delete" href="javascript:void(0)"><i class="mdi mdi-delete"></i></a></td>';


            html = html + '</tr>';
            return html;

        }


        /* toggal publish action code start*/
        $(document).on("click", "input[name='publish']", function(e) {
            var ischeck = $(this).is(':checked');
            var id = this.id;
            if (ischeck) {
                database.collection('providers_services').doc(id).update({
                    'publish': true
                }).then(function(result) {

                });
            } else {
                database.collection('providers_services').doc(id).update({
                    'publish': false
                }).then(function(result) {

                });
            }
        });

        async function getSectionName(sectionId) {
            let sectionName = '';
            if (sectionId != '' && sectionId != null) {
                let sectionDoc = await database.collection('sections').doc(sectionId).get();
                if (sectionDoc.exists) {
                    let sectionData = sectionDoc.data();
                    sectionName = sectionData.name;
                }
            }
            return sectionName;
        }

        $(document).on("click", "a[name='service-delete']", async function(e) {

            var id = this.id;
            await deleteMultipleImages('providers_services', id, 'photos');
            await database.collection('providers_services').doc(id).delete().then(function(result) {
                deleteServiceData(id);
                setTimeout(function() {
                    window.location.reload();
                }, 3000);
            });
        });

        $("#is_active").click(function() {
            $("#serviceTable .is_open").prop('checked', $(this).prop('checked'));
        });

        $("#deleteAll").click(function() {
            if ($('#serviceTable .is_open:checked').length) {
                if (confirm("{{ trans('lang.selected_delete_alert') }}")) {
                    jQuery("#data-table_processing").show();
                    $('#serviceTable .is_open:checked').each(async function() {

                        var dataId = $(this).attr('dataId');
                        await deleteMultipleImages('providers_services', dataId, 'photos');
                        await database.collection('providers_services').doc(dataId).delete().then(function(result) {
                            deleteServiceData(dataId);
                            setTimeout(function() {
                                window.location.reload();
                            }, 5000);
                        });
                    });
                }
            } else {
                alert("{{ trans('lang.select_delete_alert') }}");
            }
        });
        async function getCategoryName(categoryId) {
            var catName = '';
            await database.collection('provider_categories').where('id', '==', categoryId).get().then(async function(snapshots) {
                if (snapshots.docs.length > 0) {
                    var data = snapshots.docs[0].data();
                    catName = data.title;
                }
            });
            return catName;

        }
        async function deleteServiceData(serviceId) {
            await database.collection('favorite_service').where('service_id', '==', serviceId).get().then(async function(snapshotsItem) {

                if (snapshotsItem.docs.length > 0) {
                    snapshotsItem.docs.forEach((temData) => {
                        var item_data = temData.data();

                        database.collection('favorite_service').doc(item_data.id).delete().then(function() {

                        });
                    });
                }

            });
        }
    </script>
@endsection
