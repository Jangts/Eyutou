block([
    '$_/data/',
    '$_/dom/Elements/',
    '$_/dom/Template.cls'
], function(_, global, undefined) {
    var isnum = _.util.bool.isNumeric,
        $ = _.dom.select,
        brand_id = 1,
        type_id = 1,
        $brands = $('.mid1920 aside.left nav ul li'),
        $brandname = $('#products-brand'),
        $brandlogo = $('#brand-logo'),
        $typelist = $('nav.products-categories'),
        $list = $('#products-list section'),
        $pop = $('#product-detail'),
        $img = $('#product-detail .product-image>img'),
        $art = $('#product-detail .product-info'),
        $buy = $('#product-detail .go2buy');

    var render = function(data, hash) {
            var posi = 0,
                html = '';
            _.each(data, function(i, item) {
                posi = i % 3;
                html += '<li data-item-id="' + item.id + '" data-posi="' + posi + '" class="product-item"><img src="' + item.image + '"></li>';
            });
            location.hash = hash;
            $list.html(html);
        },
        brandClick = function(id, $this, callback) {
            var
                url1 = '/applications/1008/brands/' + id;
            url2 = '/applications/1008/brands/' + id + '/types/';
            _.data.json(url1, function(res) {
                if (res.data && res.data.brand_name) {
                    $brandname.html(res.data.brand_name);
                };
                if (res.data && res.data.brand_logo) {
                    $brandlogo[0].src = res.data.brand_logo;
                };
                $brands.removeClass('on');
                $this.addClass('on');
            });
            _.data.json(url2, function(res) {
                var types = [];
                if (res.data) {
                    _.each(res.data, function(index, type) {
                        types.push('<a class="" data-type-id="' + type.id + '" href="javascript:void(0);">' + type.typename + '</a>');
                    });
                }
                $typelist.html(types.join('|'));
                brand_id = id;
                render([], 'brand=' + brand_id);
                callback();
            });
        },
        typeClick = function(id, $this) {
            var url = '/applications/1008/brands/' + brand_id + '/types/' + id + '/productions/';
            _.data.json(url, function(res) {
                render(res.data, 'brand=' + brand_id + '&type=' + id);
                type_id = id;
                $('.products-categories a').removeClass('on');
                $this.addClass('on');
            });
        }

    $brands.click(function() {
        var $this = $(this),
            id = $this.data('brand-id'),
            type;
        brandClick(id, $this, function() {
            if (type = $('.products-categories a')[0]) {
                $(type).click();
            }
        });
    });

    $typelist.on('click', "a[data-type-id]", null, function() {
        var $this = $(this),
            id = $this.data('type-id');
        typeClick(id, $this);
    });

    $list.on('click', ".product-item", null, function() {
        var id = $(this).data('item-id'),
            url = '/applications/1008/productions/' + id;
        _.data.json(url, function(res) {
            $img.attr('src', res.data.image);
            $art.html('')
                .append('<h1>' + res.data.name + '</h1>')
                .append('<p class="indent">' + res.data.detail + '</p><p>&nbsp;</p>')
                .append('<p>产品名称：' + res.data.name + '</p>')
                .append('<p>产品类型：' + res.data.type.typename + '</p>')
                .append('<p>规格：' + res.data.standard + '</p>');
            if (res.data.link) {
                $buy.attr('href', res.data.link);
            } else {
                $buy.attr('href', 'javascript:void(0);');
            }
            $pop.addClass('on');
        });
    });
    $pop.click(function(e) {
        if (e.target === this) {
            $pop.removeClass('on');
        }
    });

    if (location.hash) {
        var hash = _.data.decodeQueryString(location.hash);
        if (isnum(hash.brand)) {
            var $_brands = $('.mid1920 aside.left nav ul li[data-brand-id="' + hash.brand + '"]');
            if ($_brands.length) {
                if (isnum(hash.type)) {
                    brandClick(hash.brand, $_brands, function() {
                        $_brands.trigger('mouseover');
                        var $_types = $('.products-categories a[data-type-id="' + hash.type + '"]')
                        if ($_types.length) {
                            $_types.click();
                        } else if (type = $('.products-categories a')[0]) {
                            $(type).click();
                        }
                    });
                } else {
                    $_brands.click().trigger('mouseover');;
                }
                return;
            }
        }
    }
    if ($brands.length) {
        $($brands[0]).click().trigger('mouseover');
    }
}, true);