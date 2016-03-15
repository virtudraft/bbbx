BBBx.panel.Configs = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        id: 'bbbx-panel-configs',
        url: BBBx.config.connectorUrl,
        baseParams: {
            action: 'mgr/configs/create'
        },
        border: false,
        labelAlign: 'left',
        labelWidth: 60,
        cls: 'container',
        items: [
            {
                html: '<p>' + _('bbbx.new_config_desc') + _('bbbx.name_is_required') + '</p>',
                bodyCssClass: 'panel-desc'
            }
        ],
        tbar: [
            {
                text: _('bbbx.expand_all'),
                handler: this.expand,
                scope: this
            }, {
                text: _('bbbx.collapse_all'),
                handler: this.collapse,
                scope: this
            }, '->', {
                text: _('save'),
                handler: this.submit,
                scope: this
            }
        ],
        bbar: [
            {
                text: _('bbbx.expand_all'),
                handler: this.expand,
                scope: this
            }, {
                text: _('bbbx.collapse_all'),
                handler: this.collapse,
                scope: this
            }, '->', {
                text: _('save'),
                handler: this.submit,
                scope: this
            }
        ]
    });
    BBBx.panel.Configs.superclass.constructor.call(this, config);
    this.on('beforerender', this.beforeRender, this);
};
Ext.extend(BBBx.panel.Configs, MODx.FormPanel, {
    beforeRender: function (fp) {
        if (!this.pageMask) {
            this.pageMask = new Ext.LoadMask(Ext.getBody(), {
                msg: _('bbbx.please_wait')
            });
        }
        this.pageMask.show();

        MODx.Ajax.request({
            url: BBBx.config.connectorUrl,
            params: {
                action: 'mgr/configs/getdefault'
            },
            listeners: {
                'success': {
                    fn: function (response) {
                        this.pageMask.hide();
                        if (response.success) {
                            this.renderForm(response.object);
                        }
                    },
                    scope: this
                },
                'failure': {
                    fn: function () {
                        return this.pageMask.hide();
                    },
                    scope: this
                }
            }
        });

        return true;
    },
    renderForm: function (data) {
        this.add({
            xtype: 'hidden',
            name: 'default',
            value: data.default
        });
        var nameValue = '';
        if (this.record && this.record.name) {
            nameValue = this.record.name;
        }
        var descValue = '';
        if (this.record && this.record.description) {
            descValue = this.record.description;
        }
        this.add({
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
                            fieldLabel: _('bbbx.name'),
                            name: 'name',
                            value: nameValue,
                            allowBlank: false,
                            anchor: '100%'
                        }, {
                            xtype: 'textarea',
                            fieldLabel: _('bbbx.description'),
                            name: 'description',
                            value: descValue,
                            grow: true,
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
                            fieldLabel: 'localeversion',
                            name: 'localeversion',
                            anchor: '100%',
                            value: data['localeversion'],
                            disabled: true
                        }, {
                            xtype: 'textfield',
                            fieldLabel: 'version',
                            name: 'version',
                            anchor: '100%',
                            value: data['version'],
                            disabled: true
                        }
                    ]
                }
            ]
        });
        for (var key in data) {
            var items = [];
            if (!data.hasOwnProperty(key)) {
                continue;
            }
            if (key === 'default' ||
                    key === 'localeversion' ||
                    key === 'version' ||
                    key === 'modules'
                    ) {
                continue;
            }
            if (data[key]['@attributes']) {
                for (var label in data[key]['@attributes']) {
                    var value = '';
                    if (this.record && this.record[key] &&
                            this.record[key]['@attributes'] &&
                            this.record[key]['@attributes'][label]
                            ) {
                        value = this.record[key]['@attributes'][label];
                    }
                    items.push({
                        fieldLabel: label,
                        name: 'configs[' + key + '][@attributes][' + label + ']',
                        anchor: '100%',
                        value: value
                    }, {
                        xtype: 'displayfield',
                        cls: 'desc-under',
                        html: data[key]['@attributes'][label],
                    });
                }
            }

            this.add({
                xtype: 'fieldset',
                title: key,
                collapsible: true,
                collapsed: true,
                autoHeight: true,
                labelWidth: 250,
                defaultType: 'textfield',
                items: items
            });
        }
        var tabItems = [];
        Ext.each(data['modules']['module'], function (item, index, allItems) {
            if (!item['@attributes']) {
                return;
            }
            var tabFields = [];
            for (var key in item['@attributes']) {
                if (key === 'name') {
                    tabFields.push({
                        xtype: 'hidden',
                        name: 'configs[modules][' + item['@attributes']['name'] + '][@attributes][' + key + ']',
                        value: item['@attributes'][key]
                    });
                    continue;
                }
                var value = '';
                if (this.record &&
                        this.record['modules'] &&
                        this.record['modules'][item['@attributes']['name']] &&
                        this.record['modules'][item['@attributes']['name']]['@attributes'] &&
                        this.record['modules'][item['@attributes']['name']]['@attributes'][key]
                        ) {
                    value = this.record['modules'][item['@attributes']['name']]['@attributes'][key];
                }
                tabFields.push({
                    fieldLabel: key,
                    name: 'configs[modules][' + item['@attributes']['name'] + '][@attributes][' + key + ']',
                    anchor: '100%',
                    value: value
                }, {
                    xtype: 'displayfield',
                    cls: 'desc-under',
                    html: item['@attributes'][key],
                });
            }
            tabItems.push({
                title: item['@attributes']['name'],
                layout: 'form',
                labelWidth: 250,
                defaultType: 'textfield',
                items: tabFields
            });
        }, this);
        this.add({
            xtype: 'fieldset',
            title: 'modules',
            collapsible: true,
            collapsed: true,
            autoHeight: true,
            labelWidth: 250,
            defaultType: 'textfield',
            items: [{
                    xtype: 'tabpanel',
                    enableTabScroll: true,
                    title: 'modules',
                    plain: true,
                    activeTab: 0,
                    height: 235,
                    deferredRender: false,
                    defaults: {
                        bodyStyle: 'padding:10px'
                    },
                    items: tabItems
                }]
        });

        this.doLayout();
    },
    expand: function () {
        var fieldSets = this.findByType('fieldset');
        if (!fieldSets.length) {
            return;
        }
        for (var i = 0; i < fieldSets.length; ++i) {
            fieldSets[i].expand();
        }
    },
    collapse: function () {
        var fieldSets = this.findByType('fieldset');
        if (!fieldSets.length) {
            return;
        }
        for (var i = 0; i < fieldSets.length; ++i) {
            fieldSets[i].collapse();
        }
    }
});
Ext.reg('bbbx-panel-configs', BBBx.panel.Configs);