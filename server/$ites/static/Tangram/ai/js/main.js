block([
    '$_/dom/Elements/',
    '$_/form/Data.cls'
], function(_) {
    var
        $ = _.dom.select,
        $main = $('.main-section, .side-section'),
        workspace = document.getElementById('maincontainer'),
        height = _.dom.getHeight(document);

    var hash = location.hash;
    if (location.hash) {
        var arr = location.hash.split(/(\#|\?)/g);

        arr[0] = arr[1] = '';
        subarr = arr[2].split(/\/+/g);
        if (subarr.length > 4) {
            // appid = subarr[2],
            // placeholder = subarr[3],
            var pagegroup = subarr[2] + '/' + subarr[3];
            var $a = $('.side-menu-item a[data-page-group=' + pagegroup + ']');
            if ($a.length) {
                $('.side-menu-item.curr, .side-menu.curr').removeClass('curr');
                $a.closet('li').addClass('curr').closet('div').addClass('curr');
            }
        }
        workspace.src = arr.join('');
    }

    $main.height(height - 60);

    $(window).on('resize', function() {
        height = _.dom.getHeight(document);
        $main.height(height - 60);
    });

    $('.side-menu-item a').click(function() {
        $('.side-menu-item.curr, .side-menu.curr').removeClass('curr');
        $(this).closet('li').addClass('curr').closet('div').addClass('curr');
        location.hash = $(this).attr('href');
    });

    $('.top-logo a').click(function() {
        $('.side-menu-item.curr, .side-menu.curr').removeClass('curr');
        location.hash = '';
    });

    $('.top-options a').click(function() {
        var href;
        if (href = $(this).data('submitHref')) {
            _.data.AJAX(href, {
                success: function(responseText) {
                    var response = _.data.decodeJSON(responseText) || {};
                    console.log(response);

                    if (response['code'] == 203) {
                        alert('成功登出，将返回登录页');
                        location.hash = '';
                        window.location.reload();
                    } else {
                        alert('未知错误');
                    }
                },
                fail: function() {
                    alert('服务器错误');
                }
            });
        } else if (href = $(this).attr('href')) {
            $('.side-menu-item.curr, .side-menu.curr').removeClass('curr');
            location.hash = href;
        }
    });
}, true);