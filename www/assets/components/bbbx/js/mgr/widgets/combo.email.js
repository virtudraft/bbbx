BBBx.combo.Email = function (config) {
    config = config || {};

    var store = new Ext.data.JsonStore({
        url: BBBx.config.connectorUrl,
        totalProperty: 'total',
        root: 'results',
        baseParams: {
            action: 'mgr/emails/getlist'
        },
        autoLoad: true,
        autoSave: false,
        dir: 'ASC',
        fields: ['email']
    });
    Ext.applyIf(config, {
        triggerAction: 'all',
        mode: 'remote',
        store: store,
        pageSize: 20,
        minChars: 1,
        allowAddNewData: true,
        addNewDataOnBlur: true,
//        value: config.record.value,
//        originalValue: config.record.value,
        valueDelimiter: ",",
        queryValuesDelimiter: ",",
        extraItemCls: 'x-tag',
        width: 400,
        displayField: "email",
        valueField: "email",
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
        forceFormValue: false,
        listeners: {
            'newitem': {
                fn: function (bs, v, f) {
                    bs.addNewItem({"email": v});
                    return true;
                },
                scope: this
            }
        }
    });
    BBBx.combo.Email.superclass.constructor.call(this, config);
};
Ext.extend(BBBx.combo.Email, Ext.ux.form.SuperBoxSelect);
Ext.reg('bbbx-combo-email', BBBx.combo.Email);