@extends('layouts.app')

@section('content')

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.worker_plural')}}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                <li class="breadcrumb-item active">{{trans('lang.worker_table')}}</li>
            </ol>
        </div>
        <div>
        </div>
    </div>
    <div class="container-fluid">
        <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
        {{trans('lang.processing')}}
        </div>
        <div class="admin-top-section"> 
            <div class="row">
                <div class="col-12">
                    <div class="d-flex top-title-section pb-4 justify-content-between">
                        <div class="d-flex top-title-left align-self-center">
                            <span class="icon mr-3"><img src="{{ asset('images/worker.png') }}"></span>
                            <h3 class="mb-0">{{trans('lang.worker_plural')}}</h3>
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
                                <h3 class="text-dark-2 mb-2 h4">{{trans('lang.worker_table')}}</h3>
                                <p class="mb-0 text-dark-2">{{trans('lang.worker_table_text')}}</p>
                            </div>
                            <div class="card-header-right d-flex align-items-center">
                                <div class="card-header-btn mr-3">                                                   
                                    <a class="btn-primary btn rounded-full" href="{!! route('workers.create') !!}"><i class="mdi mdi-plus mr-2"></i>{{trans('lang.worker_create')}}</a>
                                </div>
                            </div>                
                        </div>
                        <div class="card-body">
                            <div class="table-responsive m-t-10">
                                <table id="workerTable1"
                                    class="display nowrap table table-hover table-striped table-bordered table table-striped"
                                    cellspacing="0" width="100%">
                                    <thead>
                                    <tr>                                    
                                        <th class="delete-all"><input type="checkbox" id="is_active"><label class="col-3 control-label" for="is_active"><a id="deleteAll"
                                        class="do_not_delete" href="javascript:void(0)"><i class="mdi mdi-delete"></i> {{trans('lang.all')}}</a></label></th>                                    
                                        <th>{{trans('lang.worker_info')}}</th>
                                        <th>{{trans('lang.email')}}</th>
                                        <th>{{trans('lang.salary')}}</th>
                                        <th>{{trans('lang.status')}}</th>
                                        <th>{{trans('lang.onoff')}}</th>
                                        <th>{{trans('lang.actions')}}</th>
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

