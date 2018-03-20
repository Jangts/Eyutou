const electron = require('electron');
const request = require('request');
const app = electron.app;
const BrowserWindow = electron.BrowserWindow;
const Tray = electron.Tray;
const Menu = electron.Menu
const ipc = electron.ipcMain;
const path = require('path');

let logonscreen, workspace, session_id;
let logo = path.join(__dirname, 'favicon.ico');
let icon = path.join(__dirname, 'resources/tray.png');

let jar = request.jar();

global.sharedObject = {
    request: request.defaults({ jar: jar })
};

class ScreenWindow extends BrowserWindow {
    constructor(url, options, events) {
        super(options);
        this.bind(events);
        this.loadURL(url);
        this.state = 1;
        /*
         * 1. 隐藏状态，关闭时需要确认
         * 2. 显示状态，关闭时需要确认
         * 3. 隐藏状态直接关闭
         * 4. 显示状态直接关闭
         */
    }
    createTray(menu, tip) {
        let tray = new Tray(icon);
        let contextMenu = Menu.buildFromTemplate(menu);
        tray.setToolTip(tip);
        tray.setContextMenu(contextMenu);
        tray.on('click', () => {
            if (this.state === 2 || this.state === 4) {
                this.show();
            }
        });
        tray.on('dblclick', () => {
            this.show();
            if (this.state === 1 || this.state === 3) {
                this.state++;
            }
        });
        this.tray = tray;
    }
    bind(events) {
        for (var type in events) {
            if (typeof events[type] === 'function') {
                this.on(type, events[type]);
            } else {
                this.on(type, (event) => {
                    this[events[type]](event);
                });
            }
        }
        return this;
    }
    onminimize(event) {
        if (this.state === 2 || this.state === 4) {
            this.state--;
        }
        this.hide();
    }
    onrestore(event) {
        if (this.state === 1 || this.state === 3) {
            this.state++;
        }
    }
    onclose(event) {
        if (this.state === 1 || this.state === 2) {
            event.preventDefault()
        }
    }
    testing() {
        this.webContents.openDevTools();
        return this;
    }
}

function createLogonscreen() {
    //创建一个 580x350 的浏览器窗口
    logonscreen = new ScreenWindow('file://' + __dirname + '/logonscreen.html', {
        frame: false,
        icon: logo,
        transparent: true,
        width: 580,
        height: 350,
        resizable: false,
        movable: true
    }, {
        'restore': 'onrestore',
        'close': 'onclose'
    });
    // 打开开发者工具，方便调试
    logonscreen.testing();
}

function createWorkspace(username) {
    workspace = new ScreenWindow('file://' + __dirname + '/workspace.html', {
        frame: false,
        icon: logo,
        //useContentSize: true,
        minWidth: 1024,
        minHeight: 544,
        resizable: false,
        show: false
    }, {
        'minimize': 'onminimize',
        'restore': 'onrestore',
        'close': 'onclose'
    });
    workspace.createTray([{
        label: '还原工作区',
        click() {
            showWorkspace();
        }
    }, {
        label: '隐藏工作区',
        click() {
            if (this.state === 2 || this.state === 4) {
                this.state--;
            }
            workspace.hide();
        }
    }, {
        label: '注销登录',
        click() {
            workspace.webContents.send('log-off', session_id);
        }
    }, {
        label: '退出',
        click() {
            closeWorkspace();
            closeLogonscreen();
        }
    }], "Username: " + username + "\r\nYangRAM UOI Native Version");
    // 打开开发者工具，方便调试
    // workspace.testing();
}

function showLogonscreen() {
    if (workspace == undefined) {
        logonscreen.show();
        logonscreen.state = 4;
    }
}

function showWorkspace() {
    workspace.maximize();
    setTimeout(() => {
        workspace.show();
        workspace.state = 2;
    }, 100);
}

function closeLogonscreen() {
    if (logonscreen && (logonscreen.state === 1 || logonscreen.state === 2)) {
        logonscreen.state += 2;
    }
    logonscreen.destroy();
    logonscreen = undefined;
    app.quit();
}

function closeWorkspace() {
    if (workspace && (workspace.state === 1 || workspace.state === 2)) {
        workspace.state += 2;
    }
    workspace.destroy();
    workspace.tray.destroy();
    workspace = undefined;
}

app.on('ready', () => {
    createLogonscreen();
});

app.on('window-all-closed', (event) => {
    if (logonscreen || workspace) {
        event.preventDefault();
    }
});

ipc.on('log-close', (event, message) => {
    if (message === 'close') {
        closeLogonscreen();
    }
});

ipc.on('log-on', (event, message) => {
    session_id = message.session_id;
    logonscreen.hide();
    logonscreen.reload();
    createWorkspace(message.username);
    showWorkspace();
});

ipc.on('log-outed', (event, message) => {
    closeWorkspace();
    showLogonscreen();
});