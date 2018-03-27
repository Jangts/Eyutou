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
    if (window.parent) {
        window.alert = window.parent.alert;
    }
    $('select[name=category_id]').on('change', function() {
        var category_id = $(this).val(),
            appsurl = $('.action-container').data('appsUrl'),

            url_brand = appsurl + '/1008/categories/' + category_id + '/brands/',
            url_type = appsurl + '/1008/categories/' + category_id + '/types/';

        _.data.json(url_brand, function(response) {
            if (response.data) {
                var html = '<option value="0">不设置品牌</option>';
                _.each(response.data, function() {
                    html += '<option value="' + this.id + '">' + this.brand_name + '</option>';
                });
                $('select[name=brand_id]').html(html);
            }

        });
    });

    $('select[name=brand_id]').on('change', function() {
        var brand_id = $(this).val(),
            appsurl = $('.action-container').data('appsUrl'),
            url_type = appsurl + '/1008/brands/' + brand_id + '/types/';


        _.data.json(url_type, function(response) {
            var html = '<option value="0">不设置类型</option>';
            _.each(response.data, function() {
                html += '<option value="' + this.id + '">' + this.typename + '</option>';
            });
            $('select[name=type_id]').html(html);
        });
    });

}, true);