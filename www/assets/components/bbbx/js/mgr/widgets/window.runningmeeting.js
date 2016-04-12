BBBx.window.RunningMeeting = function (config) {
    config = config || {};
    var preloadSlides = MODx.load({
        xtype: 'modx-combo-browser',
        browserEl: 'modx-browser',
        fieldLabel: 'preloadSlides',
        name: 'preloadSlides',
        anchor: '98%'
    });
    preloadSlides.on('select', function (data) {
        var srcBrowserId = preloadSlides.browser.id;
        var browserCmp = Ext.getCmp(srcBrowserId);
        var source = browserCmp.tree.baseParams.source;
        var preloadSlidesSourceIdField = this.fp.getForm().findField('preloadSlidesSourceId');
        preloadSlidesSourceIdField.setValue(source);
    }, this);
    Ext.applyIf(config, {
        // provided by triggers
//        title: _('bbbx.meeting_update'),
//        baseParams: {
//            action: 'mgr/meetings/running/create'
//        },
        url: BBBx.config.connectorUrl,
        width: 600,
        autoScroll: true,
//        fileUpload: true,
        allowDrop: false,
        items: [
            {
                html: '<p>' + _('bbbx.api_desc') + _('bbbx.name_is_required') + '</p>',
                bodyCssClass: 'panel-desc'
            }
        ],
        fields: [
            {
                xtype: 'modx-tabs',
                enableTabScroll: true,
                defaults: {
                    border: false,
                    autoHeight: true,
                    layout: 'form'
                },
                border: true,
                items: [
                    {
                        title: _('bbbx.basics'),
                        defaults: {autoHeight: true},
                        layout: 'column',
                        border: false,
                        items: [
                            {
                                columnWidth: .5,
                                layout: 'form',
                                border: false,
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'name *',
                                        name: 'name',
                                        anchor: '98%',
                                        allowBlank: false
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'attendeePW',
                                        name: 'attendeePW',
                                        anchor: '98%'
                                    }, {
                                        xtype: 'numberfield',
                                        fieldLabel: 'maxParticipants',
                                        name: 'maxParticipants',
                                        anchor: '98%'
                                    },
                                ]
                            }, {
                                columnWidth: .5,
                                layout: 'form',
                                border: false,
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'meetingID',
                                        name: 'meetingID',
                                        anchor: '98%'
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'moderatorPW',
                                        name: 'moderatorPW',
                                        anchor: '98%'
                                    }, {
                                        xtype: 'numberfield',
                                        fieldLabel: 'duration',
                                        name: 'duration',
                                        anchor: '98%'
                                    },
                                ]
                            }, {
                                columnWidth: 1,
                                layout: 'form',
                                border: false,
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'logoutURL',
                                        name: 'logoutURL',
                                        anchor: '98%'
                                    }
                                ]
                            }
                        ]
                    }, {
                        title: _('bbbx.messages'),
                        defaults: {autoHeight: true},
                        items: [
                            {
                                xtype: 'textarea',
                                fieldLabel: 'welcome',
                                name: 'welcome',
                                grow: true,
                                anchor: '98%'
                            }, {
                                xtype: 'textarea',
                                fieldLabel: 'moderatorOnlyMessage',
                                name: 'moderatorOnlyMessage',
                                grow: true,
                                anchor: '98%'
                            }
                        ]
                    }, {
                        title: _('bbbx.voice'),
                        defaults: {autoHeight: true},
                        layout: 'column',
                        border: false,
                        items: [
                            {
                                columnWidth: .5,
                                layout: 'form',
                                border: false,
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'dialNumber',
                                        name: 'dialNumber',
                                        anchor: '98%'
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'webVoice',
                                        name: 'webVoice',
                                        anchor: '98%'
                                    }
                                ]
                            }, {
                                columnWidth: .5,
                                layout: 'form',
                                border: false,
                                items: [
                                    {
                                        xtype: 'numberfield',
                                        fieldLabel: 'voiceBridge',
                                        name: 'voiceBridge',
                                        anchor: '98%'
                                    }
                                ]
                            }
                        ]
                    }, {
                        title: _('bbbx.recording'),
                        defaults: {autoHeight: true},
                        items: [
                            {
                                layout: 'column',
                                autoScroll: true,
                                items: [
                                    {
                                        columnWidth: .33,
                                        baseCls: 'x-plain',
                                        bodyStyle: 'padding:5px 0 5px 5px',
                                        layout: 'form',
                                        items: [
                                            {
                                                xtype: 'radiogroup',
                                                fieldLabel: 'record',
                                                items: [
                                                    {
                                                        boxLabel: _('no'),
                                                        name: 'record',
                                                        inputValue: 0,
//                                        checked: true
                                                    }, {
                                                        boxLabel: _('yes'),
                                                        name: 'record',
                                                        inputValue: 1
                                                    }
                                                ]
                                            }
                                        ]
                                    }, {
                                        columnWidth: .33,
                                        baseCls: 'x-plain',
                                        bodyStyle: 'padding:5px 0 5px 5px',
                                        layout: 'form',
                                        items: [
                                            {
                                                xtype: 'radiogroup',
                                                fieldLabel: 'autoStartRecording',
                                                items: [
                                                    {
                                                        boxLabel: _('no'),
                                                        name: 'autoStartRecording',
                                                        inputValue: 0,
//                                        checked: true
                                                    }, {
                                                        boxLabel: _('yes'),
                                                        name: 'autoStartRecording',
                                                        inputValue: 1
                                                    }
                                                ]
                                            }
                                        ]
                                    }, {
                                        columnWidth: .33,
                                        baseCls: 'x-plain',
                                        bodyStyle: 'padding:5px 0 5px 5px',
                                        layout: 'form',
                                        items: [
                                            {
                                                xtype: 'radiogroup',
                                                fieldLabel: 'allowStartStopRecording',
                                                items: [
                                                    {
                                                        boxLabel: _('no'),
                                                        name: 'allowStartStopRecording',
                                                        inputValue: 0,
//                                        checked: true
                                                    }, {
                                                        boxLabel: _('yes'),
                                                        name: 'allowStartStopRecording',
                                                        inputValue: 1
                                                    }
                                                ]
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    }, {
                        title: _('bbbx.preload_slides'),
                        defaults: {autoHeight: true},
                        items: [
                            preloadSlides, {
                                xtype: 'hidden',
                                name: 'preloadSlidesSourceId'
                            }
                        ]
                    }, {
                        title: _('bbbx.meta'),
                        defaults: {autoHeight: true},
                        items: [
                            {
                                xtype: 'textarea',
                                fieldLabel: 'meta',
                                name: 'meta',
                                grow: true,
                                anchor: '98%'
                            }, {
                                xtype: 'label',
                                cls: 'desc-under',
                                html: _('bbbx.meta_desc'),
                            }
                        ]
                    }, {
                        title: _('bbbx.configurations'),
                        defaults: {autoHeight: true},
                        items: [
                            {
                                xtype: 'bbbx-combo-config',
                                name: 'config',
                                hiddenName: 'config',
                            }
                        ]
                    }
                ]
            }
        ]
    });
    BBBx.window.RunningMeeting.superclass.constructor.call(this, config);
};
Ext.extend(BBBx.window.RunningMeeting, MODx.Window);
Ext.reg('bbbx-window-runningmeeting', BBBx.window.RunningMeeting);