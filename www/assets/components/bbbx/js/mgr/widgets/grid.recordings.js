BBBx.grid.Recordings = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'bbbx-grid-recordings',
        url: BBBx.config.connectorUrl,
        baseParams: {
            action: 'mgr/recordings/getList'
        },
        fields: ['recordID', 'meetingID', 'name', 'published', 'state',
            'startTime', 'endTime', 'metadata', 'playbackURL'
        ],
//        paging: true,
//        remoteSort: true,
//        anchor: '97%',
        autoExpandColumn: 'meetingName',
        columns: [{
                header: _('id'),
                dataIndex: 'recordID',
                sortable: true,
                width: 100,
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
//                xtype: 'textfield',
//                emptyText: _('bbbx.search...'),
//                listeners: {
//                    'change': {
//                        fn: this.search,
//                        scope: this
//                    },
//                    'render': {
//                        fn: function (cmp) {
//                            new Ext.KeyMap(cmp.getEl(), {
//                                key: Ext.EventObject.ENTER,
//                                fn: function () {
//                                    this.fireEvent('change', this);
//                                    this.blur();
//                                    return true;
//                                },
//                                scope: cmp
//                            });
//                        },
//                        scope: this
//                    }
//                }
//            }, {
                text: _('bbbx.refresh'),
                handler: this.refresh,
                scope: this
            }
        ]

    });
    BBBx.grid.Recordings.superclass.constructor.call(this, config);

    this.on('click', this.handleButtons, this);
};
Ext.extend(BBBx.grid.Recordings, MODx.grid.Grid, {
    search: function (tf, nv, ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    getMenu: function () {
        var p = this.menu.record || {};
        var menu = [];
        if (p['state'] === 'published') {
            menu.push({
                text: _('bbbx.unpublish'),
                handler: function() {
                    this.publishRecording(false);
                },
                scope: this
            });
        } else if (p['state'] === 'unpublished') {
            menu.push({
                text: _('bbbx.publish'),
                handler: function() {
                    this.publishRecording(true);
                },
                scope: this
            });
        }
        menu.push('-');
        menu.push({
            text: _('bbbx.recording_remove'),
            handler: this.removeRecording
        });
        return menu;
    },
    removeRecording: function () {
        var p = this.menu.record || {};
        p['action'] = 'mgr/recordings/remove';
        MODx.msg.confirm({
            title: _('bbbx.recording_remove'),
            text: _('bbbx.recording_remove_confirm'),
            url: BBBx.config.connectorUrl,
            params: p,
            listeners: {
                'success': {fn: this.refresh, scope: this}
            }
        });
    },
    publishRecording: function (op) {
        var p = this.menu.record || {};
        p['action'] = 'mgr/recordings/publish';
        p['published'] = op || false;
        MODx.msg.confirm({
            title: op ? _('bbbx.publish') : _('bbbx.unpublish'),
            text: op ? _('bbbx.recording_publish_confirm') : _('bbbx.recording_unpublish_confirm'),
            url: BBBx.config.connectorUrl,
            params: p,
            listeners: {
                'success': {fn: this.refresh, scope: this}
            }
        });
    },
    renderName: function (value, panel, record) {
        var html = '<table border="0">' +
                '<tr><td>name</td><td>: {name}</td></tr>' +
                '<tr><td>recordID</td><td>: {recordID}</td></tr>' +
                '<tr><td>meetingID</td><td>: {meetingID}</td></tr>' +
                '<tr><td>published</td><td>: {published}</td></tr>' +
                '<tr><td>state</td><td>: {state}</td></tr>' +
                '<tr><td>startTime</td><td>: {startTime}</td></tr>' +
                '<tr><td>endTime</td><td>: {endTime}</td></tr>' +
                '<tr><td>metadata</td><td>: {metadata}</td></tr>' +
                '<tr><td></td><td>' +
                '<a href="{playbackURL}" target="_blank" class="x-btn x-btn-small bbbx-action-btn bbbx-action-play">' + _('bbbx.play') + '</a>';
        if (record.data.state === 'published') {
            html += '<a href="javascript:void(0);" class="x-btn x-btn-small bbbx-action-btn bbbx-action-unpublish" data-recordid="{recordID}">' + _('bbbx.unpublish') + '</a>';

        } else if (record.data.state === 'unpublished') {
            html += '<a href="javascript:void(0);" class="x-btn x-btn-small bbbx-action-btn bbbx-action-publish" data-recordid="{recordID}">' + _('bbbx.publish') + '</a>';

        }
        html += '<a href="javascript:void(0);" class="x-btn x-btn-small bbbx-btn-danger bbbx-action-btn bbbx-action-delete" data-recordid="{recordID}">' + _('bbbx.delete') + '</a>' +
                '</td></tr>' +
                '</table>';
        var tpl = new Ext.XTemplate(html, {compiled: true});
        return tpl.apply(record.data);
    },
    handleButtons: function(e){
        var t = e.getTarget();
        var action = null, classes, record = {};
        classes = t.className.split(' ');
        var actBtn = classes.indexOf('bbbx-action-btn');
        if (actBtn > 0) {
            action = classes[actBtn + 1];
        }

        if(action) {
            if (typeof(t.dataset.recordid) !== 'undefined') {
                record['recordID'] = t.dataset.recordid;
            }

            var size = 0, key;
            for (key in record) {
                if (record.hasOwnProperty(key))
                    size++;
            }
            if (!size) {
                return;
            }
            this.menu.record = record;

            switch (action) {
                case 'bbbx-action-delete':
                    this.removeRecording();
                    break;
                case 'bbbx-action-publish':
                    this.publishRecording(true);
                    break;
                case 'bbbx-action-unpublish':
                    this.publishRecording(false);
                    break;
                default:
                    break;
            }
        }
    }
});
Ext.reg('bbbx-grid-recordings', BBBx.grid.Recordings);