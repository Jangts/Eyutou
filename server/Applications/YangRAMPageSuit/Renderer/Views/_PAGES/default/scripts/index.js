tang.block([
    '$_/data/', '$_/async/',
    '$_/dom/Elements',
    '$_/dom/Template'
], function(_, global, undefined) {
    var $ = _.dom.select,
    url = '/api/cloudtables/?c=MVCRows&m=getrows&args=news/null/gldpd/1/0';
    _.async.json(url, function(res) {
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

    $('#index-video').click(function(){
        var
        that = this,
        URL = this.currentSrc,
        win = window.open(URL, "video", "width=800,height=450,channelmode=yes,location=no,directories=no, status=no,toolbar=no,titlebar=no", false),
        loop = setInterval(function () {
            if (win.closed) {
                clearInterval(loop);
                that.play();
            }
        }, 1000);   
        this.pause();
    });
    
}, true);