{{-- #114: helpers shared by the client subscription screens. Only declarations: each view calls them. --}}
<script type="text/javascript">
    var database = firebase.firestore();
    var providerId = @json($id);
    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_degits = 0;
    var clientNames = {};

    // Plan titles and client names come from Firestore: always escaped (quotes too, so it is safe in attributes).
    function spdEsc(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    async function spdLoadProvider() {
        var doc = await database.collection('users').doc(providerId).get();
        return doc.exists ? doc.data() : null;
    }

    // Prices are in the provider's region currency (regions/{id}.currencyId), else the active currency.
    async function spdLoadCurrency(provider) {
        var currency = null;
        try {
            if (provider && provider.regionId) {
                var region = await database.collection('regions').doc(provider.regionId).get();
                if (region.exists && region.data().currencyId) {
                    var c = await database.collection('currencies').doc(region.data().currencyId).get();
                    if (c.exists) {
                        currency = c.data();
                    }
                }
            }
            if (!currency) {
                var active = await database.collection('currencies').where('isActive', '==', true).limit(1).get();
                if (active.docs.length > 0) {
                    currency = active.docs[0].data();
                }
            }
        } catch (e) {
            console.error(e);
        }
        if (currency) {
            currentCurrency = currency.symbol || '';
            currencyAtRight = currency.symbolAtRight === true;
            decimal_degits = parseInt(currency.decimal_degits, 10) || 0;
        }
    }

    function formatPrice(value) {
        var amount = (parseFloat(value || 0) || 0).toFixed(decimal_degits);
        return currencyAtRight ? amount + '' + currentCurrency : currentCurrency + '' + amount;
    }

    function spdPeriod(expiryDay) {
        return String(expiryDay) === '365' ? @json(trans('lang.client_plan_period_annual')) : @json(trans('lang.client_plan_period_monthly'));
    }

    function toDate(value) {
        if (!value) {
            return null;
        }
        if (typeof value.toDate === 'function') {
            return value.toDate();
        }
        var parsed = new Date(value);
        return isNaN(parsed.getTime()) ? null : parsed;
    }

    function toTime(value) {
        var date = toDate(value);
        return date == null ? 0 : date.getTime();
    }

    function formatDate(value) {
        var date = toDate(value);
        if (date == null) {
            return '';
        }
        return ('0' + date.getDate()).slice(-2) + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + date.getFullYear();
    }

    // Live = not cancelled and not past its expiry date (no job updates the stored status; the date is the truth).
    function isLiveSubscription(subscription) {
        if ((subscription.status || 'active') === 'cancelled') {
            return false;
        }
        var expiry = toDate(subscription.expiryDate);
        return expiry == null || expiry.getTime() >= Date.now();
    }

    function statusBadge(subscription) {
        if ((subscription.status || 'active') === 'cancelled') {
            return '<span class="badge badge-danger">' + spdEsc(@json(trans('lang.client_subscription_cancelled'))) + '</span>';
        }
        if (!isLiveSubscription(subscription) || subscription.status === 'expired') {
            return '<span class="badge badge-warning">' + spdEsc(@json(trans('lang.client_subscription_expired'))) + '</span>';
        }
        return '<span class="badge badge-success">' + spdEsc(@json(trans('lang.client_subscription_active'))) + '</span>';
    }

    async function spdLoadClientNames(ids) {
        var unique = ids.filter(function (id, i) { return id && ids.indexOf(id) === i && clientNames[id] === undefined; });
        await Promise.all(unique.map(async function (id) {
            var doc = await database.collection('users').doc(id).get();
            clientNames[id] = doc.exists ? ((doc.data().firstName || '') + ' ' + (doc.data().lastName || '')).trim() : '';
        }));
    }

    function showError(message) {
        jQuery("#data-table_processing").hide();
        $(".error_top").show().html('').append($('<p>').text(message));
        window.scrollTo(0, 0);
    }
</script>
