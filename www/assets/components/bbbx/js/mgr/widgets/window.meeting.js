BBBx.window.Meeting = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        title: _('bbbx.meeting_update'),
        url: BBBx.config.connectorUrl,
        baseParams: {
            action: 'mgr/meetings/running/update'
        },
        width: 800,
        autoScroll: true,
//        fileUpload: true,
        items: [
            {
                html: '<p>' + _('bbbx.api_desc') + _('bbbx.required_field') +'</p>',
                bodyCssClass: 'panel-desc'
            }
        ],
        fields: [
            {
                layout: 'column',
                items: [
                    {
                        columnWidth: .5,
                        baseCls: 'x-plain',
                        layout: 'form',
                        labelAlign: 'top',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'name *',
                                name: 'name',
                                anchor: '100%',
                                allowBlank: false
                            }, {
                                xtype: 'textfield',
                                fieldLabel: 'meetingID',
                                name: 'meetingID',
                                anchor: '100%'
                            }, {
                                xtype: 'textfield',
                                fieldLabel: 'attendeePW',
                                name: 'attendeePW',
                                anchor: '100%'
                            }, {
                                xtype: 'textfield',
                                fieldLabel: 'moderatorPW',
                                name: 'moderatorPW',
                                anchor: '100%'
                            }, {
                                xtype: 'textarea',
                                fieldLabel: 'welcome',
                                name: 'welcome',
                                anchor: '100%'
                            }, {
                                xtype: 'textfield',
                                fieldLabel: 'dialNumber',
                                name: 'dialNumber',
                                anchor: '100%'
                            }, {
                                xtype: 'textfield',
                                fieldLabel: 'voiceBridge',
                                name: 'voiceBridge',
                                anchor: '100%'
                            }, {
                                xtype: 'textfield',
                                fieldLabel: 'webVoice',
                                name: 'webVoice',
                                anchor: '100%'
                            }, {
                                xtype: 'numberfield',
                                fieldLabel: 'maxParticipants',
                                name: 'maxParticipants',
                                anchor: '100%'
                            }
                        ]
                    }, {
                        columnWidth: .5,
                        baseCls: 'x-plain',
                        layout: 'form',
                        labelAlign: 'top',
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'logoutURL',
                                name: 'logoutURL',
                                anchor: '100%'
                            }, {
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
                            }, {
                                xtype: 'numberfield',
                                fieldLabel: 'duration',
                                name: 'duration',
                                anchor: '100%'
                            }, {
                                xtype: 'textarea',
                                fieldLabel: 'moderatorOnlyMessage',
                                name: 'moderatorOnlyMessage',
                                anchor: '100%'
                            }, {
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
                            }, {
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
                            }, {
//                                xtype: 'fileuploadfield',
//                                fieldLabel: 'preloadSlides',
//                                name: 'preloadSlides',
//                                anchor: '100%'
//                            }, {
                                xtype: 'modx-combo-browser',
                                browserEl: 'modx-browser',
                                fieldLabel: 'preloadSlides',
                                name: 'preloadSlides',
                                anchor: '100%'
                            }, {
                                xtype: 'textarea',
                                fieldLabel: 'meta',
                                name: 'meta',
                                anchor: '100%'
                            }, {
                                html: '<p>' + _('bbbx.meta_desc') + '</p>',
                                bodyCssClass: 'panel-desc'
                            }
                        ]
                    }
                ]
            }
        ]
    });
    BBBx.window.Meeting.superclass.constructor.call(this, config);
};
Ext.extend(BBBx.window.Meeting, MODx.Window);
Ext.reg('bbbx-window-meeting', BBBx.window.Meeting);