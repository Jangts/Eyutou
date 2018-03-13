System.Loader(function(YangRAM, global, undefined) {
    var Runtime = this.Runtime,
        Promise = YangRAM.API.util.Promise,
        items = [
            /* Load HiBar Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/HiBarItems.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Title Bar And Context Menu Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/MenuInterface.css';
                YangRAM.loadStyle(href, () => {
                    this.HiBar.build();
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Account Informations */
            (resolve) => {
                this.UpdateLoadingStatus().GET({
                    url: YangRAM.RequestDIR + 'uoi/account/info/' + Runtime.locales.CODE,
                    done: (txt) => {
                        //console.log(txt);
                        this.HiBar.Account.innerHTML = txt;
                        this.LoadedRateChange();
                        resolve();
                    },
                    fail: (txt) => {
                        console.log(txt);
                    }
                });
            },

            /* Load Kalendar Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/KalendarEvents.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Kalendar Data */
            (resolve) => {
                this.UpdateLoadingStatus();
                this.Kalendar.off().load(() => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Smartian Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/SmartianStyle.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Smartian Configurations */
            (resolve) => {
                this.UpdateLoadingStatus();
                this.Smartian.build(() => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Launcher Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/LaunchInterface.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load App Ranking List Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/ARLInterface.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load App Bookmarks Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/BookmarkTypes.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Launcher Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                this.Workspace.Launcher.build(() => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Installed Application List */
            (resolve) => {
                this.UpdateLoadingStatus();
                this.Workspace.Launcher.Memowall.refresh(() => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Messager Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/MessageCenter.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Unread Messages */
            (resolve) => {
                this.UpdateLoadingStatus();
                this.Notifier.build(() => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Browser Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/BrowserInterface.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Time Picker Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/TimePicker.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Color Picker Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/ColorPicker.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Option Selector Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/OptionSelector.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Percentager Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/Percentager.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Appliction Style Sheets / Step One */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/WinInterface.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Appliction Style Sheets / Step Two */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/WLMInterface.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Animations Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/Animations.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step One */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/framework/Layout.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Two */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/framework/BgColors.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Three */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/framework/TabViews.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Four */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/framework/Slider.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* XX I: Load OIML Style Sheets / Step Five */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/framework/SliderPanel.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Six */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/framework/Menu.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Seven */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/framework/Blocks.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Eight */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/framework/List.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Nine */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/framework/Form.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Ten */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/framework/RadioAndCheckBox.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Eleven */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/framework/EditPanel.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Twelve */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/framework/ModernTable.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Thirteen */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/framework/ModernForm.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load I4Plaza Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/I4Plaza.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Settings Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'resources/themes/' + this.Theme + '/Settings.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            }
        ]

    this.Resize();
    this.ListenLoaded();
    YangRAM.API.isFn(this.onLoadingStatusChange) && this.onUpdateLoadingStatus();
    this.UpdateLoadingStatus();
    this.LoadedRateChange();

    Promise.oneByOne(items).then(() => {
        this.UpdateLoadingStatus();
        YangRAM.API.APP.launch('I4PLAZA');
    }, function() {
        console.log(this);
    });
}, true);