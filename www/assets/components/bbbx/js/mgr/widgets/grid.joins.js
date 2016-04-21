BBBx.grid.Joins = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'bbbx-grid-joins',
        url: BBBx.config.connectorUrl,
        baseParams: {
            action: 'mgr/joins/getList'
        },
        fields: ['id', 'meeting_id', 'meeting_name', 'classkey', 'object_id'],
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
                header: _('bbbx.meeting_id'),
                dataIndex: 'meeting_id',
                sortable: true,
                width: 100,
                fixed: true,
                hidden: true,
            }, {
                header: _('bbbx.meeting_name'),
                dataIndex: 'meeting_name',
                sortable: true
            }, {
                header: _('class_key'),
                dataIndex: 'classkey',
                width: 60
            }, {
                header: _('id'),
                dataIndex: 'object_id',
                width: 60
            }
        ],
        tbar: [
            {
                text: _('bbbx.create'),
                handler: this.createJoin,
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
    BBBx.grid.Joins.superclass.constructor.call(this, config);

    this.on('click', this.handleButtons, this);
};
Ext.extend(BBBx.grid.Joins, MODx.grid.Grid, {
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
            handler: this.updateJoin
        });
        menu.push('-');
        menu.push({
            text: _('bbbx.remove'),
            handler: this.removeJoin
        });
        return menu;
    },
    updateJoin: function () {
        var p = this.menu.record || {};
        var win = MODx.load({
            xtype: 'bbbx-window-join',
            title: _('bbbx.join_update'),
            baseParams: {
                action: 'mgr/joins/update'
            },
            listeners: {
                success: {
                    fn: function(){this.refresh()},
                    scope: this
                },
                failure: function() {}
            }
        });

        win.reset();
        win.setValues(p);
        win.show();
    },
    removeJoin: function () {
        var p = this.menu.record || {};
        p['action'] = 'mgr/joins/remove';
        MODx.msg.confirm({
            title: _('bbbx.remove'),
            text: _('bbbx.join_remove_confirm'),
            url: BBBx.config.connectorUrl,
            params: p,
            listeners: {
                'success': {
                    fn: function () {
                        this.refresh();
                    },
                    scope: this
                }
            }
        });
    },
    createJoin: function () {
        var win = MODx.load({
            xtype: 'bbbx-window-join',
            listeners: {
                success: this.refresh,
                failure: function() {}
            }
        });

        win.reset();
        win.show();
    }
});
Ext.reg('bbbx-grid-joins', BBBx.grid.Joins);