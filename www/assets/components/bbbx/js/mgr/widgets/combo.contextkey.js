BBBx.combo.ContextKey = function (config) {
    config = config || {};

    var store = new Ext.data.JsonStore({
        url: BBBx.config.connectorUrl,
        totalProperty: 'total',
        root: 'results',
        baseParams: {
            action: 'mgr/contexts/getlist',
            exclude: 'mgr',
            combo: true
        },
        autoLoad: true,
        autoSave: false,
        dir: 'ASC',
        fields: ['key', 'name']
    });
    Ext.applyIf(config, {
        triggerAction: 'all',
        mode: 'remote',
        store: store,
        pageSize: 20,
        minChars: 1,
        allowAddNewData: false,
        addNewDataOnBlur: false,
//        value: config.record.value,
//        originalValue: config.record.value,
        valueDelimiter: ",",
        queryValuesDelimiter: ",",
        extraItemCls: 'x-tag',
        width: 400,
        displayField: "name",
        valueField: "key",
        queryDelay: 1000,
        resizable: true,
        hideTrigger: true,
        allowBlank: true,
        listWidth: 200,
        maxHeight: 300,
        typeAhead: true,
        typeAheadDelay: 250,
        editable: true,
        autoSelect: false,
        forceSelection: false,
        stackItems: false,
        msgTarget: 'under',
        forceFormValue: false
    });
    BBBx.combo.ContextKey.superclass.constructor.call(this, config);
};
Ext.extend(BBBx.combo.ContextKey, Ext.ux.form.SuperBoxSelect);
Ext.reg('bbbx-combo-contextkey', BBBx.combo.ContextKey);