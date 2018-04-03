block([
    '$_/data/',
    '$_/dom/Elements/',
    '$_/dom/Template.cls'
], function(_, global, undefined) {
    var url = '/api/cloudtables/?c=MVCRows&m=getrows&args=news/null/gldpd/1/0';
    _.data.json(url, function(res) {
            var
                $ = _.dom.select,
                view = $('.index-news-arti'),
                source = view.html(),
                data = {
                    title: res.data[0].TITLE.substr(0, 12),
                    year: res.data[0].PUBTIME.substr(0, 4),
                    date: res.data[0].PUBTIME.substr(5, 5).replace('-', '.'),
                    desc: res.data[0].DESCRIPTION.substr(0, 66),
                    url: '/news/detail/' + res.data[0].ID
                },
                temp = new _.dom.Template(source, data);
            view.html(temp.echo());
        },
        function() {

        });
}, true);