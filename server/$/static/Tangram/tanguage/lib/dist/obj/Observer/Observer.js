/*!
 * tanguage framework source code
 *
 * class obj.Observer
 *
 * Date 2017-04-06
 */
;
tang.init().block([
    '$_/obj/Observer/Subscriber',
    '$_/obj/Observer/Listener'
], function(pandora, root, imports, undefined) {
    var _ = pandora,
        declare = pandora.declareClass,
        console = root.console;

    var Subscriber = _.obj.Observer.Subscriber,
        Listener = _.obj.Observer.Listener,
        observe = function(data) {
            if (data && typeof data === 'object') {
                return new Observer(data);
            }
            return false;
        },
        activeListen = function(observer, key, val) {
            var subscriber = new Subscriber();
            observe(val);
            Object.defineProperty(observer.data, key, {
                enumerable: true,
                configurable: true,
                get: function() {
                    if (observer.listener) {
                        subscriber.watch(observer.listener);
                    } else if (observer.silently === false) {
                        subscriber.notify(false, false);
                    }
                    return val;
                },
                set: function(value) {
                    if (value === val) {
                        return;
                    }
                    val = value;
                    _.util.bool.isObj(value) && observe(value);
                    if (observer.silently === false) {
                        subscriber.notify(true, false);
                    } else {
                        subscriber.notify(true, true);
                    }
                }
            });
        },

        /**
         * 创建一个基本对象的观察对象
         * 
         * @param   {Object}    data        需要被观察的对象（数据）
         * 
         */
        Observer = declare('obj.Observer', {
            listener: null,
            silently: false,
            _init: function(data) {
                this.data = data;
                this.walk(data);
            },
            walk: function(data) {
                _.each(data, function(key, val) {
                    if (data.hasOwnProperty(key)) {
                        activeListen(this, key, val, true);
                    }
                }, this);
                data._observer = this;
            },
            listen: function(property, writeCallback, readCallback) {
                if (_.util.bool.isStr(property) && _.util.bool.hasProp(this.data, property)) {
                    return new Listener(this, property, writeCallback, readCallback);
                }
                if (_.util.bool.isFn(property)) {
                    readCallback = writeCallback;
                    writeCallback = property;
                }
                _.each(this.data, function(property) {
                    if (property !== '_observer' && _.util.bool.hasProp(this.data, property)) {
                        new Listener(this, property, writeCallback, readCallback);
                    }
                }, this);
            },
            on: function(eventType, property, callback) {
                if (eventType === 'read') {
                    return new Listener(this, property, null, callback);
                }
                if (eventType === 'read') {
                    return new Listener(this, property, callback, null);
                }
            }
        });

    _('obj', {
        observe: observe
    });

    _('obj.Observer', {
        create: observe
    });
});