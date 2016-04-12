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
            'started_on', 'started_date', 'started_time',
            'ended_on', 'ended_date', 'ended_time',
            'is_forced_to_end', 'is_canceled',
            'is_recorded', 'duration', 'meta', 'moderator_only_message',
            'auto_start_recording', 'allow_start_stop_recording', 'document_url',
            'created_on', 'created_by', 'edited_on', 'edited_by',
            'context_key', 'usergroups', 'users'
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
                hidden: true,
            }, {
                header: _('bbbx.moderatorPW'),
                dataIndex: 'moderator_pw',
                sortable: true,
//                width: 100
                hidden: true
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
        return [{
                text: _('bbbx.meeting_update')
                , handler: this.updateMeeting
            }, {
                text: _('bbbx.meeting_join')
                , handler: this.joinMeeting
            }, '-', {
                text: _('bbbx.meeting_end')
                , handler: this.endMeeting
            }];
    },
    updateMeeting: function() {
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
        var ug = meetingWindow.fp.getForm().findField('usergroups[]');
        ug.setValue(r.usergroups);
        var us = meetingWindow.fp.getForm().findField('users[]');
        us.setValue(r.users);
        var ck = meetingWindow.fp.getForm().findField('context_key[]');
        ck.setValue(r.context_key);

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
                    fn: function(res){
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
    endMeeting: function () {
        var p = this.menu.record || {};
        p['action'] = 'mgr/meetings/scheduled/end';
        MODx.msg.confirm({
            title: _('bbbx.meeting_end'),
            text: _('bbbx.meeting_end_confirm'),
            url: BBBx.config.connectorUrl,
            params: p,
            listeners: {
                'success': {fn: this.refresh, scope: this}
            }
        });
    },
    renderName: function (value, panel, record) {
        var tpl = new Ext.XTemplate(
                '<table border="0">' +
                '<tr><td>meetingName</td><td>: {name}</td></tr>' +
                '<tr><td>meetingID</td><td>: {meeting_id}</td></tr>' +
                '<tr><td>attendeePW</td><td>: {attendee_pw}</td></tr>' +
                '<tr><td>moderatorPW</td><td>: {moderator_pw}</td></tr>' +
                '<tr><td></td><td>' +
                '<a href="{joinURL}" target="_blank" class="x-btn x-btn-small bbbx-action-btn">Join</a>' +
                '<a href="javascript:void(0);" class="x-btn x-btn-small bbbx-btn-danger bbbx-action-btn bbbx-btn-end" data-meetingid="{meeting_id}" data-moderatorpw="{moderator_pw}">End</a>' +
                '</td></tr>' +
                '</table>'
                , {compiled: true});
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
            if (typeof(t.dataset.meetingid) !== 'undefined') {
                record['meetingID'] = t.dataset.meetingid;
            }
            if (typeof(t.dataset.meetingid) !== 'undefined') {
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