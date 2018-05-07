tangram.block([
    '$_/obj/', '$_/data/', '$_/async/',
    '$_/dom/Elements',
    '$_/dom/Template'
], function(_, global, undefined) {
    var isnum = _.util.bool.isNumeric,
        $ = _.dom.select,
        pre_gape = 9,
        brand_id = 1,
        type_id = 1,
        $brands = $('.mid1920 aside.left nav ul li'),
        $brandname = $('#products-brand'),
        $brandlogo = $('#brand-logo'),
        $typelist = $('nav.products-categories'),
        $list = $('#products-list section'),
        $pagelist = $('.pages-list'),
        $pop = $('#product-detail'),
        $img = $('#product-detail .product-image>img'),
        $art = $('#product-detail .product-info'),
        $buy = $('#product-detail .go2buy');

    var page = 1,
        render = function (data, hash) {
            var posi = 0,
                html = '';
            var total = data.length,
                pagenum = Math.ceil(total / pre_gape) || 1;
            if (page < 1) page = 1;
            if (page > pagenum) page = pagenum;
            storage.types[type_id].page = page;
            var start = (page - 1) * pre_gape,
                end = page * pre_gape;
            if (end >= total) end = total;
            if (end >= total) end = total;

            for (i = start; i < end; i++) {
                var item = data[i];
                storage.products[item.id] = item;
                posi = i % 3;
                src = item.image + '?sizes=196'
                html += '<li data-item-id="' + item.id + '" data-posi="' + posi + '" class="product-item"><figure><img src="' + src + '"></figure><p>' + item.name + '</p></li>';
            }
            location.hash = hash;
            $list.html(html);
            
            var pagehtml = '<li class="page-list-item"><a href="javascript:;" data-page="1">首页</a></li>';
            if (page > 1) {
                prevpage = page - 1;
                pagehtml += '<li class="page-list-item"><a href="javascript:;" data-page="' + prevpage + '">上一页</a></li>';
            }
            for (let index = 1; index <= pagenum; index++) {
                if(index===page){
                    pagehtml += '<li class="page-list-item on"><a href="javascript:;">' + index + '</a></li>';
                }else{
                    pagehtml += '<li class="page-list-item"><a href="javascript:;" data-page="' + index + '">' + index + '</a></li>';
                }
            }
            if (page < pagenum) {
                nextpage = page + 1;
                pagehtml += '<li class="page-list-item"><a href="javascript:;" data-page="' + nextpage + '">下一页</a></li>';
            } 
            pagehtml += '<li class="page-list-item"><a href="javascript:;" data-page="' + pagenum+'">末页</a></li>';
            $pagelist.html(pagehtml);
        },
        brandClick = function (id, $this, callback) {
            if (storage.brands[id] === void 0) {
                var url1 = '/applications/1008/brands/' + id,
                    url2 = '/applications/1008/brands/' + id + '/types/';
                _.async.json(url1, function (res) {
                    if (res.data && res.data.brand_name) {
                        $brandname.html(res.data.brand_name);
                    };
                    if (res.data && res.data.brand_logo) {
                        $brandlogo[0].src = res.data.brand_logo;
                    };
                    storage.brands[id].info = res.data;
                    $brands.removeClass('on');
                    $this.addClass('on');
                });
                _.async.json(url2, function (res) {
                    var types = [];
                    if (res.data) {
                        _.each(res.data, function (index, type) {
                            types.push('<a class="" data-type-id="' + type.id + '" href="javascript:void(0);">' + type.typename + '</a>');
                        });
                        storage.brands[id].types = res.data || [];
                    }
                    $typelist.html(types.join('|'));
                    brand_id = id;
                    page = 0;
                    // render([], 'brand=' + brand_id);
                    callback();
                });
                storage.brands[id] = {
                    info: void 0,
                    types: []
                };
            } else {
                // console.log(storage.brands[id]);
                if (storage.brands[id].info && storage.brands[id].info.brand_name) {
                    $brandname.html(storage.brands[id].info.brand_name);
                };
                if (storage.brands[id].info && storage.brands[id].info.brand_logo) {
                    $brandlogo[0].src = storage.brands[id].info.brand_logo;
                };
                $brands.removeClass('on');
                $this.addClass('on');
                var types = [];
                _.each(storage.brands[id].types, function (index, type) {
                    types.push('<a class="" data-type-id="' + type.id + '" href="javascript:void(0);">' + type.typename + '</a>');
                });
                $typelist.html(types.join('|'));
                brand_id = id;
                page = 1;
                // render([], 'brand=' + brand_id);
                callback();
            }
        },
        storage = {
            brands: {},
            types: {},
            products: {}
        },
        typeClick = function (id, $this) {
            if (storage.types[id] === void 0) {
                var url = '/applications/1008/brands/' + brand_id + '/types/' + id + '/productions/?sortby=grade_reverse';
                _.async.json(url, function (res) {
                    storage.types[id] = {
                        total: res.data.length,
                        page: 1,
                        pros: res.data
                    };
                    type_id = id;
                    page = 1;
                    // console.log(res.data);
                    render(res.data, 'brand=' + brand_id + '&type=' + id);
                    $('.products-categories a').removeClass('on');
                    $this.addClass('on');
                });
            } else {
                type_id = id;
                page = storage.types[id].page;
                render(storage.types[id].pros, 'brand=' + brand_id + '&type=' + id);
                $('.products-categories a').removeClass('on');
                $this.addClass('on');
            }
        },
        loadpros = function () {
            var id = $(this).data('item-id');

            $img.attr('src', storage.products[id].image);
            $art.html('')
                .append('<h1>' + storage.products[id].name + '</h1>')
                .append('<p class="indent">' + storage.products[id].detail + '</p><p>&nbsp;</p>')
                .append('<p>产品名称：' + storage.products[id].name + '</p>')
                .append('<p>产品类型：' + storage.products[id].type.typename + '</p>')
                .append('<p>规格：' + storage.products[id].standard + '</p>');
            if (storage.products[id].link) {
                $buy.attr('href', storage.products[id].link);
            } else {
                $buy.attr('href', 'javascript:void(0);');
            }
            $pop.addClass('on');
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

    $list.on('click', ".product-item", null, loadpros);
    $pop.click(function(e) {
        if (e.target === this) {
            $pop.removeClass('on');
        }
    });

    $pagelist.on('click', "li a[data-page]", null, function () {
        page = $(this).data('page');
        render(storage.types[type_id].pros, 'brand=' + brand_id + '&type=' + type_id);
    });

    if (location.hash) {
        var hash = _.obj.decodeQueryString(location.hash);
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