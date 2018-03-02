block([
    '$_/dom/Elements/form.clsx',
    '$_/form/Data.cls'
], function(_) {
    var
        $ = _.dom.select;

    $('a[data-onclick]').click(function() {
        var order = $(this).data('onclick');
        switch (order) {
            case 'delete':
                if (action = $(this).data('submitHref')) {
                    _.data.AJAX(action, {
                        method: 'POST',
                        data: {
                            http_method: 'DELETE',
                        },
                        success: function(responseText) {
                            var response = _.data.decodeJSON(responseText) || {};
                            if (response['code'] == 1212) {
                                alert('删除成功，将刷新列表页');
                                window.location.reload();
                            } else if (response['code'] == 1204) {
                                alert('移除成功，您仍可以在回收站找到它；将刷新列表页');
                                window.location.reload();
                            } else if (response['code'] == 404) {
                                alert('删除失败，资源已经被删除，将刷新列表页');
                                window.location.reload();
                            } else if (response['code'] == 1412) {
                                alert('删除失败');
                            } else if (response['code'] == 1404) {
                                alert('移除失败');
                            } else {
                                alert('未知错误');
                            }
                        },
                        fail: function() {
                            if (response['code'] == 404) {
                                alert('删除失败');
                            } else {
                                alert('服务器错误');
                            }
                        }
                    });
                }
        }
    });
}, true);