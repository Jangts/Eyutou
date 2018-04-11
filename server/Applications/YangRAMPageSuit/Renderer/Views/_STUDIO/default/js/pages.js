tangram.block([
    '$_/data/',
    '$_/dom/Elements/'
], function(_) {
    var
        $ = _.dom.select;

    if (window.parent) {
        window.alert = window.parent.alert;
    }

    $('select[name=archive]').on('change', function() {
        var archive = $(this).val(),
            id = $('input[name=id]').val(),
            appsurl = $('.action-container').data('appsUrl'),
            url = appsurl + '/pages/archives/' + archive + '/pages/' + id + '/parent-page-paths/';

        _.data.json(url, function(response) {
            if (response.data) {
                var html = '<option value="0">无父级页面</option>';
                _.each(response.data, function() {
                    html += '<option value="' + this.id + '">' + _.util.str.repeat('——', this.level) + ' | ' + this.title + '</option>';
                });
                $('select[name=parent]').html(html);
            }
        });
    });

}, true);