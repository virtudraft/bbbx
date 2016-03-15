BBBx.grid.Configs = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'bbbx-grid-configs',
        url: BBBx.config.connectorUrl,
        baseParams: {
            action: 'mgr/configs/getList'
        },
        fields: ['id', 'name', 'description'],
        paging: true,
        remoteSort: true,
//        anchor: '97%',
        autoExpandColumn: 'name',
        columns: [{
                header: _('id'),
                dataIndex: 'id',
                sortable: true,
                width: 100,
                fixed: true,
                hidden: true,
            }, {
                header: _('name'),
                dataIndex: 'name',
                sortable: true,
                renderer: this.renderName,
            }
        ],
        tbar: [
            {
                text: _('bbbx.create'),
                handler: this.createConfig,
                scope: this
            }, {
                xtype: 'textfield',
                emptyText: _('bbbx.search...'),
                listeners: {
                    'change': {
                        fn: this.search,
                        scope: this
                    },
                    'render': {
                        fn: function (cmp) {
                            new Ext.KeyMap(cmp.getEl(), {
                                key: Ext.EventObject.ENTER,
                                fn: function () {
                                    this.fireEvent('change', this);
                                    this.blur();
                                    return true;
                                },
                                scope: cmp
                            });
                        },
                        scope: this
                    }
                }
            }
        ]

    });
    BBBx.grid.Configs.superclass.constructor.call(this, config);

    this.on('click', this.handleButtons, this);
};
Ext.extend(BBBx.grid.Configs, MODx.grid.Grid, {
    search: function (tf, nv, ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    getMenu: function () {
        var p = this.menu.record || {};
        var menu = [];
        menu.push({
            text: _('bbbx.update'),
            handler: this.updateConfig
        });
        menu.push('-');
        menu.push({
            text: _('bbbx.remove'),
            handler: this.removeConfig
        });
        return menu;
    },
    renderName: function (value, panel, record) {
        var tpl = new Ext.XTemplate(
                '<h4>{name}</h4>' +
                '<div class="desc-under">{description}</div>'
                , {compiled: true});
        return tpl.apply(record.data);
    },
    updateConfig: function () {
        var p = this.menu.record || {};
        var tabs = Ext.getCmp('bbbx-configurations-tabs');
        var confPanel = Ext.getCmp('bbbx-panel-configs-' + p.id);
        if (typeof(confPanel) === 'undefined') {
            MODx.Ajax.request({
                url: BBBx.config.connectorUrl,
                params: {
                    action: 'mgr/configs/get',
                    id: p.id
                },
                listeners: {
                    'success': {
                        fn: function (res) {
                            confPanel = MODx.load({
                                xtype: 'bbbx-panel-configs',
                                title: _('bbbx.update_config'),
                                id: 'bbbx-panel-configs-' + p.id,
                                baseParams: {
                                    action: 'mgr/configs/update',
                                    id: p.id
                                },
                                record: res.object,
                                closable: true
                            });
                            confPanel.getForm().setValues(res.object);
                            confPanel.on('success', function (data) {
                                if (data.result.success === true) {
                                    this.refresh();
                                }
                            }, this);
                            tabs.add(confPanel);
                            tabs.setActiveTab(confPanel);
                        },
                        scope: this
                    }
                }
            });
        } else {
            tabs.setActiveTab(confPanel);
        }
    },
    removeConfig: function () {
        var p = this.menu.record || {};
        p['action'] = 'mgr/configs/remove';
        MODx.msg.confirm({
            title: _('bbbx.remove'),
            text: _('bbbx.config_remove_confirm'),
            url: BBBx.config.connectorUrl,
            params: p,
            listeners: {
                'success': {
                    fn: function() {
                        this.refresh();
                        var tab = Ext.getCmp('bbbx-panel-configs-' + p.id);
                        if (typeof(tab) !== 'undefined') {
                            tab.destroy();
                        }
                    },
                    scope: this
                }
            }
        });
    },
    createConfig: function () {
        var tabs = Ext.getCmp('bbbx-configurations-tabs');
        var confPanel = MODx.load({
            xtype: 'bbbx-panel-configs',
            title: _('bbbx.new_config'),
            closable: true
        });
        confPanel.on('success', function(data) {
            if (data.result.success === true) {
                this.refresh();
                this.menu.record = {};
                this.menu.record.id = data.result.object.id;
                tabs.remove(confPanel);
                this.updateConfig();
            }
        }, this);
        tabs.add(confPanel);
        tabs.setActiveTab(confPanel);
    }
});
Ext.reg('bbbx-grid-configs', BBBx.grid.Configs);