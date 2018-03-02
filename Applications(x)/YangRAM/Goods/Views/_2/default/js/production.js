block([
    '$_/data/',
    '$_/dom/Elements/',


    // '$_/form/Editor/toolbarTypes/complete.bar',
    // '$_/form/Editor/toolbarTypes/normal.bar',
    // '$_/form/Editor/toolbarTypes/simple.bar',
    // '$_/form/Editor/emoticons/default.emt',
    // '$_/form/Editor/',
    // '$_/form/Data.cls',
    // '$_/Time/Picker/'
], function(_) {
    var
        $ = _.dom.select;

    $('select[name=category_id]').on('change', function() {
        var category_id = $(this).val(),
            appsurl = $('.action-container').data('appsUrl'),

            url_brand = appsurl + '/8/categories/' + category_id + '/brands/',
            url_type = appsurl + '/8/categories/' + category_id + '/types/';

        _.data.json(url_brand, function(response) {
            if (response.data) {
                var html = '';
                _.each(response.data, function() {
                    html += '<option value="' + this.id + '">' + this.brand_name + '</option>';
                });
                $('select[name=brand_id]').html(html);
            }

        });

        _.data.json(url_type, function(response) {
            var html = '';
            _.each(response.data, function() {
                html += '<option value="' + this.id + '">' + this.typename + '</option>';
            });
            $('select[name=brand_id]').html(html);
        });
    });

}, true);