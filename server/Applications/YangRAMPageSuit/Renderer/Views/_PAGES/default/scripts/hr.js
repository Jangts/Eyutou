block([
    '$_/data/',
    '$_/dom/Elements/',
    '$_/dom/Template.cls'
], function(_, global, undefined) {
    var url = '/api/cloudtables/?c=MVCRows&m=getrows&args=positions/null/gldpd/0/0';
    _.data.json(url, function(res) {
        var
            $ = _.dom.select,
            view = $('.job-list'),
            source = $('#mytpl').html(),
            temp = new _.dom.Template(source);
        _.each(res.data, function(i, position) {
            temp.complie(position);
        });
        view.append(temp.echo());
    }, function() {});
}, true);