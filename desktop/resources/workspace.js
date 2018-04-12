const electron = require('electron');
const path = require('path');
const fs = require('fs');
const tangram = require('@jangts/tangram.js');
const ipc = electron.ipcRenderer;
const remote = electron.remote;

if (localStorage.NTVOI_CONF_LANG) {
    var lang = localStorage.NTVOI_CONF_LANG;
} else {
    var lang = 'zh-CN';
}

tangram.config({
    corePath: './node_modules/@jangts/tangram.js'
});

tangram.block([
    '$_/util/imports.xtd',
    '$_/util/arr.xtd',
    '$_/util/obj.xtd',
    '$_/util/str.xtd',
    '$_/util/locales/en',
    '$_/util/Time',
    '$_/util/Promise',
    '$_/data/hash.xtd',
    '$_/data/MD5',
    '$_/data/Uploader',
    '$_/data/Clipboard',
    '$_/data/Component',
    '$_/data/Month',
    '$_/dom/Elements',
    '$_/dom/Elements',
    '$_/dom/Template',
    '$_/form/Editor/toolbarTypes/complete.bar',
    '$_/form/Editor/toolbarTypes/normal.bar',
    '$_/form/Editor/toolbarTypes/simple.bar',
    '$_/form/Editor/emoticons/default.emt',
    '$_/form/Data',
    '$_/media/Player',
    '$_/medias/Image',
    '$_/see/fa.css',
    '$_/see/Scrollbar/Abstract',
    '$_/see/Slider/',
    '$_/see/Tabs/TabViews',
    '$_/see/popup/Alerter',
    /* ************ */
    'resources/scripts/System.js',
    'resources/scripts/Application.js',
    'resources/scripts/ApplicationAPIs.js',
    'resources/scripts/YangRAM.js',
    'resources/scripts/YangRAMAPIs.js',
    'resources/scripts/CrossPlatform.js',
    'locales/' + lang,
    'resources/scripts/OIMLElement.js',
    'resources/scripts/Browser.js',
    'resources/scripts/TitleAndMenu.js',
    'resources/scripts/ContextMenu.js',
    'resources/scripts/HiBar.js',
    'resources/scripts/ProcessBus.js',
    'resources/scripts/Workspace.js',
    'resources/scripts/MagicCube.js',
    'resources/scripts/Dialog.js',
    'resources/scripts/Logger.js',
    'resources/scripts/Locker.js',
    'resources/scripts/Uploader.js',
    'resources/scripts/Explorer.js',
    'resources/scripts/TimePicker.js',
    'resources/scripts/TimePickerBuilders.js',
    'resources/scripts/Kalendar.js',
    'resources/scripts/KalendarEvent.js',
    'resources/scripts/Smartian.js',
    'resources/scripts/Launcher.js',
    'resources/scripts/RankingList.js',
    'resources/scripts/ARLCxtMenus.js',
    'resources/scripts/Memowall.js',
    'resources/scripts/BookmarkModel.js',
    'resources/scripts/BookmarkGroup.js',
    'resources/scripts/BookmarkModifier.js',
    'resources/scripts/Notifier.js',
    'resources/scripts/Message.js',
    'resources/scripts/Runtime.js'
], function(pandora, global, undefined) {
    var _ = pandora,
        declare = pandora.declareClass,
        cache = pandora.locker,
        $ = pandora.dom.$,
        document = global.document,
        location = global.location;

    var modules = './node_modules/';

    require.config({
        packages: [{
                main: 'echarts',
                location: modules + 'echarts/src',
                name: 'echarts'
            },
            {
                main: 'zrender',
                location: modules + 'zrender',
                name: 'zrender'
            }
        ]
    });

    var System = global.System;
    ipc.on('log-off', (event, message) => {
        if (message === localStorage.NTVOI_SESSID) {
            System.Logger.logoff();
        }
        //console.log(message, localStorage.NTVOI_SESSID);
    });

    System.IPC = ipc;

    System.DebugMode = '1';
    System.Theme = 'default';
    System.User = localStorage.NTVOI_LASTUSER_OPN;
    System.UserAvatar = localStorage.NTVOI_LASTUSER_PIC;
    $('vision[name=on]').html(this.User);
    $('avatar').css('background-image', 'url(' + localStorage.NTVOI_LASTUSER_PIC + ')');
    $('locker form vision[name=username]').html(localStorage.NTVOI_LASTUSER_OPN);
    System.LoadingItemsCount = 38;
    System.OnLoadStart = () => {
        System.Logger.loadingstatus.attr('status', 'loading');
        System.Logger.loadedpercent.html(System.LoadedRate);
    };
    System.OnLoadingStatusChange = () => {
        var str = System.LoadingStatus + '(' + System.Loaded + '/' + System.LoadingItemsCount + ')';
        System.Logger.loadingstatus.html(str);
        // console.log(System.Loaded, str);
    };
    System.OnLoaded = () => {
        System.Logger.loadedpercent.html(System.LoadedRate);
    }
    System.OnLoadCompletely = () => {
        global.System = undefined;
        System.Logger.sleep();
        setTimeout(function() {
            System.HiBar.launch().listenEvents();
        }, 500);
        setTimeout(function() {
            System.Workspace.Launcher.launch();
        }, 1500);
    };

    global.onresize = () => {
        System.Resize();
    };
    window.onbeforeunload = () => {
        return System.OnClose();
    };

    System.KeyEvents = (e) => {
        var  elem  =  e.relatedTarget  ||  e.srcElement  ||  e.target  || e.currentTarget;
        if (e.keyCode == 8) {
            if (elem.tagName == 'INPUT') {
                var type = elem.type.toUpperCase();
                if (type == 'TEXT' || type == 'PASSWORD') {
                    if (elem.readOnly == true || elem.disabled == true) {
                        return false;
                    }
                    return;
                }
                return false;
            } else if (elem.tagName == 'TEXTAREA') {
                if (elem.readOnly == true || elem.disabled == true) {
                    return false;
                }
                return;
            } else if (elem.getAttribute('contenteditable') == 'true') {
                return;
            }
            return false;
        }
    }
    document.onkeydown = System.KeyEvents;

    try {
        var script = fs.readFileSync(__dirname + '/node_modules/interblocks/util/locales/' + lang.toLowerCase().substring(0, 2) + '.js');
        eval(script);
    } catch (error) {
        console.log(error)
    }
    YangRAM.initialize(__dirname, localStorage.NTVOI_CONF_O, localStorage.NTVOI_CONF_G, localStorage.NTVOI_CONF_S, function() {
        System.Load();
    });
}, true);