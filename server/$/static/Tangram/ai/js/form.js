block([
    '$_/dom/Elements/form.clsx',
    '$_/form/SimpleEditor/',
    '$_/form/PicturesUploader.cls',
    '$_/form/Data.cls',
    '$_/Time/Picker/'
], function(_) {
    var
        $ = _.dom.select;

    if (window.parent) {
        window.alert = window.parent.alert;
    }

    var ueditors = [];
    $('textarea.uedit-text-area').each(function() {
        var id = $(this).data('ueditorId'),
            text = $(this).val(),
            ue = UE.getEditor(id, {
                // UEDITOR_HOME_URL: URL,
                serverUrl: "/applications/uploads/u-editor/",
                autoHeightEnabled: false,
                scaleEnabled: false, //设置不自动调整高度
                autoFloatEnabled: false,
                toolbars: [
                    [
                        'source', '|', 'undo', 'redo', '|',
                        'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
                        'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                        'paragraph', 'fontfamily', 'fontsize', '|',
                        'directionalityltr', 'directionalityrtl', 'indent', '|',
                        'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                        'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
                        'simpleupload', 'insertimage', 'emotion', 'scrawl', 'insertvideo', 'attachment', 'insertframe', 'insertcode', '|',
                        'horizontal', 'date', 'time', 'spechars', '|',
                        'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
                        'print', 'preview', 'searchreplace', 'drafts'
                    ]
                ]
            });
        ue.ready(function() {
            ue.setContent(text, false);
        });
        ueditors.push({
            textarea: this,
            editor: ue
        });
    });

    var editors = _.form.careatEditors('textarea.edit-text-area', {
            uploader: {
                maxsize: 1024 * 1024 * 20,
                sfixs: false,
                transfer: function(files, inserter) {
                    var total = files.length;
                    var loaded = 0;
                    var failed = 0;
                    var images = [];

                    function listen() {
                        if (loaded + failed == total) {
                            inserter(images, failed);
                        }
                    };
                    _.data.Uploader.transfer(files, {
                        url: '/applications/uploads/files/?returndetails=json',
                        handlers: {
                            done: function(response) {
                                // console.log(response);
                                var response = _.data.decodeJSON(response.responseText) || {
                                    data: 500
                                };
                                // console.log(response);
                                if ((data = response.data) && data.successed) {
                                    _.each(data.successed.myfile, function() {
                                        loaded++;
                                        images.push(this.url);
                                    });
                                    _.each(data.failed.myfile, function() {
                                        failed++;
                                    });
                                } else {
                                    alert('上传失败');
                                }
                                listen();
                            },
                            fail: function(response) {
                                alert('上传失败');
                            },
                        }
                    });
                }
            }
        }),
        timepicker = new _.Time.Picker('timepicker');



    $('.input-section .datetime-item,.input-section .fulldate-item,.input-section .timeofday-item').click(function() {
        var
            $this = $(this),
            $timepicker = $('.action-timepicker'),
            value = $this.val();
        switch ($this.attr('class')) {
            case 'datetime-item':
                timepicker.launch(value, 'datetime', function(value) {
                    $this.val(value);
                    $timepicker.removeClass('on');
                }, function(value) {
                    $timepicker.removeClass('on');
                });
                break;

            case 'fulldate-item':
                timepicker.launch(value, 'fulldate', function(value) {
                    $this.val(value);
                    $timepicker.removeClass('on');

                }, function(value) {
                    $timepicker.removeClass('on');

                });
                break;

            case 'timeofday-item':
                timepicker.launch(value, 'timeofday', function(value) {
                    $this.val(value);
                    $timepicker.removeClass('on');
                }, function(value) {
                    $timepicker.removeClass('on');
                });
                break;
        }
        $timepicker.addClass('on');
    });

    var imageUploaders = _.form.careatPicturesUploaders('.figure-items .pic-uploader', {
            url: '/applications/uploads/files/?returndetails=json',
            // url: '/applications/uploads/attachments/?returndetails=json'
        }),
        videoUploaders = _.form.careatPicturesUploaders('.video-item .pic-uploader', {
            url: '/applications/uploads/files/?returndetails=json',
            type: 'video'
        });

    $('.action-buttons button').click(function() {
        var order = $(this).data('order'),
            form = $('form[name=' + $(this).data('orderForm') + ']')[0];
        switch (order) {
            case 'reset':
                if (form) {
                    form.reset();
                    // $(form).find('.img-show').each(function() {
                    //     this.src = $(this).data('resetSrc');
                    // });
                    _.each(imageUploaders, function() {
                        if (this.inForm(form)) {
                            this.resetValue();
                        }
                    });
                    _.each(videoUploaders, function() {
                        if (this.inForm(form)) {
                            this.resetValue();
                        }
                    });
                    _.each(editors, function() {
                        if (this.inForm(form)) {
                            this.resetValue();
                        }
                    });
                }
                break;

            case 'submit':
                if (form) {
                    _.each(editors, function() {
                        if (this.inForm(form)) {
                            this.getValue();
                        }
                    });
                    _.each(ueditors, function() {
                        if (_.dom.contain(form, this.textarea)) {
                            this.textarea.value = this.editor.getContent();
                        }
                    });
                    var formdata = new _.form.Data(form),
                        action = $(this).data('submitHref'),
                        href = $(this).data('successHref');
                    if (action) {
                        formdata.submit({
                            method: 'POST',
                            action: action,
                            correctData: {
                                http_method: $(form).attr('method') || 'POST'
                            },
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
                    }

                }
                break;

            case 'delete':
                var action = $(this).data('submitHref'),
                    href = $(this).data('successHref');
                if (action) {
                    _.data.AJAX(action, {
                        method: 'POST',
                        data: {
                            http_method: 'DELETE',
                        },
                        success: function(responseText) {
                            var response = _.data.decodeJSON(responseText) || {
                                'code': 500
                            };
                            if (response['code'] == 1212) {
                                alert('删除成功，将返回列表页');
                                href && (window.location.href = href) && window.parent && (window.parent.location.hash = href);
                            } else if (response['code'] == 1204) {
                                alert('移除成功，您仍可以在回收站找到它；将返回列表页');
                                href && (window.location.href = href) && window.parent && (window.parent.location.hash = href);
                            } else if (response['code'] == 404) {
                                alert('删除失败，资源已经被删除，将返回列表页');
                                href && (window.location.href = href) && window.parent && (window.parent.location.hash = href);
                            } else if (response['code'] == 1411.4) {
                                alert('删除失败，请检查您的权限');
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
                                alert('删除失败，资源已经被删除，将返回列表页');
                                href && (window.location.href = href) && window.parent && (window.parent.location.hash = href);
                            } else {
                                alert('服务器错误');
                            }
                        }
                    });
                }
                break;

            case 'anchor':
                var href = $(this).data('successHref');
                if (href) {
                    href && (window.location.href = href) && window.parent && (window.parent.location.hash = href);
                }

        }
    });
}, true);