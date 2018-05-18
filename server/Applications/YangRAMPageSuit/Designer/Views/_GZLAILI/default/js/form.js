tang.block([
        '$_/dom/Elements',
        '$_/form/SimpleEditor/',
        '$_/form/PicturesUploader',
        '$_/form/Data',
        '$_/app/TimePicker/'
    ], function(_) {
        var
            $ = _.dom.$;

        if (window.parent) {
            window.alert = window.parent.alert;
        }

        if (location.search) {
            $_GET = _.obj.decodeQueryString(location.search);
            $type = $_GET['tabid'];
        } else {
            $type = 'niml';
        }

        switch ($type) {
            case 'css':
                $option = {
                    mode: "text/css",
                    lineNumbers: true
                };
                break;

            case 'js':
                $option = {
                    mode: "text/javascript",
                    lineNumbers: true
                };
                break;

            default:
                $option = {
                    lineNumbers: true
                };
        }

        var textarea = $('[name=content]').get(0),
            editor = CodeMirror.fromTextArea(textarea, $option);

        $('.action-buttons button').click(function() {
            var order = $(this).data('order'),
                form = $('form[name=' + $(this).data('orderForm') + ']')[0];
            switch (order) {
                case 'submit':
                    if (form) {
                        var data = editor.getValue(),
                            action = $(this).data('submitHref'),
                            href = $(this).data('successHref');
                        _.async.ajax({
                            url: action,
                            data: data,
                            method: 'POST',
                            mime: 'text/x-niml-template',
                            success: function(responseText) {
                                var response = _.data.decodeJSON(responseText) || {
                                    'code': 500
                                };
                                if (response['code'] == 200) {
                                    alert('更新中止，提交的数据并未被改变');
                                } else if (response['code'] == 1201) {
                                    alert('创建成功，将返回列表页');
                                    href && (window.location.href = href) && window.parent && (window.parent.location.hash = href);
                                } else if (response['code'] == 1203) {
                                    if (href) {
                                        alert('更新成功，将返回列表页');
                                        window.location.href = href;
                                        if (window.parent) {
                                            window.parent.location.hash = href;
                                        }
                                    } else {
                                        alert('更新成功');
                                    }
                                } else if (response['code'] == 404) {
                                    alert('更新失败，资源不存在，将返回列表页');
                                    href && (window.location.href = href) && window.parent && (window.parent.location.hash = href);
                                } else if (response['code'] == 1401) {
                                    alert('创建失败，请检查您的表单');
                                } else if (response['code'] == 1403) {
                                    alert('更新失败，请检查您的表单');
                                } else if (response['code'] == 1411.1) {
                                    alert('创建失败，请检查您的权限');
                                } else if (response['code'] == 1411.2) {
                                    alert('读取失败，请检查您的权限');
                                } else if (response['code'] == 1411.3) {
                                    alert('更新失败，请检查您的权限');
                                } else if (response['code'] == 1411.4) {
                                    alert('删除失败，请检查您的权限');
                                } else if (response['code'] == 1415) {
                                    alert('更新失败，请检查您的表单');
                                } else {
                                    alert('提交失败');
                                }
                            },
                            fail: function() {
                                alert('服务器错误');
                            }
                        });
                        console.log(data);
                    }
                    break;

                case 'anchor':
                    var href = $(this).data('successHref');
                    if (href) {
                        href && (window.location.href = href) && window.parent && (window.parent.location.hash = href);
                    }

            }
        });
    },
    true);