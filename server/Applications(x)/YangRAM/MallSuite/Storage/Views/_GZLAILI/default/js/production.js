tang.block([
    '$_/data/', '$_/async/',
    '$_/dom/Elements'
], function(_) {
    var
        $ = _.dom.$;
    if (window.parent) {
        window.alert = window.parent.alert;
    }
    $('select[name=category_id]').on('change', function() {
        var category_id = $(this).val(),
            appsurl = $('.action-container').data('appsUrl'),

            url_brand = appsurl + '/1008/categories/' + category_id + '/brands/',
            url_type = appsurl + '/1008/categories/' + category_id + '/types/';

        _.async.json(url_brand, function(response) {
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

        console.log(url_type);

        _.async.json(url_type, function(response) {
            console.log(22222222);
            var html = '<option value="0">不设置类型</option>';
            _.each(response.data, function() {
                html += '<option value="' + this.id + '">' + this.typename + '</option>';
            });
            $('select[name=type_id]').html(html);
        });
    });

    console.log(111111);

}, true);