<script>


    var database = firebase.firestore();
    var offest = 1;
    var pagesize = 10;
    var end = null;
    var endarray = [];
    var start = null;
    var user_number = [];
    var provideUserId = "<?php echo $id; ?>";

    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_degits = 0;

    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });

    var placeholder = database.collection('settings').doc('placeHolderImage');
    placeholder.get().then(async function (snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    })
    var append_list = '';

    var refData = ''

    var providerId = provideUserId;

    ref = database.collection('providers_workers').where('providerId', '==', providerId);


    $(document).ready(function () {

        $(document.body).on('click', '.redirecttopage', function () {
            var url = $(this).attr('data-url');
            window.location.href = url;
        });
        jQuery("#data-table_processing").show();

        var fieldConfig = {
            columns: [
                { key: 'userName', header: "{{trans('lang.worker_info')}}" },
                { key: 'email', header: "{{trans('lang.email')}}" },                
                { key: 'salary', header: "{{trans('lang.salary')}}" },
                { key: 'active', header: "{{trans('lang.status')}}" },
            ],
            
            fileName: "{{trans('lang.worker_table')}}",
        };
        
        const table = $('#workerTable1').DataTable({
            pageLength: 10,
            processing: false,
            serverSide: true,
            responsive: true,
            ajax: async function (data, callback, settings) {
                const start = data.start;
                const length = data.length;
                const searchValue = data.search.value.toLowerCase();
                const orderColumnIndex = data.order[0].column;
                const orderDirection = data.order[0].dir;

                const orderableColumns = ['','userName','email','salary','','OnOff',''];

                const orderByField = orderableColumns[orderColumnIndex];

                if (searchValue.length >= 3 || searchValue.length === 0) {
                    $('#data-table_processing').show();
                }

                try {
                    const querySnapshot = await ref.get();
                    if (!querySnapshot || querySnapshot.empty) {
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
                    let filteredRecords = [];

                    await Promise.all(querySnapshot.docs.map(async (doc) => {
                        let childData = doc.data();
                        childData = childData || {};                        
                        childData.id = doc.id;
                        childData.OnOff = 'Offline';
                        if(childData.online == true){
                            childData.OnOff = "Online";
                        }
                        var salary = 0.00;
                        if (currencyAtRight) {
                            salary = parseFloat(childData.salary).toFixed(decimal_degits) + "" + currentCurrency;
                        } else {
                            salary = currentCurrency + "" + parseFloat(childData.salary).toFixed(decimal_degits);
                        }
                        childData.salary = salary ? salary : 0.00;
                        childData.userName = (childData && childData.hasOwnProperty('firstName') ? childData.firstName : '') + ' ' + (childData && childData.hasOwnProperty('lastName') ? childData.lastName : '') ;


                        if (searchValue) {
                            if (
                                (childData.userName && childData.userName.toLowerCase().includes(searchValue)) ||
                                (childData.email && childData.email.toLowerCase().includes(searchValue)) ||
                                (childData.salary && childData.salary.toLowerCase().includes(searchValue)) ||
                                (childData.OnOff && childData.OnOff.toLowerCase().includes(searchValue))
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
                        if (orderByField === 'salary') {
                            aValue = a[orderByField] ? parseFloat(a[orderByField].replace(/[^0-9.]/g, '')) || 0 : 0;
                            bValue = b[orderByField] ? parseFloat(b[orderByField].replace(/[^0-9.]/g, '')) || 0 : 0;
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
            order: [1, 'asc'],
            columnDefs: [
                { orderable: false, targets: [0, 4, 5,6] },
            ],
            "language": datatableLang,
            dom: 'lfrtipB',
            buttons: [
                {
                    extend: 'collection',
                    text: '<i class="mdi mdi-cloud-download"></i> {{trans('lang.export_as')}}',
                    className: 'btn btn-info',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: '{{trans('lang.export_excel')}}',
                            action: function (e, dt, button, config) {
                                exportData(dt, 'excel',fieldConfig);
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '{{trans('lang.export_pdf')}}',
                            action: function (e, dt, button, config) {
                                exportData(dt, 'pdf',fieldConfig);
                            }
                        },   
                        {
                            extend: 'csvHtml5',
                            text: '{{trans('lang.export_csv')}}',
                            action: function (e, dt, button, config) {
                                exportData(dt, 'csv',fieldConfig);
                            }
                        }
                    ]
                }
            ],
            initComplete: function() {
                $(".dataTables_filter").append($(".dt-buttons").detach());
                $('.dataTables_filter input').attr('placeholder', '{{trans("lang.search_here")}}').attr('autocomplete','new-password').val('');
                $('.dataTables_filter label').contents().filter(function() {
                    return this.nodeType === 3; 
                }).remove();
            }
        });
        function debounce(func, wait) {
            let timeout;
            const context = this;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }
        $('#search-input').on('input', debounce(function () {
            const searchValue = $(this).val();
            if (searchValue.length >= 3) {
                $('#data-table_processing').show();
                table.search(searchValue).draw();
            } else if (searchValue.length === 0) {
                $('#data-table_processing').show();
                table.search('').draw();
            }
        }, 300));

    });

    async function buildHTML(val) {
        html = [];
        id = val.id;
        var route1 = '{{route("workers.edit",":id")}}';
        route1 = route1.replace(':id', val.id);
        html.push('<td class="delete-all"><input type="checkbox" id="is_open_' + id + '" class="is_open" dataId="' + id + '"><label class="col-3 control-label"\n' +
            'for="is_open_' + id + '" ></label></td>');
        if (val.profilePictureURL == '' || val.profilePictureURL == null) {
            html.push('<td><img class="rounded" style="width:50px" src="' + placeholderImage + '" alt="image"><span class="left_space">' + val.userName + '</span></td>');
        } else {    
            html.push('<td><img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" class="rounded" style="width:50px" src="' + val.profilePictureURL + '" alt="image"><span class="left_space">' + val.userName + '</span></td>');
        }

        html.push('<td>' + val.email + '</td>');
        html.push('<td>' + val.salary + '</td>');
        if (val.active) {
            html.push('<td><label class="switch"><input type="checkbox" checked id="' + val.id + '" name="isActive"><span class="slider round"></span></label></td>');
        } else {
            html.push('<td><label class="switch"><input type="checkbox" id="' + val.id + '" name="isActive"><span class="slider round"></span></label></td>');
        }
        html.push('<td>'+ val.OnOff +'</td>');
        var action = '';
        action = action + '<span class="action-btn"><a href="' + route1 + '"><i class="mdi mdi-lead-pencil"></i></a>';
        action = action + '<a id="' + val.id + '" class="do_not_delete" name="user-delete" href="javascript:void(0)"><i class="mdi mdi-delete"></i></a>';
        action = action + '</span>';
        html.push(action);
        return html;

    }

    $("#is_active").click(function () {
        $("#workerTable1 .is_open").prop('checked', $(this).prop('checked'));
    });

    $("#deleteAll").click(function () {
        if ($('#workerTable1 .is_open:checked').length) {
            if (confirm("{{trans('lang.selected_delete_alert')}}")) {
                jQuery("#data-table_processing").show();
                $('#workerTable1 .is_open:checked').each(async function () {
                    
                    var dataId = $(this).attr('dataId');
                    await deleteImage('providers_workers',dataId, 'profilePictureURL');
        
                    await database.collection('providers_workers').doc(dataId).delete().then(function (result) {
                        var deleteUser = deleteUserData(dataId);
                        setTimeout(function () {
                            window.location.reload();
                        }, 7000);
                    });
                });
            }
        } else {
            alert("{{trans('lang.select_delete_alert')}}");
        }
    });
    $(document).on("click", "a[name='user-delete']", async function (e) {

        var id = this.id;
        await deleteImage('providers_workers',id, 'profilePictureURL');
        await database.collection('providers_workers').doc(id).delete().then(function (result) {
            var deleteUser = deleteUserData(id);
            setTimeout(function () {
                window.location.reload();
            }, 7000);
        });
    });

    $(document).on("click", "input[name='isActive']", function (e) {
        var ischeck = $(this).is(':checked');
        var id = this.id;
        if (ischeck) {
            database.collection('providers_workers').doc(id).update({
                'active': true
            }).then(function (result) { });
        } else {
            database.collection('providers_workers').doc(id).update({
                'active': false
            }).then(function (result) { });
        } 
    });

    async function deleteUserData(userId) {

        var dataObject = {
            "data": {
                "uid": userId
            }
        };
        var projectId = '<?php echo env('FIREBASE_PROJECT_ID') ?>';
        jQuery.ajax({
            url: 'https://us-central1-' + projectId + '.cloudfunctions.net/deleteUser',
            method: 'POST',
            contentType: "application/json; charset=utf-8",
            data: JSON.stringify(dataObject),
            success: function (data) {
                console.log('Delete user success:', data.result);
            },
            error: function (xhr, status, error) {
                var responseText = JSON.parse(xhr.responseText);
                console.log('Delete user error:', responseText.error);
            }
        });
    }


</script>

@endsection