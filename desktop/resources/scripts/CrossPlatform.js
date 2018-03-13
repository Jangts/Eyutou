System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        Logger = System.Logger,
        _ = System.Pandora;

    global.FormData = _.form.Data;

    /* Public Variables of System */
    var ID = 'UOI',
        Name = 'Uniform Operator Interface Desktop Edition',
        Theme = 'default',
        Author = 'TANGRAM I4s DEVELOP CENTER',
        Version = '2.8.0.0';

    /* Request Methods of System */
    var request = remote.getGlobal('sharedObject').request,
        Response = declare({
            _init(response) {
                this.statusCode = response.statusCode;
                this.statusText = response.statusMessage;
                this.responseHeaders = {};
                for (var i = 0; i < response.rawHeaders.length; i++) {
                    this.responseHeaders[response.rawHeaders[i]] = response.rawHeaders[++i];
                }
            },
            getAllResponseHeaders() {
                var result = this.responseHeaders ? '' : null;
                for (var key in this.responseHeaders) {
                    result += key + ' : ' + this.responseHeaders[key] + ' \n';
                }
                return result;
            },
            getResponseHeader(key) {
                return this.responseHeaders ? this.responseHeaders[key] : null;
            },
        }),
        RequestFailed = function(code, data, callback) {
            //console.log(code);
            switch (code) {
                case '700.5':
                    if (System.State) {
                        System.Locker.launch();
                    } else {
                        location.reload();
                    }
                    break;
                case '700.6':
                case '700.7':
                    location.reload();
                    break;
                case '704.0':
                case '705.0':
                case '708.0':
                case '709.0':
                    alert(this.getResponseHeader('NI-Response-Text') + ' [' + code + ']');
                    break;
                case '708.4':
                    alert(Runtime.locales.COMMON.OF_NOT_FOUND);
                    break;
                default:
                    callback.call(this, data);
            }
        },
        Uploader = {
            appid: 'UPLOADER',
            Status: true,
            name: 'Uploader',
            title: 'Uploader',
            Author: System.author,
            Version: System.version,
            timeStamp: new Date().getTime(),
            MaxSize: System.UploadMaxSize,
            extends: _.widgets.Component.prototype._x
        };

    /* extends Variables Methods For YangRAM */
    _.extend(System, true, {
        ID: ID,
        Name: Name,
        Theme: Theme,
        Author: Author,
        Version: Version,
        Uploader: Uploader,
        JSON(url, doneCallback, failCallback) {
            request({
                    url: url
                },
                function(error, response, body) {
                    if (!error) {
                        var that = new Response(response);
                        if (that.statusCode == 200) {
                            doneCallback(JSON.parse(body));
                        } else {
                            failCallback.call(that, body);
                        }
                    } else {
                        failCallback.call({}, error.message);
                    }
                }
            );
            return this;
        },
        GET(options) {
            options = options || {};
            var url = options.url || System.YangRAM.URI;
            var fail = _.util.bool.isFn(options.fail) ? options.fail : function(data) {
                global.console.log(url, data, this);
            };
            var done = function(data) {
                //global.console.log(this);
                var code;
                if (code = this.getResponseHeader('Y-Response-Code')) {
                    if ((code >= 200 && code < 300) || code == 304) {
                        _.util.bool.isFn(options.done) && options.done.call(this, System.TrimHTML(data));
                    } else {
                        RequestFailed.call(this, code, data, fail);
                    }
                } else {
                    _.util.bool.isFn(options.done) && options.done.call(this, System.TrimHTML(data));
                }
            };

            if (_.util.bool.isStr(options.data)) {
                if (url.indexof('?')) {
                    url = url + "&" + options.data;
                } else {
                    url = url + "?" + options.data;
                }
            } else if (_.util.bool.isObj(options.data)) {
                if (options.data instanceof FormData) {
                    if (url.indexof('?')) {
                        url = url + "&" + options.data.encodeQueryString();
                    } else {
                        url = url + "?" + options.data.encodeQueryString();
                    }
                } else {
                    if (url.indexof('?')) {
                        url = url + "&" + encodeQueryString(options.data);
                    } else {
                        url = url + "?" + encodeQueryString(options.data);
                    }
                }
            }
            request({
                    url: url
                },
                function(error, response, body) {
                    if (!error) {
                        var that = new Response(response);
                        if (that.statusCode == 200) {
                            done.call(that, body);
                        } else {
                            fail.call(that, body);
                        }
                        if (_.util.bool.isFn(options.always)) {
                            options.always.call(that, body);
                        }
                    } else {
                        fail.call({}, error.message);
                    }
                }
            );
            return this;
        },
        POST(options) {
            options = options || {};
            var url = options.url || System.YangRAM.URI;
            var fail = _.util.bool.isFn(options.fail) ? options.fail : function(data) {
                global.console.log(url, data, this);
            };
            var done = function(data) {
                var code;
                if (code = this.getResponseHeader('Y-Response-Code')) {
                    if ((code >= 200 && code < 300) || code == 304) {
                        _.util.bool.isFn(options.done) && options.done.call(this, System.TrimHTML(data));
                    } else {
                        RequestFailed.call(this, code, data, fail);
                    }
                } else {
                    _.util.bool.isFn(options.done) && options.done.call(this, System.TrimHTML(data));
                }
            };

            var data;
            if (_.util.bool.isStr(options.data)) {
                data = options.data;
            } else if (_.util.bool.isObj(options.data)) {
                if (options.data instanceof FormData) {
                    if (options.data.useMultipartFormData) {
                        request.post({
                                url: url,
                                form: options.data.getQueryString(),
                                encoding: 'utf8'
                            },
                            function(error, response, body) {
                                if (!error) {
                                    var that = new Response(response);
                                    if (that.statusCode == 200) {
                                        done.call(that, body);
                                    } else {
                                        fail.call(that, body);
                                    }
                                    if (_.util.bool.isFn(options.always)) {
                                        options.always.call(that, body);
                                    }
                                } else {
                                    fail.call({}, error.message);
                                }
                            }
                        );
                        return this;
                    } else {
                        data = options.data.encodeQueryString();
                    }
                } else {
                    data = _.data.encodeQueryString(options.data);
                }
            } else {
                data = '';
            }
            request.post({
                    url: url,
                    form: data,
                    encoding: 'utf8'
                },
                function(error, response, body) {
                    if (!error) {
                        var that = new Response(response);
                        if (that.statusCode == 200) {
                            done.call(that, body);
                        } else {
                            fail.call(that, body);
                        }
                        if (_.util.bool.isFn(options.always)) {
                            options.always.call(that, body);
                        }
                    } else {
                        fail.call({}, error.message);
                    }
                }
            );
            return this;
        },
        OnLogon(message) {
            localStorage.NTVOI_CONF_G = message.getter_path;
            localStorage.NTVOI_LASTUSER = true;
            localStorage.NTVOI_LASTUSER_PIC = message.avatar;
            localStorage.NTVOI_LASTUSER_OPN = Logger.username.val();
            localStorage.NTVOI_LASTUSER_OPP = Logger.password.val();
            localStorage.NTVOI_SESSID = message.session_id;
            System.IPC.send('log-on', message);
        },
        OnLogoff() {
            System.IPC.send('log-outed', 'see-you');
        }
    });

    _.extend(Runtime, true, {
        runInDesktop: true,
        checkAppOS(mainURL, href) {
            var that = this;
            System.GET({
                url: mainURL + '?check=web',
                done(data) {
                    if (that.appid !== 'I4PLAZA') {
                        Runtime.AppLoadOS.call(that, mainURL, href);
                    } else {
                        Runtime.AppLoadOS.call(that, mainURL + '?session_id=' + localStorage.NTVOI_SESSID, href);
                    }
                },
                fail(data) {
                    if (Runtime.current) {
                        Runtime.AppLoadError.call(that, 'AF_NOT_FOUND');
                    } else {
                        global.console.log(that);
                    }
                }
            });
        }
    });

    _.extend(YangRAM, true, {
        get: System.GET,
        set: System.POST,
        json: System.JSON,
        oninitialize(BGMPlayer, main) {
            var data = JSON.parse(fs.readFileSync(__dirname + '/resources/bgmlist.json')),
                srcs = {};
            _.each(data, (i, music) => {
                srcs[i] = {
                    'audio/ogg': YangRAM.PhysicalURI + music.ogg,
                    'audio/mpeg': YangRAM.PhysicalURI + music.mp3
                };
            });
            BGMPlayer.register(srcs);
            main();
        }
    });

    _.extend(Logger, true, {
        onlogon(message) {
            Logger.avatar.attr('status', 'checked');
            Logger.avatar.css('background-image', 'url(' + message.avatar + ')');
            Logger.pincode.attr('type', 'text').val(' 3 2 1 ');
            setTimeout(() => {
                Logger.pincode.val(' * 2 1 ');
            }, 500);
            setTimeout(() => {
                Logger.pincode.val(' * * 1 ');
            }, 1000);
            setTimeout(() => {
                System.OnLogon(message);
            }, 1500);
        }
    });
});