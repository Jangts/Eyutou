tang.block([
    '$_/data/', '$_/async/',
    '$_/dom/Elements',
    '$_/dom/Template'
], function(_, global, undefined) {
    var url = '/api/cloudtables/?c=MVCRows&m=getrows&args=positions/null/gldpd/0/0';
    _.async.json(url, function(res) {
        var
            $ = _.dom.$,
            view = $('.job-list'),
            source = $('#mytpl').html(),
            temp = new _.dom.Template(source);
        _.each(res.data, function(i, position) {
            temp.complie(position);
        });
        view.append(temp.echo());
    }, function() {});
}, true);