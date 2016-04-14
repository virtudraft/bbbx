BBBx.grid.ScheduledMeetings = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'bbbx-grid-scheduledmeetings',
        url: BBBx.config.connectorUrl,
        baseParams: {
            action: 'mgr/meetings/scheduled/getList'
        },
        fields: ['id', 'meeting_id', 'name', 'description', 'attendee_pw',
            'moderator_pw', 'welcome', 'dial_number', 'voice_bridge',
            'web_voice', 'logout_url', 'is_moderator_first', 'max_participants',
            {name: 'started_on', type: 'date', dateFormat: 'U'}, 'started_date', 'started_time',
            {name: 'ended_on', type: 'date', dateFormat: 'U'}, 'ended_date', 'ended_time',
            'is_forced_to_end', 'is_canceled',
            'is_recorded', 'duration', 'meta', 'moderator_only_message',
            'auto_start_recording', 'allow_start_stop_recording', 'document_url',
            'created_on', 'created_by', 'edited_on', 'edited_by',
            'context_key', 'moderator_usergroups', 'viewer_usergroups',
            'moderator_users', 'viewer_users', 'is_created', 'is_running',
            'joinURL', 'recordings'
        ],
        paging: true,
        remoteSort: true,
//        anchor: '97%',
        autoExpandColumn: 'name',
        columns: [{
                header: _('id'),
                dataIndex: 'meeting_id',
                sortable: true,
                width: 100,
                hidden: true,
            }, {
                header: _('name'),
                dataIndex: 'name',
                sortable: true,
                renderer: this.renderName
            }, {
                header: _('bbbx.attendeePW'),
                dataIndex: 'attendee_pw',
                sortable: true,
//                width: 100
                hidden: true
            }, {
                header: _('bbbx.moderatorPW'),
                dataIndex: 'moderator_pw',
                sortable: true,
//                width: 100
                hidden: true
            }, {
                header: _('bbbx.started_on'),
                dataIndex: 'started_on',
                xtype: 'datecolumn',
                format: 'M d,Y g:i A',
                sortable: true,
                width: 150,
                fixed: true
            }, {
                header: _('bbbx.ended_on'),
                dataIndex: 'ended_on',
                xtype: 'datecolumn',
                format: 'M d,Y g:i A',
                sortable: true,
                width: 150,
                fixed: true
            }, {
                header: _('bbbx.running'),
                dataIndex: 'is_running',
                sortable: true,
                width: 80,
                fixed: true
            }, {
                header: _('bbbx.recorded'),
                dataIndex: 'is_recorded',
                renderer: function (value, record) {
                    if (value === 1) {
                        return _('yes');
                    } else {
                        return _('no');
                    }
                },
                sortable: true,
                width: 90,
                fixed: true
            }, {
                xtype: 'actioncolumn',
                header: _('bbbx.recordings'),
                dataIndex: 'recordings',
                items: [
                    {
                        handler: function (grid, row, col) {
                            var rec = grid.store.getAt(row);
                            if (rec.data.recordings && rec.data.recordings.length) {
                                grid.getRecordings(rec.data.meeting_id);
                            }
                        },
                        getClass: function (v, meta, rec) {
                            if (rec.data.recordings && rec.data.recordings.length) {
                                this.items[0].tooltip = _('bbbx.recordings');
                                this.items[0].altText = _('bbbx.recordings');
                                return 'icon-bbbx-recordings icon-bbbx-actioncolumn-img';
                            }
                        }
                    }
                ],
                sortable: true,
                width: 100,
                fixed: true
            }
        ],
        tbar: [
            {
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
            }, {
                text: _('bbbx.meeting_create'),
                handler: this.createMeeting,
                scope: this
            }, {
                text: _('bbbx.refresh'),
                handler: this.refresh,
                scope: this
            }
        ]

    });
    BBBx.grid.ScheduledMeetings.superclass.constructor.call(this, config);

    this.on('click', this.handleButtons, this);
};
Ext.extend(BBBx.grid.ScheduledMeetings, MODx.grid.Grid, {
    search: function (tf, nv, ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    getMenu: function () {
        var r = this.menu.record || {};
        var menu = [];
        menu.push({
            text: _('bbbx.meeting_update'),
            handler: this.updateMeeting
        });

        var startedOn = new Date(r.started_on).getTime();
        var endedOn = new Date(r.ended_on).getTime();
        var now = new Date().getTime();

        if (r.is_created) {
            menu.push({
                text: _('bbbx.meeting_join'),
                handler: this.joinMeeting
            });
        } else if (startedOn <= now && endedOn >= now) {
            menu.push({
                text: _('bbbx.meeting_start'),
                handler: this.startMeeting
            });
        }
        if (r.is_running) {
            menu.push('-', {
                text: _('bbbx.meeting_end'),
                handler: this.endMeeting
            });
        } else {
            menu.push('-', {
                text: _('bbbx.meeting_remove'),
                handler: this.removeMeeting
            });
        }

        return menu;
    },
    removeMeeting: function () {
        var p = this.menu.record || {};
        p['action'] = 'mgr/meetings/scheduled/remove';
        MODx.msg.confirm({
            title: _('bbbx.meeting_remove'),
            text: _('bbbx.meeting_remove_confirm'),
            url: BBBx.config.connectorUrl,
            params: p,
            listeners: {
                'success': {fn: this.refresh, scope: this}
            }
        });
    },
    updateMeeting: function () {
        var r = this.menu.record || {};
        var meetingWindow = MODx.load({
            xtype: 'bbbx-window-scheduledmeeting',
            title: _('bbbx.meeting_update'),
            baseParams: {
                action: 'mgr/meetings/scheduled/update',
                id: r.id
            },
            listeners: {
                'success': {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
        meetingWindow.reset();
        meetingWindow.setValues(r);
        // SuperBoxSelect
        var sb;
        sb = meetingWindow.fp.getForm().findField('moderator_usergroups[]');
        sb.setValue(r.moderator_usergroups);
        sb = meetingWindow.fp.getForm().findField('viewer_usergroups[]');
        sb.setValue(r.viewer_usergroups);
        sb = meetingWindow.fp.getForm().findField('moderator_users[]');
        sb.setValue(r.moderator_users);
        sb = meetingWindow.fp.getForm().findField('viewer_users[]');
        sb.setValue(r.viewer_users);
        sb = meetingWindow.fp.getForm().findField('context_key[]');
        sb.setValue(r.context_key);

        meetingWindow.show();
    },
    createMeeting: function () {
        var meetingWindow = MODx.load({
            xtype: 'bbbx-window-scheduledmeeting',
            title: _('bbbx.meeting_create'),
            baseParams: {
                action: 'mgr/meetings/scheduled/create'
            },
            listeners: {
                'success': {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
        meetingWindow.reset();

        // SuperBoxSelect
        var sb;
        sb = meetingWindow.fp.getForm().findField('moderator_usergroups[]');
        sb.setValue(BBBx.config.default.moderator);

        meetingWindow.show();
    },
    joinMeeting: function () {
        var p = this.menu.record || {};
        p['action'] = 'mgr/meetings/scheduled/join';
        MODx.msg.confirm({
            title: _('bbbx.meeting_join'),
            text: _('bbbx.meeting_join_confirm'),
            url: BBBx.config.connectorUrl,
            params: p,
            listeners: {
                'success': {
                    fn: function (res) {
                        var href;
                        if (res.success &&
                                typeof (res.object) !== 'undefined' &&
                                typeof (res.object.href) !== 'undefined' &&
                                res.object.href !== ''
                                ) {
                            href = res.object.href;
                        }
                        if (href) {
                            var win = window.open(href, '_blank');
                            if (win) {
                                win.focus();
                            } else {
                                alert('Please allow popups for this site');
                            }
                        }
                    },
                    scope: this
                }
            }
        });
    },
    startMeeting: function () {
        var p = this.menu.record || {};
        p['action'] = 'mgr/meetings/scheduled/start';
        MODx.msg.confirm({
            title: _('bbbx.meeting_start'),
            text: _('bbbx.meeting_start_confirm'),
            url: BBBx.config.connectorUrl,
            params: p,
            listeners: {
                'success': {fn: this.refresh, scope: this}
            }
        });
    },
    endMeeting: function () {
        var r = this.menu.record || {};
        MODx.msg.confirm({
            title: _('bbbx.meeting_end'),
            text: _('bbbx.meeting_end_confirm'),
            url: BBBx.config.connectorUrl,
            params: {
                action: 'mgr/meetings/running/end',
                meetingID: r.meeting_id,
                moderatorPW: r.moderator_pw
            },
            listeners: {
                'success': {fn: this.refresh, scope: this}
            }
        });
    },
    renderName: function (value, panel, record) {
        var html = '<table border="0">' +
                '<tr><td>meetingName</td><td>: {name}</td></tr>' +
                '<tr><td>meetingID</td><td>: {meeting_id}</td></tr>' +
                '<tr><td>attendeePW</td><td>: {attendee_pw}</td></tr>' +
                '<tr><td>moderatorPW</td><td>: {moderator_pw}</td></tr>' +
                '<tr><td>contexts</td><td>: {context_key}</td></tr>' +
                '<tr><td></td><td>';
        if (record.data.is_running) {
            html += '<a href="{joinURL}" target="_blank" class="x-btn x-btn-small bbbx-action-btn">Join</a>' +
                    '<a href="javascript:void(0);" class="x-btn x-btn-small bbbx-btn-danger bbbx-action-btn bbbx-btn-end" data-meetingid="{meeting_id}" data-moderatorpw="{moderator_pw}">End</a>';
        }
        html += '</td></tr>' +
                '</table>';
        var tpl = new Ext.XTemplate(html, {compiled: true});
        return tpl.apply(record.data);
    },
    getRecordings: function(meetingId) {
        var tabs = Ext.getCmp('bbbx-tabs-scheduledmeetings');
        if (tabs) {
            var check = Ext.getCmp('bbbx-recordings-' + meetingId);
            if (check) {
                return tabs.setActiveTab(check);
            }
            var newTab = MODx.load({
                xtype: 'bbbx-grid-recordings',
                title: _('bbbx.recordings'),
                id: 'bbbx-recordings-' + meetingId,
                record: {
                    meetingId: meetingId
                },
                closable: true
            });
            tabs.add(newTab);
            tabs.setActiveTab(newTab);
            tabs.doLayout();
        }
    },
    handleButtons: function (e) {
        var t = e.getTarget();
        var action = null, classes, record = {};
        classes = t.className.split(' ');
        var actBtn = classes.indexOf('bbbx-action-btn');
        if (actBtn > 0) {
            action = classes[actBtn + 1];
        }

        if (action) {
            if (typeof (t.dataset.meetingid) !== 'undefined') {
                record['meetingID'] = t.dataset.meetingid;
            }
            if (typeof (t.dataset.meetingid) !== 'undefined') {
                record['moderatorPW'] = t.dataset.moderatorpw;
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
                case 'bbbx-btn-join':
                    this.joinMeeting();
                    break;
                case 'bbbx-btn-end':
                    this.endMeeting();
                    break;
                default:
                    break;
            }
        }
    }
});
Ext.reg('bbbx-grid-scheduledmeetings', BBBx.grid.ScheduledMeetings);