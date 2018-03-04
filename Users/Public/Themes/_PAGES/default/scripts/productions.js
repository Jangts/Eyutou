block([
    '$_/data/',
    '$_/dom/Elements/',
    '$_/dom/Template.cls'
], function(_, global, undefined) {
    var $ = _.dom.select,
        brand_id = 1,
        type_id = 1,
        $brands = $('.mid1920 aside.left nav ul li'),
        $types = $('.products-categories a'),
        $list = $('#products-list'),
        $pop = $('#product-detail'),
        $img = $('#product-detail .product-image>img'),
        $art = $('#product-detail .product-info');

    function render(data, hash) {
        var posi = 0,
            html = '';
        _.each(data, function(i, item) {
            posi = i % 3;
            html += '<li data-item-id="' + item.id + '" data-posi="' + posi + '" class="product-item"><img src="' + item.image + '"></li>';
        });
        location.hash = hash;
        $list.html(html);
    }

    $brands.click(function() {
        var $this = $(this),
            id = $this.data('brand-id'),
            url = '/applications/8/brand/' + id + '/type/' + type_id + '/productions/';
        _.data.json(url, function(res) {
            render(res.data, 'brand=' + id + '&type=' + type_id);
            brand_id = id;
            $brands.removeClass('on');
            $this.addClass('on');
        });
    });

    $types.click(function() {
        var $this = $(this),
            id = $this.data('type-id'),
            url = '/applications/8/brand/' + brand_id + '/type/' + id + '/productions/';
        _.data.json(url, function(res) {
            render(res.data, 'brand=' + brand_id + '&type=' + id);
            type_id = id;
            $types.removeClass('on');
            $this.addClass('on');
        });
    });

    $list.on('click', ".product-item", null, function() {
        var id = $(this).data('item-id'),
            url = '/applications/8/productions/' + id;
        _.data.json(url, function(res) {
            $img.attr('src', res.data.image);
            $art.html('')
                .append('<h1>' + res.data.name + '</h1>')
                .append('<p class="indent">' + res.data.detail + '</p><p>&nbsp;</p>')
                .append('<p>产品名称：' + res.data.name + '</p>')
                .append('<p>产品类型：' + res.data.type.typename + '</p>')
                .append('<p>规格：540g×10罐/箱</p>');
            $pop.addClass('on');
        });
    });
    $pop.click(function(e) {
        if (e.target === this) {
            $pop.removeClass('on');
        }
    });
}, true);