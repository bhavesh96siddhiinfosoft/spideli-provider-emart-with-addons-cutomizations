@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">

            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{ trans('lang.service_plural') }}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{!! route('dashboard') !!}">{{ trans('lang.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{!! route('services') !!}">{{ trans('lang.service_plural') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ trans('lang.service_create') }}</li>
                </ol>
            </div>
        </div>

        <div>
            <div class="card-body">
                <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
                    {{ trans('lang.processing') }}
                </div>
                <div class="error_top" style="display:none"></div>
                <div class="row vendor_payout_create">
                    <div class="vendor_payout_create-inner">

                        <fieldset>
                            <legend>{{ trans('lang.service_information') }}</legend>
                            <div class="form-group row width-50">
                                <input type="hidden" class="form-control author_name">
                                <input type="hidden" class="form-control author_profile">

                                <label class="col-3 control-label">{{ trans('lang.service_name') }}</label>
                                <div class="col-7">
                                    <input type="text" class="form-control service_name" required>
                                    <div class="form-text text-muted">
                                        {{ trans('lang.service_name_name_help') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label ">{{ trans('lang.select_section') }}</label>
                                <div class="col-7">
                                    <select name="section_id" class="form-control" id="section_id">
                                        <option value="">{{ trans('lang.select_section') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{ trans('lang.item_category_id') }}</label>
                                <div class="col-7">
                                    <select id='item_category' class="form-control" required>
                                        <option value="">{{ trans('lang.select_category') }}</option>
                                    </select>
                                    <div class="form-text text-muted">
                                        {{ trans('lang.item_category_id_help') }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{ trans('lang.sub_category_id') }}</label>
                                <div class="col-7">
                                    <select id='sub_category' class="form-control" required>
                                        <option value="">{{ trans('lang.select_sub_category') }}</option>
                                    </select>
                                    <div class="form-text text-muted">
                                        {{ trans('lang.sub_category_id_help') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{ trans('lang.price') }}</label>
                                <div class="col-7">
                                    <input type="number" class="form-control price" required>
                                    <div class="form-text text-muted">
                                        {{ trans('lang.item_price_help') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{ trans('lang.item_discount') }}</label>
                                <div class="col-7">
                                    <input type="number" class="form-control item_discount">
                                    <div class="form-text text-muted">
                                        {{ trans('lang.item_discount_help') }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{ trans('lang.price_unit') }}</label>
                                <div class="col-7">
                                    <select id='price_unit' name="price_unit" class="form-control" required>
                                        <option value="Hourly">{{ trans('lang.hourly') }}</option>
                                        <option value="Fixed">{{ trans('lang.fixed') }}</option>
                                    </select>

                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{ trans('lang.item_image') }}</label>
                                <div class="col-7">
                                    <input type="file" id="service_image" required>
                                    <div class="placeholder_img_thumb service_image"></div>
                                    <div id="uploding_image"></div>
                                    <div class="form-text text-muted">
                                        {{ trans('lang.item_image_help') }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <div class="form-check">
                                    <input type="checkbox" class="item_publish" id="item_publish">
                                    <label class="col-3 control-label" for="item_publish">{{ trans('lang.item_publish') }}</label>
                                </div>
                            </div>

                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{ trans('lang.item_description') }}</label>
                                <div class="col-7">
                                    <textarea rows="8" class="form-control item_description" id="item_description"></textarea>
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{ trans('lang.address') }}</label>
                                <div class="col-7">
                                    <input type="text" class="form-control address" id="address" autocomplete="on">

                                </div>
                            </div>
                            <div class="form-group row width-100">
                                <label class="col-3 control-label">{{ trans('lang.Days') }}</label>
                                <div class="col-7">
                                    <input type="checkbox" class="days" name="days" id="monday" value="Monday">
                                    <label class="col-3 control-label" for="monday">{{ trans('lang.monday') }}</label>
                                    <input type="checkbox" class="days" name="days" id="tuesday" value="Tuesday">
                                    <label class="col-3 control-label" for="tuesday">{{ trans('lang.tuesday') }}</label>
                                    <input type="checkbox" class="days" name="days" id="wednesday" value="Wednesday">
                                    <label class="col-3 control-label" for="wednesday">{{ trans('lang.wednesday') }}</label>
                                    <input type="checkbox" class="days" name="days" id="thursday" value="Thursday">
                                    <label class="col-3 control-label" for="thursday">{{ trans('lang.thursday') }}</label>
                                    <input type="checkbox" class="days" name="days" id="friday" value="Friday">
                                    <label class="col-3 control-label" for="friday">{{ trans('lang.friday') }}</label>
                                    <input type="checkbox" class="days" name="days" id="saturday" value="Saturday">
                                    <label class="col-3 control-label" for="saturday">{{ trans('lang.saturday') }}</label>
                                    <input type="checkbox" class="days" name="days" id="sunday" value="Sunday">
                                    <label class="col-3 control-label" for="sunday">{{ trans('lang.sunday') }}</label>

                                </div>
                            </div>
                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{ trans('lang.start_Time') }}</label>
                                <div class="col-7">
                                    <input type="time" class="form-control" id="start_Time" required>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{ trans('lang.end_Time') }}</label>
                                <div class="col-7">
                                    <input type="time" class="form-control" id="end_Time" required>
                                </div>
                            </div>

                        </fieldset>

                    </div>
                </div>

                <div class="form-group col-12 text-center btm-btn">
                    <button type="button" class="btn btn-primary  create_item_btn"><i class="fa fa-save"></i>
                        {{ trans('lang.save') }}
                    </button>
                    <a href="{!! route('services') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>

    <script>
        var database = firebase.firestore();
        var UserId = "<?php echo $id; ?>";
        var authorName = '';
        var authorProfilePic = '';
        var authorPhone = '';
        var createdAt = firebase.firestore.FieldValue.serverTimestamp();
        var photos = [];
        var serviceImageFileName = [];
        var serviceImageCount = 0;
        var author = database.collection('users').where('id', '==', UserId);
        var categories = database.collection('provider_categories').where('publish', '==', true);
        var googleApiKey = '';
        var serviceImagesCount = 0;
        var itemLimit = '-1';
        var createdItem = 0;
        var subscriptionModel = false;
        var commissionModel = false;
        var subscription_plan = '';
        var subscriptionPlanId = '';
        var subscriptionExpiryDate = '';
        var subscriptionTotalOrders = '';
        var isSectionIdExist = false;
        var mapType = '';
        var mapTypeDoc = database.collection('settings').doc('DriverNearBy');
        mapTypeDoc.get().then(async function(snapshots) {
            var mapTypeData = snapshots.data();
            mapType = mapTypeData.selectedMapType;
        })
        var subscriptionBusinessModel = database.collection('settings').doc("vendor");
        subscriptionBusinessModel.get().then(async function(snapshots) {
            var subscriptionSetting = snapshots.data();
            if (subscriptionSetting.subscription_model == true) {
                subscriptionModel = true;
            }
        });
        $(document).ready(function() {

            database.collection('sections').where('serviceTypeFlag', '==', 'ondemand-service').get().then(async function(snapshots) {
                snapshots.docs.forEach((listval) => {
                    var data = listval.data();
                    $('#section_id').append($("<option></option>")
                        .attr("value", data.id)
                        .attr("data-type", data.serviceTypeFlag)
                        .text(data.name + ' (' + data.serviceType + ')'));
                });
            });

            author.get().then(async function(snapshots) {
                snapshots.docs.forEach(async (listval) => {
                    var data = listval.data();
                    authorName = data.firstName + ' ' + data.lastName;
                    authorProfilePic = data.profilePictureURL;
                    authorPhone = data.phoneNumber;
                    if (data.hasOwnProperty('section_id') && data.section_id != null && data.section_id != '') {
                        $('#section_id').val(data.section_id).trigger('change');
                        $('#section_id').prop('disabled', true);
                        isSectionIdExist = true;
                        await database.collection('sections').doc(data.section_id).get().then(
                            async function(snapshot) {
                                if (snapshot.data().adminCommision != null && snapshot.data()
                                    .adminCommision != '') {
                                    if (snapshot.data().adminCommision.enable) {
                                        commissionModel = true;
                                    }
                                }
                            });
                    }
                    subscription_plan = data != '' && data.subscription_plan ? data.subscription_plan : null;
                    subscriptionPlanId = data != '' && data.subscriptionPlanId ? data.subscriptionPlanId : null;
                    subscriptionExpiryDate = data != '' && data.subscriptionExpiryDate ? data.subscriptionExpiryDate : null;
                    subscriptionTotalOrders = data != '' && data.subscriptionTotalOrders ? data.subscriptionTotalOrders : null;
                    if (subscriptionModel || commissionModel) {
                        if (data.hasOwnProperty('subscription_plan') && data.subscription_plan != null && data.subscription_plan != '') {
                            itemLimit = data.subscription_plan.itemLimit;
                        }
                    }
                });
            });

            $('#section_id').on('change', function() {
                var section_id = $(this).val();
                if (section_id) {
                    categories.where('parentCategoryId', '==', null).where('sectionId', '==', section_id).get().then(async function(snapshots) {
                        if (snapshots.docs.length > 0) {
                            $('#item_category').html('<option value="">{{ trans('lang.select_category') }}</option>');
                            snapshots.docs.forEach((listval) => {
                                var data = listval.data();
                                $('#item_category').append($("<option></option>")
                                    .attr("value", data.id)
                                    .text(data.title));
                            });
                        } else {
                            $('#item_category').html('<option value="">{{ trans('lang.select_category') }}</option>');
                        }
                    });
                } else {
                    $('#item_category').html('<option value="">{{ trans('lang.select_category') }}</option>');
                }
                $('#sub_category').html('<option value="">{{ trans('lang.select_sub_category') }}</option>');
            })

            $('#item_category').on('change', function() {
                var categoryId = $(this).val();
                if (categoryId) {
                    categories.where('parentCategoryId', '==', categoryId).get().then(async function(snapshots) {
                        if (snapshots.docs.length > 0) {
                            $('#sub_category').html('<option value="">{{ trans('lang.select_sub_category') }}</option>');
                            snapshots.docs.forEach((listval) => {
                                var data = listval.data();
                                $('#sub_category').append($("<option></option>")
                                    .attr("value", data.id)
                                    .text(data.title));
                            });
                        } else {
                            $('#sub_category').html('<option value="">{{ trans('lang.select_sub_category') }}</option>');
                        }
                    });
                } else {
                    $('#sub_category').html('<option value="">{{ trans('lang.select_sub_category') }}</option>');
                }
            })

            /* 02#27: this used to compare the whole `types` list with
             * JSON.stringify, which throws whenever Google returns an extra
             * type, a different order, or no locality at all - and the
             * listener then died BEFORE setting lat and lng, so the address
             * was rejected as invalid. The helper matches on membership and
             * never throws. Attached once, not on every click. */
            function initialize(id) {
                spideliAttachPlaceAutocomplete(id);
            }

            function init(id) {
                function getPlaceSuggestions(query) {
                    return $.ajax({
                        url: `https://nominatim.openstreetmap.org/search?format=json&q=${query}`,
                        dataType: 'json'
                    });
                }

                // Autocomplete setup
                $(".address").autocomplete({
                    source: function(request, response) {
                        getPlaceSuggestions(request.term).done(function(data) {
                            response(data.map(place => ({
                                label: place.display_name,
                                lat: place.lat,
                                lon: place.lon,
                                address: place.address || {}

                            })));

                        });

                    },
                    select: function(event, ui) {
                        var address_name = ui.item.label;
                        var address_lat = ui.item.lat;
                        var address_lng = ui.item.lon;
                        var address_city = ui.item.address.city || ui.item.address.town || ui.item.address.village || '';
                        var address_state = ui.item.address.state || '';
                        var address_country = ui.item.address.country || '';
                        $(".address").val(address_name).attr('lat', address_lat).attr('lng', address_lng).attr('city', address_city).attr('state', address_state).attr('country', address_country);
                    }

                });
            }

            $(document).on("click", "#address", function() {
                var id = $(this).attr('id');
                if (mapType == 'google') {
                    initialize(id);
                } else {
                    init(id);
                }
            });


            const serviceDocs = database.collection('providers_services').where('author', '==', UserId).get().then((querySnapshot) => {
                createdItem = querySnapshot.size; // This will give you the number of documents
            }).catch((error) => {
                console.error("Error fetching documents: ", error);
            });

            $(".create_item_btn").click(async function() {
                jQuery("#data-table_processing").show();
                if (parseInt(itemLimit) == -1 || parseInt(createdItem) < parseInt(itemLimit)) {
                    var days = [];
                    var id = database.collection("tmp").doc().id;
                    var name = $(".service_name").val();
                    var price = $(".price").val();
                    var discount = $(".item_discount").val();
                    var category = $("#item_category option:selected").val();
                    var sub_category = $("#sub_category option:selected").val();
                    var description = $("#item_description").val();
                    var itemPublish = $(".item_publish").is(":checked");
                    var price_unit = $("#price_unit option:selected").val();
                    var address = $(".address").val();
                    var endTime = $("#end_Time").val();
                    var startTime = $("#start_Time").val();
                    var longitude = parseFloat($('.address').attr('lng'));
                    var latitude = parseFloat($('.address').attr('lat'));
                    var section_id = $("#section_id").val();


                    $("input:checkbox[name=days]:checked").each(function() {
                        days.push($(this).val());
                    });

                    if (discount == '') {
                        discount = "0";
                    }



                    if (name == '') {
                        jQuery("#data-table_processing").hide();
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>{{ trans('lang.enter_service_name_error') }}</p>");
                        window.scrollTo(0, 0);
                    } else if (section_id == '') {
                        jQuery("#data-table_processing").hide();
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>{{ trans('lang.select_section_error') }}</p>");
                        window.scrollTo(0, 0);
                    } else if (category == '') {
                        jQuery("#data-table_processing").hide();
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>{{ trans('lang.select_service_category_error') }}</p>");
                        window.scrollTo(0, 0);
                    } else if (sub_category == '') {
                        jQuery("#data-table_processing").hide();
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>{{ trans('lang.select_sub_category_error') }}</p>");
                        window.scrollTo(0, 0);
                    } else if (price == '') {
                        jQuery("#data-table_processing").hide();
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>{{ trans('lang.enter_service_price_error') }}</p>");
                        window.scrollTo(0, 0);
                    } else if (parseInt(price) < parseInt(discount)) {
                        jQuery("#data-table_processing").hide();
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>{{ trans('lang.price_should_not_less_then_discount_error') }}</p>");
                        window.scrollTo(0, 0);
                    } else if (description == '') {
                        jQuery("#data-table_processing").hide();
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>{{ trans('lang.enter_service_description_error') }}</p>");
                        window.scrollTo(0, 0);
                    } else if (isNaN(latitude) || isNaN(longitude)) {
                        jQuery("#data-table_processing").hide();
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>{{ trans('lang.service_select_address_error') }}</p>");
                        window.scrollTo(0, 0);
                    } else if (days.length == 0) {
                        jQuery("#data-table_processing").hide();
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>{{ trans('lang.service_select_days_error') }}</p>");
                        window.scrollTo(0, 0);
                    } else if (startTime == '' || endTime == '') {
                        jQuery("#data-table_processing").hide();
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>{{ trans('lang.service_select_time_error') }}</p>");
                        window.scrollTo(0, 0);
                    } else if (startTime > endTime) {
                        jQuery("#data-table_processing").hide();
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append("<p>{{ trans('lang.start_time_grater_than_endtime_error') }}</p>");
                        window.scrollTo(0, 0);
                    } else {
                        jQuery("#data-table_processing").show();
                        await storeServiceImageData().then(async (IMG) => {
                            if (IMG.length == 0) {
                                $(".error_top").show();
                                $(".error_top").html("");
                                $(".error_top").append("<p>{{ trans('lang.image_required') }}</p>");
                                window.scrollTo(0, 0);
                                jQuery("#data-table_processing").hide();
                                return false;
                            }
                            geoFirestore.collection('providers_services').doc(id).set({
                                'title': name,
                                'sectionId': section_id,
                                'price': price,
                                'disPrice': discount,
                                'categoryId': category,
                                'subCategoryId': sub_category,
                                'photos': IMG,
                                'priceUnit': price_unit,
                                "address": address,
                                'author': UserId,
                                'authorName': authorName,
                                'authorProfilePic': authorProfilePic,
                                'phoneNumber': authorPhone,
                                'description': description,
                                'publish': itemPublish,
                                'createdAt': createdAt,
                                'days': days,
                                'endTime': endTime,
                                'startTime': startTime,
                                'reviewsCount': 0,
                                'id': id,
                                'reviewsSum': 0,
                                'latitude': latitude,
                                'longitude': longitude,
                                'coordinates': new firebase.firestore.GeoPoint(latitude, longitude),
                                'g' : {
                                    'geohash' : encodeGeohash(latitude, longitude),
                                    'geopoint' : new firebase.firestore.GeoPoint(latitude, longitude)
                                },
                                'subscription_plan': subscription_plan,
                                'subscriptionPlanId': subscriptionPlanId,
                                'subscriptionExpiryDate': subscriptionExpiryDate,
                                'subscriptionTotalOrders': subscriptionTotalOrders
                            }).then(async function(result) {
                                if (isSectionIdExist) {
                                    window.location.href = '{{ route('services') }}';
                                } else {
                                    var commissionObj = null;
                                    await database.collection('sections').doc(section_id).get().then(async function(snapshot) {
                                        commissionObj = snapshot.data().adminCommision;
                                        await database.collection('users').doc(UserId).update({
                                            'adminCommission': commissionObj,
                                            'section_id': section_id
                                        }).then(function(result) {
                                            window.location.href = '{{ route('services') }}';
                                        })
                                    })
                                }
                            });
                        }).catch(err => {
                            $(".error_top").show();
                            $(".error_top").html("");
                            $(".error_top").append("<p>" + err + "</p>");
                            window.scrollTo(0, 0);
                            jQuery("#data-table_processing").hide();
                        });
                    }
                } else {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append(
                        "<p>{{ trans('lang.create_service_limit_exceed') }}</p>"
                    );
                    window.scrollTo(0, 0);
                    jQuery("#data-table_processing").hide();
                }

            })
        })
        var storageRef = firebase.storage().ref('images');

        $("#service_image").resizeImg({


            callback: function(base64str) {


                var val = $('#service_image').val().toLowerCase();

                var ext = val.split('.')[1];

                var docName = val.split('fakepath')[1];

                var filename = $('#service_image').val().replace(/C:\\fakepath\\/i, '')

                var timestamp = Number(new Date());

                var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;

                serviceImageFileName.push(filename);

                serviceImageCount++;

                photos_html = '<span class="image-item" id="photo_' + serviceImageCount + '"><span class="remove-btn" data-id="' + serviceImageCount + '" data-img="' + base64str + '"><i class="fa fa-remove"></i></span><img class="rounded" width="50px" id="" height="auto" src="' + base64str + '"></span>'

                $(".service_image").append(photos_html);

                photos.push(base64str);

                $("#service_image").val('');


            }

        });

        async function storeServiceImageData() {

            var newPhoto = [];

            if (photos.length > 0) {

                await Promise.all(photos.map(async (servicePhoto, index) => {

                    servicePhoto = servicePhoto.replace(/^data:image\/[a-z]+;base64,/, "");

                    var uploadTask = await storageRef.child(serviceImageFileName[index]).putString(servicePhoto, 'base64', {
                        contentType: 'image/jpg'
                    });

                    var downloadURL = await uploadTask.ref.getDownloadURL();

                    newPhoto.push(downloadURL);

                }));

            }

            return newPhoto;

        }


        $(document).on("click", ".remove-btn", function() {
            var id = $(this).attr('data-id');
            var photo_remove = $(this).attr('data-img');
            $("#photo_" + id).remove();
            index = photos.indexOf(photo_remove);
            if (index > -1) {
                photos.splice(index, 1); 
            }
        });
    </script>
@endsection
