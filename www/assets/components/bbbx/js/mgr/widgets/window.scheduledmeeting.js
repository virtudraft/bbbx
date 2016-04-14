BBBx.window.ScheduledMeeting = function (config) {
    config = config || {};
    var preloadSlides = MODx.load({
        xtype: 'modx-combo-browser',
        browserEl: 'modx-browser',
        fieldLabel: 'preloadSlides',
        name: 'preloadSlides',
        anchor: '100%'
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
                        border: false,
                        items: [
                            {
                                layout: 'column',
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
                                                anchor: '100%',
                                                allowBlank: false
                                            }
                                        ]
                                    }, {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        items: [
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'meetingID',
                                                name: 'meeting_id',
                                                anchor: '100%'
                                            }
                                        ]
                                    }
                                ]
                            }, {
                                layout: 'form',
                                border: false,
                                items: [
                                    {
                                        xtype: 'textarea',
                                        fieldLabel: 'description',
                                        name: 'description',
                                        anchor: '100%'
                                    }
                                ]
                            }, {
                                layout: 'column',
                                items: [
                                    {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        items: [
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'attendeePW',
                                                name: 'attendee_pw',
                                                anchor: '100%'
                                            }
                                        ]
                                    }, {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        items: [
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'moderatorPW',
                                                name: 'moderator_pw',
                                                anchor: '100%'
                                            }
                                        ]
                                    }
                                ]
                            }, {
                                xtype: 'label',
                                cls: 'desc-under',
                                html: _('bbbx.password_desc'),
                            }, {
                                layout: 'column',
                                items: [
                                    {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        items: [
                                            {
                                                xtype: 'numberfield',
                                                fieldLabel: 'maxParticipants',
                                                name: 'max_participants',
                                                anchor: '100%'
                                            }
                                        ]
                                    }, {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        items: [
//                                            {
//                                                xtype: 'numberfield',
//                                                fieldLabel: 'duration',
//                                                name: 'duration',
//                                                anchor: '100%'
//                                            }
                                        ]
                                    }
                                ]
                            }, {
                                layout: 'form',
                                border: false,
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'logoutURL',
                                        name: 'logout_url',
                                        anchor: '100%'
                                    }
                                ]
                            }
                        ]
                    }, {
                        title: _('bbbx.access'),
                        defaults: {autoHeight: true},
                        border: false,
                        items: [
                            {
                                layout: 'column',
                                items: [
                                    {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        items: [
                                            {
                                                fieldLabel: 'start',
                                                layout: 'hbox',
                                                items: [
                                                    {
                                                        xtype: 'datefield',
                                                        name: 'started_date',
                                                        flex: 1
                                                    }, {
                                                        xtype: 'timefield',
                                                        name: 'started_time',
                                                        format: 'H:i',
                                                        flex: 1
                                                    }
                                                ]
                                            }
                                        ]
                                    }, {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        items: [
                                            {
                                                fieldLabel: 'end',
                                                layout: 'hbox',
                                                items: [
                                                    {
                                                        xtype: 'datefield',
                                                        name: 'ended_date',
                                                        flex: 1
                                                    }, {
                                                        xtype: 'timefield',
                                                        name: 'ended_time',
                                                        format: 'H:i',
                                                        flex: 1
                                                    }
                                                ]
                                            }
                                        ]
                                    }
                                ]
                            }, {
                                fieldLabel: 'usergroups',
                                layout: 'column',
                                items: [
                                    {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        items: [
                                            {
                                                fieldLabel: 'moderator',
                                                xtype: 'bbbx-combo-usergroup',
                                                preventRender: true,
                                                name: 'moderator_usergroups[]',
                                                hiddenName: 'moderator_usergroups[]',
                                                anchor: '100%'
                                            }
                                        ]
                                    }, {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        items: [
                                            {
                                                fieldLabel: 'viewer',
                                                xtype: 'bbbx-combo-usergroup',
                                                preventRender: true,
                                                name: 'viewer_usergroups[]',
                                                hiddenName: 'viewer_usergroups[]',
                                                anchor: '100%'
                                            }
                                        ]
                                    }
                                ]
                            }, {
                                fieldLabel: 'users',
                                layout: 'column',
                                items: [
                                    {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        items: [
                                            {
                                                fieldLabel: 'moderator',
                                                xtype: 'bbbx-combo-user',
                                                preventRender: true,
                                                name: 'moderator_users[]',
                                                hiddenName: 'moderator_users[]',
                                                anchor: '100%'
                                            }
                                        ]
                                    }, {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        items: [
                                            {
                                                fieldLabel: 'viewer',
                                                xtype: 'bbbx-combo-user',
                                                preventRender: true,
                                                name: 'viewer_users[]',
                                                hiddenName: 'viewer_users[]',
                                                anchor: '100%'
                                            }
                                        ]
                                    }
                                ]
                            }, {
                                xtype: 'label',
                                cls: 'desc-under',
                                html: _('bbbx.user_access_desc'),
                            }, {
                                layout: 'column',
                                items: [
                                    {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        items: [
                                            {
                                                xtype: 'radiogroup',
                                                name: 'is_moderator_first',
                                                fieldLabel: _('bbbx.is_moderator_first'),
                                                items: [
                                                    {
                                                        boxLabel: _('no'),
                                                        name: 'is_moderator_first',
                                                        inputValue: 0
                                                    }, {
                                                        boxLabel: _('yes'),
                                                        name: 'is_moderator_first',
                                                        inputValue: 1
                                                    }
                                                ]
                                            }, {
                                                xtype: 'label',
                                                cls: 'desc-under',
                                                html: _('bbbx.is_moderator_first_desc')
                                            }
                                        ]
                                    }, {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        items: [
                                            {
                                                fieldLabel: _('bbbx.context_key'),
                                                xtype: 'bbbx-combo-contextkey',
                                                preventRender: true,
                                                name: 'context_key[]',
                                                hiddenName: 'context_key[]',
                                                anchor: '100%'
                                            }, {
                                                xtype: 'label',
                                                cls: 'desc-under',
                                                html: _('bbbx.context_key_desc')
                                            }
                                        ]
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
                                anchor: '100%'
                            }, {
                                xtype: 'label',
                                cls: 'desc-under',
                                html: _('bbbx.welcome_desc')
                            }, {
                                xtype: 'textarea',
                                fieldLabel: 'moderatorOnlyMessage',
                                name: 'moderator_only_message',
                                grow: true,
                                anchor: '100%'
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
                                        name: 'dial_number',
                                        anchor: '100%'
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'webVoice',
                                        name: 'web_voice',
                                        anchor: '100%'
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
                                        name: 'voice_bridge',
                                        anchor: '100%'
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
                                                name: 'is_recorded',
                                                fieldLabel: 'record',
                                                items: [
                                                    {
                                                        boxLabel: _('no'),
                                                        name: 'is_recorded',
                                                        inputValue: 0
                                                    }, {
                                                        boxLabel: _('yes'),
                                                        name: 'is_recorded',
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
                                                name: 'auto_start_recording',
                                                fieldLabel: 'autoStartRecording',
                                                items: [
                                                    {
                                                        boxLabel: _('no'),
                                                        name: 'auto_start_recording',
                                                        inputValue: 0
                                                    }, {
                                                        boxLabel: _('yes'),
                                                        name: 'auto_start_recording',
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
                                                name: 'allow_start_stop_recording',
                                                fieldLabel: 'allowStartStopRecording',
                                                items: [
                                                    {
                                                        boxLabel: _('no'),
                                                        name: 'allow_start_stop_recording',
                                                        inputValue: 0
                                                    }, {
                                                        boxLabel: _('yes'),
                                                        name: 'allow_start_stop_recording',
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
                                anchor: '100%'
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
    BBBx.window.ScheduledMeeting.superclass.constructor.call(this, config);
};
Ext.extend(BBBx.window.ScheduledMeeting, MODx.Window);
Ext.reg('bbbx-window-scheduledmeeting', BBBx.window.ScheduledMeeting);