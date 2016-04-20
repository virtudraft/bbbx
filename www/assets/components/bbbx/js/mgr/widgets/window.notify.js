BBBx.window.Notify = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        // provided by triggers
        title: _('bbbx.notify_users'),
        baseParams: {
            action: 'mgr/meetings/notifications/update'
        },
        url: BBBx.config.connectorUrl,
        width: 600,
        autoScroll: true,
//        fileUpload: true,
        allowDrop: false,
        items: [
            {
                html: '<p>' + _('bbbx.notify_users_desc') + '</p>',
                bodyCssClass: 'panel-desc'
            }
        ],
        fields: [
            {
                xtype: 'hidden',
                name: 'meeting_id'
            }, {
                layout: 'column',
                items: [
                    {
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        items: [
                            {
                                fieldLabel: _('bbbx.usergroups'),
                                xtype: 'bbbx-combo-usergroup',
                                name: 'usergroups[]',
                                hiddenName: 'usergroups[]',
                                anchor: '100%'
                            }, {
                                fieldLabel: _('bbbx.users'),
                                xtype: 'bbbx-combo-user',
                                name: 'users[]',
                                hiddenName: 'users[]',
                                anchor: '100%'
                            }, {
                                fieldLabel: _('bbbx.emails'),
                                xtype: 'bbbx-combo-email',
                                name: 'emails[]',
                                hiddenName: 'emails[]',
                                anchor: '100%'
                            }
                        ]
                    }, {
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        items: [
                            {
                                fieldLabel: _('bbbx.send_now'),
                                xtype: 'radiogroup',
                                preventRender: true,
                                name: 'send_now',
                                anchor: '100%',
                                items: [
                                    {boxLabel: _('bbbx.now'), name: 'send_now', inputValue: 1},
                                    {boxLabel: _('bbbx.later'), name: 'send_now', inputValue: 0}
                                ]
                            }, {
                                fieldLabel: _('bbbx.status'),
                                boxLabel: _('bbbx.sent'),
                                xtype: 'xcheckbox',
                                name: 'is_sent',
                                readOnly: true,
                                disabled: true
                            }
                        ]
                    }
                ]
            }
        ]
    });
    BBBx.window.Notify.superclass.constructor.call(this, config);
};
Ext.extend(BBBx.window.Notify, MODx.Window);
Ext.reg('bbbx-window-notify', BBBx.window.Notify);
