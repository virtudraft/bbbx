BBBx.window.Join = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        // provided by triggers
        title: _('bbbx.join_create'),
        baseParams: {
            action: 'mgr/joins/create'
        },
        url: BBBx.config.connectorUrl,
        width: 300,
        autoScroll: true,
//        fileUpload: true,
        allowDrop: false,
        fields: [
            {
                xtype: 'hidden',
                name: 'id'
            }, {
                fieldLabel: _('bbbx.meeting'),
                xtype: 'bbbx-combo-meeting',
                name: 'meeting_id',
                hiddenName: 'meeting_id',
                anchor: '100%',
                allowBlank: false
            }, {
                fieldLabel: _('class_key'),
                xtype: 'textfield',
                name: 'classkey',
                anchor: '100%',
                allowBlank: false
            }, {
                fieldLabel: _('id'),
                xtype: 'numberfield',
                name: 'object_id',
                anchor: '100%',
                allowBlank: false
            }
        ]
    });
    BBBx.window.Join.superclass.constructor.call(this, config);
};
Ext.extend(BBBx.window.Join, MODx.Window);
Ext.reg('bbbx-window-join', BBBx.window.Join);
