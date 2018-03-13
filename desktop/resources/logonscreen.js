const electron = require('electron');
const path = require('path');
const fs = require('fs');
const ipc = electron.ipcRenderer;
const remote = electron.remote;

if (localStorage.NTVOI_CONF_LANG) {
    var lang = localStorage.NTVOI_CONF_LANG;
} else {
    var lang = 'zh-cn';
}

require('Interblocks');
iBlock.config({
    corePath: './node_modules/interblocks/'
});

iBlock([
    '$_/util/Time.Cls',
    '$_/data/',
    '$_/data/hash.xtd',
    '$_/data/Clipboard.Cls',
    '$_/dom/Elements/form.clsx',
    '$_/form/Data.Cls',
    '$_/medias/Player.Cls',
    '$_/see/BasicScrollBAR.Cls',
    '$_/widgets/Component.Cls',
    '$_/widgets/Alerter.Cls',
    /* ************ */
    'resources/scripts/System',
    'resources/scripts/Application',
    'resources/scripts/ApplicationAPIs',
    'resources/scripts/YangRAM',
    'resources/scripts/YangRAMAPIs',
    'resources/scripts/CrossPlatform',
    'locales/' + lang,
    'resources/scripts/Logger',
    'resources/scripts/Runtime'
], function(pandora, global, undefined) {
    var _ = pandora,
        declare = pandora.declareClass,
        cache = pandora.locker,
        $ = pandora.dom.$,
        document = global.document,
        location = global.location;
    //localStorage.clear();
    var System = global.System;
    System.IPC = ipc;

    var Topbar = $('topbar'),
        Logger = $('logger'),
        Inputs = $('logger input'),
        Setter = $('setter');

    if (localStorage.NTVOI_CONF) {
        $('setter [name=host]').val(localStorage.NTVOI_CONF_H);
        $('setter [name=code]').attr('placeholder', '****************');
        $('setter [name=sapi]').val(localStorage.NTVOI_CONF_A);
        Logger.show();
        if (localStorage.NTVOI_LASTUSER) {
            $('avatar').css('background-image', 'url(' + localStorage.NTVOI_LASTUSER_PIC + ')');
            $('logger [name=opn]').val(localStorage.NTVOI_LASTUSER_OPN);
            $('logger [name=opp]').val(localStorage.NTVOI_LASTUSER_OPP);
        } else {
            $('avatar').css('background-image', 'url(' + path.join(__dirname, 'resources/operator.jpg').replace(/\\/g, '/') + ')');
        }

        YangRAM.initialize(__dirname, localStorage.NTVOI_CONF_O, '', localStorage.NTVOI_CONF_S, () => {
            System.Logger.listenEvents();
        });
    } else {
        Inputs.attr('readonly', '').attr('placeholder', 'Configure At First');
    }

    Topbar
        .on('click', 'setting', () => {
            Logger.hide();
            Setter.show();
        })
        .on('click', 'close', () => {
            ipc.send('log-close', 'close');
        });
    Setter
        .on('click', 'click[name=submit]', () => {
            var host = $('setter [name=host]').val();
            var code = $('setter [name=code]').val();
            var sapi = $('setter [name=sapi]').val();
            var lang = $('setter [name=lang]').val();
            if (host && code) {
                Setter.hide();
                Logger.show();
                var hash = _.data.hash.sha256(code),
                    oipath = host + '/_deuoi_' + hash + '_/',
                    setter = host + '/' + sapi + '/';
                localStorage.NTVOI_CONF = true;
                localStorage.NTVOI_CONF_H = host;
                localStorage.NTVOI_CONF_C = hash;
                localStorage.NTVOI_CONF_A = sapi;
                localStorage.NTVOI_CONF_O = oipath;
                localStorage.NTVOI_CONF_S = setter;
                $('setter [name=code]').attr('placeholder', '****************');
                Inputs.removeAttr('readonly');
                YangRAM.initialize(__dirname, oipath, '', setter, () => {
                    System.Logger.listenEvents();
                });
            }
        })
        .on('click', 'click[name=return]', () => {
            Setter.hide();
            Logger.show();
        });
}, true);