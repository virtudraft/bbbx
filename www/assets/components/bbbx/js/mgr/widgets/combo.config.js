BBBx.combo.Config = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        url: BBBx.config.connectorUrl,
        baseParams: {
            action: 'mgr/configs/getList',
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
    BBBx.combo.Config.superclass.constructor.call(this, config);
};
Ext.extend(BBBx.combo.Config, MODx.combo.ComboBox);
Ext.reg('bbbx-combo-config', BBBx.combo.Config);