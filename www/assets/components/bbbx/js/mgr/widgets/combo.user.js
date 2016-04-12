BBBx.combo.User = function (config) {
    config = config || {};

    var store = new Ext.data.JsonStore({
        url: BBBx.config.connectorUrl,
        totalProperty: 'total',
        root: 'results',
        baseParams: {
            action: 'mgr/users/getlist'
        },
        autoLoad: true,
        autoSave: false,
        dir: 'ASC',
        fields: ['id', 'username']
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
        displayField: "username",
        valueField: "id",
        queryDelay: 1000,
        resizable: true,
        hideTrigger: true,
        allowBlank: true,
        listWidth: 200,
        maxHeight: 300,
        typeAhead: true,
        typeAheadDelay: 250,
        editable: true,
//        listEmptyText: _('bbbx.listEmptyText'),
        autoSelect: false,
        forceSelection: false,
        stackItems: false,
        msgTarget: 'under',
        forceFormValue: false
    });
    BBBx.combo.User.superclass.constructor.call(this, config);
};
Ext.extend(BBBx.combo.User, Ext.ux.form.SuperBoxSelect);
Ext.reg('bbbx-combo-user', BBBx.combo.User);