BBBx.combo.Meeting = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        url: BBBx.config.connectorUrl,
        baseParams: {
            action: 'mgr/meetings/scheduled/getList',
            combo: true
        },
        autoSelect: true,
        hiddenValue: 'id',
        listeners: {
            select: function (comp, record, index) {
                if (comp.getValue() == "" || comp.getValue() == "&nbsp;") {
                    comp.setValue(null);
                }
            }
        }
    });
    BBBx.combo.Meeting.superclass.constructor.call(this, config);
};
Ext.extend(BBBx.combo.Meeting, MODx.combo.ComboBox);
Ext.reg('bbbx-combo-meeting', BBBx.combo.Meeting);