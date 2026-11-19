{{-- #114: plan form helpers shared by create and edit (points, image, reading and checking the form). --}}
<script type="text/javascript">
    // The plan's selling points (plan_points, array of strings, same as the store plans). The array is the truth.
    var planPoints = [];

    function renderPlanPoints() {
        var container = document.getElementById('plan_points_container');
        if (!container) {
            return;
        }
        container.innerHTML = '';
        planPoints.forEach(function (point, index) {
            var row = document.createElement('div');
            row.className = 'form-group d-flex option-row mt-1';
            var input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control plan_point';
            input.maxLength = 160;
            input.value = point;   // a property, never markup
            input.addEventListener('input', function () {
                planPoints[index] = this.value;
            });
            var remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'btn btn-danger ml-2 remove_point';
            remove.innerHTML = '<i class="mdi mdi-delete"></i>';
            remove.addEventListener('click', function () {
                planPoints.splice(index, 1);
                renderPlanPoints();
            });
            row.appendChild(input);
            row.appendChild(remove);
            container.appendChild(row);
        });
    }

    function addPlanPoint() {
        planPoints.push('');
        renderPlanPoints();
    }

    function setPlanPoints(points) {
        planPoints = Array.isArray(points) ? points.map(function (p) { return String(p == null ? '' : p); }) : [];
        renderPlanPoints();
    }

    function cleanedPlanPoints() {
        return planPoints.map(function (p) { return (p || '').trim(); }).filter(function (p) { return p !== ''; });
    }

    // Reads and checks the form. Returns {error} or {fields} (price kept as a string, like the store plans).
    function readPlanForm() {
        var title = String($('.plan_title').val() || '').trim();
        var priceText = String($('.plan_price').val() || '').trim();
        var price = parseFloat(priceText);
        if (title === '') {
            return {error: @json(trans('lang.client_plan_title_error'))};
        }
        if (priceText === '' || !isFinite(priceText) || !(price > 0)) {
            return {error: @json(trans('lang.client_plan_price_error'))};
        }
        return {fields: {
            'title': title,
            'description': String($('.plan_description').val() || '').trim(),
            'price': String(price),
            'expiryDay': $('.plan_period').val() === '365' ? '365' : '30',
            'plan_points': cleanedPlanPoints(),
            'isEnable': $('.plan_enabled').is(':checked')
        }};
    }

    // A plan takes the provider's region and section; without a region it would be invisible to clients and the admin.
    function providerPlace(provider) {
        if (!provider || !provider.regionId) {
            return {error: @json(trans('lang.client_plan_region_error'))};
        }
        return {fields: {'regionId': provider.regionId, 'sectionId': provider.section_id || provider.sectionId || ''}};
    }

    var planPhoto = '';
    var planFileName = '';
    var existingPhoto = '';

    function handlePlanFileSelect(evt) {
        var f = evt.target.files[0];
        if (!f) {
            planPhoto = '';
            planFileName = '';
            return;
        }
        var reader = new FileReader();
        reader.onload = function (e) {
            var ext = f.name.split('.').pop();
            var name = f.name.replace(/C:\\fakepath\\/i, '').split('.')[0].replace(/[^A-Za-z0-9_-]/g, '_');
            planPhoto = e.target.result;
            planFileName = 'provider_plan_' + name + '_' + Number(new Date()) + '.' + ext;
            $('#plan_image').attr('src', planPhoto);
            $('.plan_image_preview').show();
        };
        reader.readAsDataURL(f);
    }

    // Keeps the current image when no new one was chosen.
    async function storePlanImage() {
        if (planPhoto === '') {
            return existingPhoto;
        }
        try {
            var payload = planPhoto.replace(/^data:image\/[a-z+.-]+;base64,/, '');
            var task = await firebase.storage().ref('images').child(planFileName).putString(payload, 'base64', {contentType: 'image/jpg'});
            return await task.ref.getDownloadURL();
        } catch (error) {
            console.error('plan image upload failed', error);
            return existingPhoto;
        }
    }
</script>
