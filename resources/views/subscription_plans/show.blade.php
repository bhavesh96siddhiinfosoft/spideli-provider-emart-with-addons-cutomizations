<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title id="app_name"><?php echo @$_COOKIE['meta_title']; ?></title>
        {{-- <link rel="icon" id="favicon" type="image/x-icon" href="<?php echo str_replace('images/', 'images%2F', @$_COOKIE['favicon']); ?>"> --}}
        <link rel="icon" type="image/x-icon" href="{{ asset('images/logo-light-icon.png') }}">
        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        <!-- Styles -->
        <link href="{{ asset('assets/plugins/slick/slick.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/plugins/slick/slick-theme.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/plugins/slick/slick-lightbox.css') }}" rel="stylesheet">

        <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <!-- @yield('style') -->
    </head>

    <body>

        <div class="page-wrapper py-5 pl-0">
            <div class="container-fluid">
                <div id="data-table_processing" class="page-overlay" style="display:none;">
                    <div class="overlay-text">
                        <img src="{{ asset('images/spinner.gif') }}">
                    </div>
                </div>
                <div class="col-lg-11 ml-lg-auto mr-lg-auto">
                    <div class="title text-center mb-5">
                        <h2 class="text-primary">{{ trans('lang.business_plans') }}</h2>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex top-title-section pb-4 mb-4 justify-content-between">
                                <div class="d-flex top-title-left align-start-center">
                                    <div class="top-title">
                                        <h3 class="mb-0">{{ trans('lang.choose_your_business_plan') }}</h3>
                                        <p class="mb-0 text-dark-2">
                                            {{ trans('lang.choose_your_business_plan_description') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row sections-div justify-content-center">

                        <div class="col-6 text-center">
                            <div class="cat-slider mb-4 mt-3" id="sections"></div>
                        </div>
                        <select id="section-input" hidden>
                            <option value="">{{ trans('lang.select') }} {{ trans('lang.section') }}</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="row" id="default-plan"></div>
                        </div>
                    </div>
                    <div class="row backBtn d-none">
                        <div class="col-12 text-center"><a href="{{ url('/') }}" class="btn btn-primary">{{trans('lang.back')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
        <script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-app.js"></script>
        <script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-firestore.js"></script>
        <script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-storage.js"></script>
        <script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-auth.js"></script>
        <script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-database.js"></script>
        <script src="{{ asset('js/crypto-js.js') }}"></script>
        <script src="{{ asset('js/jquery.cookie.js') }}"></script>
        <script src="{{ asset('js/jquery.validate.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/plugins/slick/slick.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/plugins/slick/slick-lightbox.js') }}"></script>

        <script type="text/javascript">
            var database = firebase.firestore();
            var currentCurrency = '';
            var currencyAtRight = false;
            var decimal_degits = 0;
            var userId = "{{ $userId }}";

            var createdAt = firebase.firestore.FieldValue.serverTimestamp();
            var refCurrency = database.collection('currencies').where('isActive', '==', true);
            refCurrency.get().then(async function(snapshots) {
                var currencyData = snapshots.docs[0].data();
                currentCurrency = currencyData.symbol;
                currencyAtRight = currencyData.symbolAtRight;

                if (currencyData.decimal_degits) {
                    decimal_degits = currencyData.decimal_degits;
                }
            });
            var placeholderImage = '';
            var placeholder = database.collection('settings').doc('placeHolderImage');
            placeholder.get().then(async function(snapshotsimage) {
                var placeholderImageData = snapshotsimage.data();
                placeholderImage = placeholderImageData.image;
            })
            var commisionModel = false;
            var AdminCommission = '';
            var providerSpecificCommission = false;
            var commissionObj = '';
            var sectionId = null;
            var sectionLength = 0;
            var adminEmail = '';
            var subscriptionModel = false;
            var subscriptionBusinessModel = database.collection('settings').doc("vendor");
            subscriptionBusinessModel.get().then(async function(snapshots) {
                var businessModelSettings = snapshots.data();
                if (businessModelSettings.hasOwnProperty('subscription_model') &&
                    businessModelSettings.subscription_model == true) {
                    subscriptionModel = true;
                }
            });
            var activeSubscriptionId = '';
            var subscriptionHistory = database.collection('subscription_history').where('user_id', '==', userId).orderBy(
                'createdAt', 'desc');
            subscriptionHistory.get().then(async function(snapshot) {
                if (snapshot.docs.length > 0) {
                    var data = snapshot.docs[0].data();
                    activeSubscriptionId = data.subscription_plan.id;
                }
            });

            var refSection = database.collection('sections').where('isActive', '==', true).where('serviceType', '==', 'On Demand Service');
            refSection.get().then(async function(sectionsSnapshot) {
                sectionsSnapshot.docs.forEach((listval) => {
                    var data = listval.data();
                    $('#section-input').append(
                        $("<option></option>")
                        .attr("value", data.id)
                        .text(data.name)
                    );
                });
            });
            var refContact = database.collection('settings').doc('ContactUs');
            refContact.get().then(async function(snapshots) {
                var data = snapshots.data();
                adminEmail = data.Email;
            })
            var ref = database.collection('settings').doc("globalSettings");
            ref.get().then(async function(snapshots) {
                var globalSettings = snapshots.data();
                provider_panel_color = globalSettings.provider_panel_color;
                setCookie('meta_title', globalSettings.applicationName, 365);
                document.title = globalSettings.applicationName;
                setCookie('provider_panel_color', provider_panel_color, 365);
                setCookie('favicon', globalSettings.providerLogo, 365);
            })

            function setCookie(cname, cvalue, exdays) {
                const d = new Date();
                d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                let expires = "expires=" + d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            }

            jQuery('#data-table_processing').show();
            database.collection('users').where('id', '==', userId).get().then(async function(snapshot) {
                var userData = snapshot.docs[0].data();
                if (userData.hasOwnProperty('adminCommission') && userData.adminCommission != null && userData.adminCommission != '') {
                    commissionObj = userData.adminCommission;
                    providerSpecificCommission = true;
                    if (userData.adminCommission.type == "percentage") {
                        AdminCommission = userData.adminCommission.commission + '' + '%';
                    } else {
                        if (currencyAtRight) {
                            AdminCommission = userData.adminCommission.commission.toFixed(decimal_degits) + currentCurrency;
                        } else {
                            AdminCommission = currentCurrency + userData.adminCommission.commission.toFixed(decimal_degits);
                        }
                    }
                }
                if (userData.hasOwnProperty('section_id') && userData.section_id != '' && userData.section_id !=
                    null) {
                    sectionId = userData.section_id;
                    $('#section-input').val(sectionId);
                    $('.sections-div').addClass('d-none');
                    getCommissionDataBySection();
                } else {
                    refSection.get().then(async function(sectionsSnapshot) {
                        sections = document.getElementById('sections');
                        sections.innerHTML = '';
                        sectionshtml = buildHTMLSections(sectionsSnapshot);
                        sections.innerHTML = sectionshtml;
                        sectionLength = sectionsSnapshot.docs.length;
                        slickcatCarousel();
                    })
                }


            });
            async function getSubscriptionPlan() {
                $('#default-plan').html('');
                database.collection('subscription_plans').where('sectionId', '==', sectionId).where('isEnable', '==', true).get().then(async function(
                    snapshots) {

                    if (commisionModel == false && snapshots.docs.length == 1) {
                        $('#default-plan').html('<p class="text-danger">{{ trans('lang.no_subscription_plan_is_created_contact_to_admin') }}</p><span class="font-weight-bold">{{trans('lang.email')}} : ' + adminEmail + '<span>');
                    }
                    let plans = [];
                    snapshots.docs.map(doc => {
                        let data = doc.data();
                        plans.push({
                            ...data
                        }); // Include document ID if needed
                    });

                    plans.sort((a, b) => b.isCommissionPlan - a.isCommissionPlan);

                    var html = '';
                    var activeClass = '';
                    plans.map(async (data) => {

                        var activeClass = (data.id == activeSubscriptionId) ?
                            '<span class="badge badge-success">{{ trans('lang.active') }}</span>' :
                            '';

                        if (data.isCommissionPlan == true) {

                            if (commisionModel) {


                                html += `<div class="col-md-3 mb-3 pricing-card pricing-card-commission">
                                            <div class="pricing-card-inner">
                                                <div class="pricing-card-top">
                                                    <div class="d-flex align-items-center pb-4">
                                                        <span class="pricing-card-icon mr-4"><img src="${data.image}"></span>
                                                    </div>
                                                    <div class="pricing-card-price">
                                                        <h3 class="text-dark-2">${data.name} ${activeClass}</h3>
                                                        <span class="price-day">${data.description}</span>
                                                    </div>
                                                </div>
                                                <div class="pricing-card-content pt-3 mt-3 border-top">
                                                    <ul class="pricing-card-list text-dark-2">`;
                                html +=
                                    `<li><span class="mdi mdi-check"></span>{{ trans('lang.pay_commission_of') }} ${AdminCommission} {{ trans('lang.on_each_booking') }} </li>`
                                data.plan_points.map(async (list) => {
                                    html +=
                                        `<li><span class="mdi mdi-check"></span>${list}</li>`
                                });
                                html +=
                                    `<li><span class="mdi mdi-check"></span>{{ trans('lang.unlimited') }} {{ trans('lang.booking_plural') }}</li>`
                                html +=
                                    `<li><span class="mdi mdi-check"></span>{{ trans('lang.unlimited') }} {{ trans('lang.services') }}</li>`

                                html += `</ul>
                                                </div>`;
                                var buttonText = (activeClass == '') ?
                                    "{{ trans('lang.select_plan') }}" :
                                    "{{ trans('lang.renew_plan') }}";

                                html += `<div class="pricing-card-btm">
                                                    <a href="javascript:void(0)" onClick="saveSubscriptionPlan('${data.id}')" class="btn rounded-full active-btn btn-primary">${buttonText}</a>
                                                </div>`;

                                html += `</div>
                                </div>`;
                            }
                        } else {
                            if (subscriptionModel) {

                                const translations = {
                                    chatingOption: "{{ trans('lang.chating_option') }}",
                                    mobileAppAccess: "{{ trans('lang.mobile_app_access') }}"
                                };
                                var features = data.features;
                                var buttonText = (activeClass == '') ?
                                    "{{ trans('lang.select_plan') }}" :
                                    "{{ trans('lang.renew_plan') }}";

                                if (data.type == "free") {

                                    var routeHtml =
                                        `<a href="javascript:void(0)" onClick="saveSubscriptionPlan('${data.id}')" class="btn rounded-full">${buttonText}</a>`
                                } else {
                                    var route =
                                        "{{ route('subscription-plans.checkout', [':id', ':sectionId']) }}";
                                    route = route.replace(":id", data.id);
                                    route = route.replace(":sectionId", sectionId);
                                    var routeHtml =
                                        `<a href="${route}" class="btn rounded-full">${buttonText}</a>`
                                }


                                html += `<div class="col-md-3 mb-3  pricing-card pricing-card-subscription ${data.name}">
                                    <div class="pricing-card-inner">
                                        <div class="pricing-card-top">
                                        <div class="d-flex align-items-center pb-4">
                                            <span class="pricing-card-icon mr-4"><img src="${data.image}"></span>
                                            <h2 class="text-dark-2">${data.name} ${activeClass}</h2>
                                        </div>
                                        <p class="text-muted">${data.description}</p>
                                        <div class="pricing-card-price">
                                            <h3 class="text-dark-2">${data.type!=="free"? (currencyAtRight? parseFloat(data.price).toFixed(decimal_degits)+currentCurrency:currentCurrency+parseFloat(data.price).toFixed(decimal_degits)):'<span style="color:red;">Free</span>'}</h3>
                                            <span class="price-day">${data.expiryDay==-1? "{{ trans('lang.unlimited') }}":data.expiryDay} {{ trans('lang.days') }}</span>
                                        </div>
                                        </div>
                                        <div class="pricing-card-content pt-3 mt-3 border-top">
                                        <ul class="pricing-card-list text-dark-2">
                                            ${features.chat? `<li><span class="mdi mdi-check"></span>${translations.chatingOption}</li>`:`<li><span class="mdi mdi-close"></span>${translations.chatingOption}</li>`}                                          
                                            
                                            ${features.ownerMobileApp? `<li><span class="mdi mdi-check"></span>${translations.mobileAppAccess}</li>`:`<li><span class="mdi mdi-close"></span>${translations.mobileAppAccess}</li>`}    
                                            <li><span class="mdi mdi-check"></span>${data.orderLimit==-1? "{{ trans('lang.unlimited') }}":data.orderLimit} {{ trans('lang.booking_plural') }}</li>
                                            <li><span class="mdi mdi-check"></span>${data.itemLimit==-1? "{{ trans('lang.unlimited') }}":data.itemLimit} {{ trans('lang.services') }}</li>
                                        </ul>
                                        </div>`;

                                html += `<div class="pricing-card-btm">${routeHtml}</div>`;

                                html += `</div>
                                </div>`;
                            }
                        }
                    });
                    (activeSubscriptionId == '') ? $('.backBtn').addClass('d-none'): $('.backBtn').removeClass('d-none')
                    $('#default-plan').append(html);
                    jQuery('#data-table_processing').hide();
                });
            }

            var id_order = database.collection('tmp').doc().id;

            async function saveSubscriptionPlan(id) {
                await database.collection('subscription_plans').where('id', '==', id).get().then(async function(snapshot) {
                    planData = snapshot.docs[0].data();
                    var currentDate = new Date();
                    if (planData.expiryDay != '-1') {
                        currentDate.setDate(currentDate.getDate() + parseInt(planData.expiryDay));
                        expiryDay = firebase.firestore.Timestamp.fromDate(currentDate);
                    } else {
                        expiryDay = null;
                    }
                    await database.collection('users').doc(userId).update({
                        'subscription_plan': planData,
                        'subscriptionPlanId': id,
                        'subscriptionExpiryDate': expiryDay,
                        'subscriptionTotalOrders': planData.orderLimit,
                        'section_id': sectionId,
                        'adminCommission': commissionObj
                    }).then(async function(result) {

                        await database.collection('subscription_history').doc(id_order).set({
                            'id': id_order,
                            'user_id': userId,
                            'expiry_date': expiryDay,
                            'createdAt': createdAt,
                            'subscription_plan': planData,
                            'payment_type': 'free'
                        }).then(async function(snapshot) {
                            let providerSnapshot = await database.collection('providers_services').where('author', '==', userId).get();
                            let updatePromises = [];
                            providerSnapshot.forEach(doc => {
                                updatePromises.push(
                                    doc.ref.update({
                                        'subscription_plan': planData,
                                        'subscriptionPlanId': id,
                                        'subscriptionExpiryDate': expiryDay,
                                        'subscriptionTotalOrders': planData.orderLimit
                                    })
                                );
                            });
                            await Promise.all(updatePromises);

                            var url = "{{ route('setSubcriptionFlag') }}";

                            $.ajax({

                                type: 'POST',
                                url: url,
                                data: {
                                    email: "<?php echo Auth::user()->email; ?>",
                                    isSubscribed: 'true'
                                },

                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                        .attr('content')
                                },

                                success: function(data) {
                                    if (data.access) {
                                        window.location.href =
                                            '{{ route('dashboard') }}';
                                    }
                                }
                            });

                        });

                    });
                })

            }


            function buildHTMLSections(sectionsSnapshot) {
                var html = '';
                sectionsSnapshot.docs.forEach((listval) => {
                    var val = listval.data();
                    var category_id = val.id;
                    if (val.sectionImage) {
                        photo = val.sectionImage;
                    } else {
                        photo = placeholderImage;
                    }
                    html = html + '<div class="cat-item px-2 py-1"  service_type="' + val
                        .serviceType + '" data-name="' + val.name + '" data-id="' + val.id +
                        '"><a class="bg-white d-block p-2 text-center shadow-sm cat-link d-flex flex-column align-items-center" href="javascript:void(0)"><img alt="#" src="' +
                        photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImage +
                        '\'" class="img-fluid mb-2"><p class="m-0 small">' + val.name +
                        '</p></a></div>';
                });
                return html;
            }


            function slickcatCarousel() {
                var slidToshow = (sectionLength > 1) ? sectionLength - 1 : 1
                $('.cat-slider').slick({
                    slidesToShow: slidToshow,
                    arrows: true,
                    responsive: [{
                            breakpoint: 1199,
                            settings: {
                                arrows: true,
                                centerMode: true,
                                centerPadding: '40px',
                                slidesToShow: slidToshow,
                            }
                        }, {
                            breakpoint: 992,
                            settings: {
                                arrows: true,
                                centerMode: true,
                                centerPadding: '40px',
                                slidesToShow: slidToshow,
                            }
                        }, {
                            breakpoint: 768,
                            settings: {
                                arrows: true,
                                centerMode: true,
                                centerPadding: '40px',
                                slidesToShow: slidToshow,
                            }
                        },
                        {
                            breakpoint: 560,
                            settings: {
                                arrows: false,
                                centerMode: true,
                                centerPadding: '20px',
                                slidesToShow: slidToshow,
                            }
                        }
                    ]
                });

                $('#sections .slick-current').addClass('section-selected');
                var element = $('.cat-slider .cat-item.section-selected');
                sectionId = element.attr('data-id');

                $('#section-input').val(sectionId);
                getCommissionDataBySection();
            }

            $(document).on('click', '.slick-slide', function() {
                $('.slick-slide').removeClass('section-selected');
                $(this).addClass('section-selected');
                var element = $('.cat-slider .cat-item.section-selected');
                sectionId = element.attr('data-id');
                $('#section-input').val(sectionId);
                getCommissionDataBySection();
            });

            async function getCommissionDataBySection() {
                sectionId = $('#section-input').val();
                var commissionBusinessModel = database.collection('sections').where('id', '==',
                    sectionId);
                await commissionBusinessModel.get().then(async function(snapshots) {
                    if (snapshots.docs.length > 0) {
                        var data = snapshots.docs[0].data();

                        var commissionSetting = data.adminCommision;
                        if (commissionSetting.enable == true) {
                            commisionModel = true;
                        } else {
                            commisionModel = false;
                        }
                        if (providerSpecificCommission == false) {
                            commissionObj = data.adminCommision;
                            if (commissionSetting.type == "percentage") {
                                AdminCommission = commissionSetting.commission + '' + '%';
                            } else {
                                if (currencyAtRight) {
                                    AdminCommission = commissionSetting.commission.toFixed(
                                            decimal_degits) +
                                        currentCurrency;
                                } else {
                                    AdminCommission = currentCurrency + commissionSetting
                                        .commission
                                        .toFixed(
                                            decimal_degits);
                                }
                            }
                        }
                    }
                });
                getSubscriptionPlan();

            }

            setInterval(checkBusinessModel, 3000);
            async function checkBusinessModel() {
                if (commisionModel == false && subscriptionModel == false) {
                    var isSubscribed = "";

                    var url = "{{ route('setSubcriptionFlag') }}";
                    $.ajax({

                        type: 'POST',

                        url: url,

                        data: {

                            email: "{{ Auth::user()->email }}",
                            isSubscribed: isSubscribed
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                        success: function(data) {
                            if (data.access) {
                                window.location = "{{ route('home') }}";
                            }
                        }

                    })
                }

            }
        </script>

    </body>

</html>
