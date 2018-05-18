tang.auto([
    '$+/BaiduMap/style.css',
    '$+/BaiduMap/',
    '$_/dom/Elements'
], function(_, global, undefined) {
    var $ = _.dom.$;

    var more = JSON.parse($('#more').html()) || {
        title: "广州来利洪饼业有限公司",
        desc: "广州市白云区人和镇秀盛路三盛工业区自编1号",
        location: [113.330953, 23.316237]
    };

    $('.contact-info>h4').html(more.title);

    dvcode = new _.BaiduMap('map', {
        title: more.title,
        desc: more.desc,
        coords: more.location
    });
});