<nav class="sidebar-nav">

    <ul id="sidebarnav">

        <li><a class="waves-effect waves-dark" href="{!! url('dashboard') !!}" aria-expanded="false">

                <i class="mdi mdi-home"></i>

                <span class="hide-menu">{{ trans('lang.dashboard') }}</span>

            </a>

        </li>

        <li>

            <a class="waves-effect waves-dark change_subscription d-none" href="{!! route('subscription-plan.show') !!}" aria-expanded="false">

                <i class="mdi mdi-crown"></i>

                <span class="hide-menu">{{ trans('lang.change_subscription') }}</span>

            </a>

        </li>

        <li>

            <a class="waves-effect waves-dark" href="{!! url('my-subscriptions') !!}" aria-expanded="false">

                <i class="mdi mdi-wallet-membership"></i>

                <span class="hide-menu">{{ trans('lang.my_subscriptions') }}</span>

            </a>

        </li>

        <li>

            <a class="waves-effect waves-dark" href="{!! url('services') !!}" aria-expanded="false">

                <i class="mdi mdi-clipboard-text"></i>

                <span class="hide-menu">{{ trans('lang.service_plural') }}</span>

            </a>

        </li>

        <li>

            <a class="waves-effect waves-dark" href="{!! url('workers') !!}" aria-expanded="false">

                <i class="mdi mdi-account-multiple"></i>

                <span class="hide-menu">{{ trans('lang.workers') }}</span>

            </a>

        </li>

        <li>

            <a class="waves-effect waves-dark" href="{!! url('bookings') !!}" aria-expanded="false">

                <i class="mdi mdi-cart"></i>

                <span class="hide-menu">{{ trans('lang.booking_plural') }}</span>

            </a>

        </li>

        <li class="coupon-div d-none">

            <a class="waves-effect waves-dark" href="{!! url('coupons') !!}" aria-expanded="false">

                <i class="mdi mdi-sale"></i>

                <span class="hide-menu">{{ trans('lang.coupons') }}</span>

            </a>

        </li>

        <li>

            <a class="waves-effect waves-dark" href="{!! url('wallettransaction') !!}" aria-expanded="false">

                <i class="mdi mdi-swap-horizontal"></i>

                <span class="hide-menu">{{ trans('lang.wallet_transaction') }}</span>

            </a>

        </li>

        <li class="checkDocumentVerify">

            <a class=" waves-effect waves-dark" href="{!! url('withdraw-method') !!}" aria-expanded="false">

                <i class="fa fa-credit-card "></i>

                <span class="hide-menu">{{ trans('lang.withdrawal_method') }}</span>

            </a>

        </li>

        <li>

            <a class="waves-effect waves-dark" href="{!! url('payouts') !!}" aria-expanded="false">

                <i class="mdi mdi-wallet"></i>

                <span class="hide-menu">{{ trans('lang.payouts') }}</span>

            </a>

        </li>

    </ul>

    <p class="web_version"></p>

</nav>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-app.js"></script>

<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-firestore.js"></script>

<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-storage.js"></script>

<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-auth.js"></script>

<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-database.js"></script>

<script src="{{ asset('js/geofirestore.js') }}"></script>

<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>

<script src="{{ asset('js/crypto-js.js') }}"></script>

<script src="{{ asset('js/jquery.cookie.js') }}"></script>

<script src="{{ asset('js/jquery.validate.js') }}"></script>

<script type="text/javascript">
    var database = firebase.firestore();

    var vendorUserId = "<?php echo $id; ?>";

    var subscriptionModel = false;
    var commisionModel = false;
    var businessModel = database.collection('settings').doc("vendor");

    async function getCommissionModel() {
        await businessModel.get().then(async function(snapshots) {

            var businessModelSettings = snapshots.data();

            if (businessModelSettings.hasOwnProperty('subscription_model') && businessModelSettings.subscription_model == true) {

                subscriptionModel = true;

            }

        });
        await database.collection('users').doc(vendorUserId).get().then(async function(usersnapshots) {
            var userData = usersnapshots.data();
            if (userData.hasOwnProperty('section_id') && userData.section_id != '' && userData.section_id != null) {
                $('.coupon-div').removeClass('d-none');
                await database.collection('sections').where('id', '==', userData.section_id).get().then(async function(snapshots) {

                    if (snapshots.docs && snapshots.docs.length > 0) {

                        var commissionSetting = snapshots.docs[0].data();

                        if (commissionSetting && commissionSetting.adminCommision && commissionSetting.adminCommision.enable) {

                            commisionModel = true;

                        }

                    }

                });
            }

        });

    }

    $(document).ready(function() {
        getCommissionModel().then(function(result) {
            if (subscriptionModel == true || commisionModel == true) {

                $(".change_subscription").removeClass('d-none');

            }
            database.collection('users').doc(vendorUserId).get().then(async function(snapshots) {

                var userData = snapshots.data();

                if (commisionModel || subscriptionModel) {

                    if (userData.hasOwnProperty('subscriptionPlanId') && userData.subscriptionPlanId != null) {

                        var isSubscribed = true;

                    } else {

                        var isSubscribed = false;

                    }

                } else {

                    var isSubscribed = '';

                }

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



                        }

                    }



                })



            });

        })


    });
</script>
