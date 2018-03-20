tangram.block([
        '$_/data/',
        '$_/dom/Elements/'
    ], function(_, global, undefined) {
        var $ = _.dom.select,
            document = global.document,
            body = document.body || document.getElementsByTagName('body')[0],
            isSupportWebp = !![].map && document.createElement('canvas').toDataURL('image/webp').indexOf('data:image/webp') == 0;

        var htmlBuilde = function() {
            if ($('#tncode_div_bg').length) {
                return;
            }
            var html = '<div class="loading">加载中</div>';
            html += '<canvas class="tncode_canvas_bg"></canvas>';
            html += '<canvas class="tncode_canvas_mark"></canvas>';
            html += '<div class="hgroup"></div>';
            html += '<div class="tncode_msg_error"></div>';
            html += '<div class="tncode_msg_ok"></div>';
            html += '<div class="slide">';
            html += '<div class="slide_block"></div>';
            html += '<div class="slide_block_text">拖动左边滑块完成上方拼图</div></div>';
            html += '<div class="tools">';
            html += '<div class="tncode_close"></div>';
            html += '<div class="tncode_refresh"></div></div></div>';
            _.dom.create('div', body, {
                id: 'tncode_div_bg',
                className: 'tncode_div_bg'
            });
            _.dom.create('div', body, {
                id: 'tncode_div',
                className: 'tncode_div',
                html: html
            });
            return true;
        }

        _.declareClass('DVCode', {
            drawFullBackground: function() {
                var canvas_bg = $('.tncode_canvas_bg')[0];
                var ctx_bg = canvas_bg.getContext('2d');
                ctx_bg.drawImage(this._img, 0, this._img_h * 2, this._img_w, this._img_h, 0, 0, this._img_w, this._img_h);
            },
            drawBackground: function() {
                if (this._is_draw_bg) {
                    return;
                }
                this._is_draw_bg = true;
                var canvas_bg = $('.tncode_canvas_bg')[0];
                var ctx_bg = canvas_bg.getContext('2d');
                ctx_bg.drawImage(this._img, 0, 0, this._img_w, this._img_h, 0, 0, this._img_w, this._img_h);
            },
            drawMark: function() {
                var canvas_mark = $('.tncode_canvas_mark')[0];
                var ctx_mark = canvas_mark.getContext('2d');
                //清理画布
                ctx_mark.clearRect(0, 0, canvas_mark.width, canvas_mark.height);
                ctx_mark.drawImage(this._img, 0, this._img_h, this._mark_w, this._img_h, this._mark_offset, 0, this._mark_w, this._img_h);
                var imageData = ctx_mark.getImageData(0, 0, this._img_w, this._img_h);
                // 获取画布的像素信息
                // 是一个一维数组，包含以 RGBA 顺序的数据，数据使用  0 至 255（包含）的整数表示
                // 如：图片由两个像素构成，一个像素是白色，一个像素是黑色，那么 data 为
                // [255,255,255,255,0,0,0,255]
                // 这个一维数组可以看成是两个像素中RBGA通道的数组的集合即:
                // [R,G,B,A].concat([R,G,B,A])
                var data = imageData.data;
                //alert(data.length/4);
                var x = this._img_h,
                    y = this._img_w;
                for (var j = 0; j < x; j++) {
                    var ii = 1,
                        k1 = -1;
                    for (var k = 0; k < y && k >= 0 && k > k1;) {
                        // 得到 RGBA 通道的值
                        var i = (j * y + k) * 4;
                        k += ii;
                        var r = data[i],
                            g = data[i + 1],
                            b = data[i + 2];
                        // 我们从最下面那张颜色生成器中可以看到在图片的右上角区域，有一小块在
                        // 肉眼的观察下基本都是白色的，所以我在这里把 RGB 值都在 245 以上的
                        // 的定义为白色
                        // 大家也可以自己定义的更精确，或者更宽泛一些
                        if (r + g + b < 200) data[i + 3] = 0;
                        else {
                            var arr_pix = [1, -5];
                            var arr_op = [250, 0];
                            for (var i = 1; i < arr_pix[0] - arr_pix[1]; i++) {
                                var iiii = arr_pix[0] - 1 * i;
                                var op = parseInt(arr_op[0] - (arr_op[0] - arr_op[1]) / (arr_pix[0] - arr_pix[1]) * i);
                                var iii = (j * y + k + iiii * ii) * 4;
                                data[iii + 3] = op;
                            }
                            if (ii == -1) {
                                break;
                            }
                            k1 = k;
                            k = y - 1;
                            ii = -1;
                        };
                    }
                }
                ctx_mark.putImageData(imageData, 0, 0);
            },
            _obj: null,
            _tncode: null,
            _img: null,
            _img_loaded: false,
            _is_draw_bg: false,
            _is_moving: false,
            _block_start_x: 0,
            _block_start_y: 0,
            _doing: false,
            _mark_w: 50,
            _mark_h: 50,
            _mark_offset: 0,
            _img_w: 240,
            _img_h: 150,
            _result: false,
            _err_c: 0,
            _onsuccess: null,
            _init: function(options) {
                _.each(options, function(option, value) {
                    this[option] = value;
                }, this);
                console
                    .log('1');
                if (htmlBuilde()) {
                    console
                        .log('2');
                    this.listenEvents();
                }
            },
            show: function() {
                $('.hgroup').hide();
                this.refresh();
                $('#tncode_div_bg').show();
                $('#tncode_div').show();
            },
            hide: function() {
                $('#tncode_div_bg').hide();
                $('#tncode_div').hide();
            },
            refresh: function() {
                var that = this;
                this._err_c = 0;
                this._is_draw_bg = false;
                this._result = false;
                this._img_loaded = false;

                $('.tncode_canvas_bg').hide();
                $('.tncode_canvas_mark').hide();

                var img_url = this.baseUrl + "&m=make&t=" + Math.random();
                if (!isSupportWebp) {
                    //浏览器不支持webp
                    img_url += "&nowebp";
                }

                this._img = new Image();
                this._img.src = img_url;
                this._img.onload = function() {
                    that.drawFullBackground();
                    var canvas_mark = $('.tncode_canvas_mark')[0];
                    var ctx_mark = canvas_mark.getContext('2d');
                    //清理画布
                    ctx_mark.clearRect(0, 0, canvas_mark.width, canvas_mark.height);
                    that._img_loaded = true;
                    $('.tncode_canvas_bg').show();
                    $('.tncode_canvas_mark').show();
                };

                $('.slide_block').css('transform', 'translate(0px, 0px)');
                $('.slide_block_text').show();
            },
            result: function() {
                return this._result;
            },
            onsuccess: function(fn) {
                this._onsuccess = fn;
            },
            listenEvents: function() {
                if (!this._img) {
                    var that = this;
                    $('#tncode_div')
                        .on(['mousedown', 'touchstart'], '.slide_block', this, function(e) {
                            that._block_start_move(e);
                        })
                        .on(['touchstart', 'click'], '.tncode_close', this, function(e) {
                            that._block_start_move(e);
                        })
                        .on(['mousedown', 'click'], '.tncode_refresh', this, function(e) {
                            that._block_start_move(e);
                        });
                    $(document)
                        .on(['mousemove', 'touchmove'], this, function(e) {
                            that._block_start_move(e);
                        })
                        .on(['mouseup', 'touchend'], this, function(e) {
                            that._block_on_end(e);
                        });


                }
                return this;
            },
            _block_start_move: function(e) {
                if (this._doing || !this._img_loaded) {
                    return;
                }
                e.preventDefault();
                var theEvent = window.event || e;
                if (theEvent.touches) {
                    theEvent = theEvent.touches[0];
                }

                console.log("_block_start_move");

                $('.slide_block_text').hide();
                this.drawBackground();
                this._block_start_x = theEvent.clientX;
                this._block_start_y = theEvent.clientY;
                this._doing = true;
                this._is_moving = true;
            },
            _block_on_move: function(e) {
                if (!this._doing) return true;
                if (!this._is_moving) return true;
                e.preventDefault();
                var theEvent = window.event || e;
                if (theEvent.touches) {
                    theEvent = theEvent.touches[0];
                }
                this._is_moving = true;
                console.log("_block_on_move");
                var offset = theEvent.clientX - this._block_start_x;
                if (offset < 0) {
                    offset = 0;
                }
                var max_off = this._img_w - this._mark_w;
                if (offset > max_off) {
                    offset = max_off;
                }
                $('.slide_block').css('transform', 'translate(' + offset + 'px, 0px');
                this._mark_offset = offset / max_off * (this._img_w - this._mark_w);
                this.drawBackground();
                this.drawMark();
            },
            _block_on_end: function(e) {
                if (!this._doing) return true;
                e.preventDefault();
                var theEvent = window.event || e;
                if (theEvent.touches) {
                    theEvent = theEvent.touches[0];
                }
                console.log("_block_on_end");
                this._is_moving = false;
                this._send_result();
            },
            _send_result: function() {
                var haddle = { success: this._send_result_success, failure: this._send_result_failure };
                this._result = false;
                _.data.AJAX(this.baseUrl + '&m=check.php&tokken=' + this._mark_offset, {
                    method: 'GET',
                    success: this._send_result_success,
                    fail: this._send_result_failure
                });
            },
            _send_result_success: function(responseText, responseXML) {
                this._doing = false;
                if (responseText == 'ok') {
                    this._this.innerHTML = '√验证成功';
                    this._showmsg('√验证成功', 1);
                    this._result = true;
                    $('.hgroup').show();
                    setTimeout(this.hide, 3000);
                    if (this._onsuccess) {
                        this._onsuccess();
                    }
                } else {
                    var obj = $('#tncode_div');
                    addClass(obj, 'dd');
                    setTimeout(function() {
                        removeClass(obj, 'dd');
                    }, 200);
                    this._result = false;
                    this._showmsg('验证失败');
                    this._err_c++;
                    if (this._err_c > 5) {
                        this.refresh();
                    }
                }
            },
            _send_result_failure: function(xhr, status) {

            },
            _reset: function() {
                this._mark_offset = 0;
                this.drawBackground();
                this.drawMark();
                $('.slide_block');
                obj.style.cssText = "transform: translate(0px, 0px)";
            },
            _showmsg: function(msg, status) {
                if (!status) {
                    status = 0;
                    $('.tncode_msg_error');
                } else {
                    $('.tncode_msg_ok');
                }
                obj.innerHTML = msg;
                var setOpacity = function(ele, opacity) {
                    if (ele.style.opacity != undefined) {
                        ///兼容FF和GG和新版本IE
                        ele.style.opacity = opacity / 100;

                    } else {
                        ///兼容老版本ie
                        ele.style.filter = "alpha(opacity=" + opacity + ")";
                    }
                };

                function fadeout(ele, opacity, speed) {
                    if (ele) {
                        var v = ele.style.filter.replace("alpha(opacity=", "").replace(")", "") || ele.style.opacity || 100;
                        v < 1 && (v = v * 100);
                        var count = speed / 1000;
                        var avg = (100 - opacity) / count;
                        var timer = null;
                        timer = setInterval(function() {
                            if (v - avg > opacity) {
                                v -= avg;
                                setOpacity(ele, v);
                            } else {
                                setOpacity(ele, 0);
                                if (status == 0) {
                                    this._reset();
                                }
                                clearInterval(timer);
                            }
                        }, 100);
                    }
                }

                function fadein(ele, opacity, speed) {
                    if (ele) {
                        var v = ele.style.filter.replace("alpha(opacity=", "").replace(")", "") || ele.style.opacity;
                        v < 1 && (v = v * 100);
                        var count = speed / 1000;
                        var avg = count < 2 ? (opacity / count) : (opacity / count - 1);
                        var timer = null;
                        timer = setInterval(function() {
                            if (v < opacity) {
                                v += avg;
                                setOpacity(ele, v);
                            } else {
                                clearInterval(timer);
                                setTimeout(function() { fadeout(obj, 0, 6000); }, 1000);
                            }
                        }, 100);
                    }
                }

                fadein(obj, 80, 4000);
            }
        });
    },
    'myscript');



var tncode = {
    _obj: null,
    _tncode: null,
    _img: null,
    _img_loaded: false,
    _is_draw_bg: false,
    _is_moving: false,
    _block_start_x: 0,
    _block_start_y: 0,
    _doing: false,
    _mark_w: 50,
    _mark_h: 50,
    _mark_offset: 0,
    _img_w: 240,
    _img_h: 150,
    _result: false,
    _err_c: 0,
    _onsuccess: null,




};