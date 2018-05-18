tang.block([
    '$_/data/', '$_/async/',
    '$_/dom/Elements',
    '$_/dom/Template'
], function(_, global, undefined) {
    var $ = _.dom.select,
        $list = $('.mid1920 aside.left nav ul'),
        $item = $('.mid1920 aside.left nav ul li'),
        $mask = $('.left-mask');
    $list.on('mouseout', function(e) {
        var index = $('.mid1920 aside.left nav ul li.on').index($item),
            top = index * 37 + 12;
        $mask.css('top', top);
    });
    $item.on('mouseover', function() {
        var index = $(this).index($item),
            top = (index >= 0 ? index : 0) * 37 + 12;
        $mask.css('top', top);
    });
    var index = $('.mid1920 aside.left nav ul li.on').index($item),
        top = (index >= 0 ? index : 999) * 37 + 12;
    $mask.css('top', top);
}, true);