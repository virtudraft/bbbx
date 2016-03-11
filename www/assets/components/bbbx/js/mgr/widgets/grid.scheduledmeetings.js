BBBx.grid.ScheduledMeetings = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'bbbx-grid-scheduledmeetings',
        url: BBBx.config.connectorUrl,
        baseParams: {
            action: 'mgr/meetings/scheduled/getList'
        },
        fields: ['meetingID', 'meetingName',
//            'createTime',
            {name: 'createTime', mapping: 'createTime', type: 'date', dateFormat: 'timestamp'},
            'createDate', 'voiceBridge', 'dialNumber',
//            'attendeePW',
            {name: 'attendeePW', mapping: 'attendeePW', type: 'string'},
//             'moderatorPW',
            {name: 'moderatorPW', mapping: 'moderatorPW', type: 'string'},
            'hasBeenForciblyEnded', 'running', 'participantCount',
            'listenerCount', 'voiceParticipantCount', 'videoCount', 'duration', 'hasUserJoined',
            'joinURL', 'endURL'
        ],
        paging: true,
        remoteSort: true,
//        anchor: '97%',
        autoExpandColumn: 'meetingName',
        columns: [{
                header: _('id'),
                dataIndex: 'meetingID',
                sortable: true,
                width: 100,
                hidden: true,
            }, {
                header: _('name'),
                dataIndex: 'meetingName',
                sortable: true,
                renderer: this.renderName,
            }, {
                header: _('bbbx.createTime'),
                dataIndex: 'createTime',
                sortable: true,
//                width: 100
                hidden: true,
            }, {
                header: _('bbbx.createDate'),
                dataIndex: 'createDate',
                sortable: true,
//                width: 100
                hidden: true,
            }, {
                header: _('bbbx.voiceBridge'),
                dataIndex: 'voiceBridge',
                sortable: true,
                width: 60,
                hidden: true,
            }, {
                header: _('bbbx.dialNumber'),
                dataIndex: 'dialNumber',
                sortable: true,
                width: 80,
                hidden: true,
            }, {
                header: _('bbbx.attendeePW'),
                dataIndex: 'attendeePW',
                sortable: true,
//                width: 100
                hidden: true,
            }, {
                header: _('bbbx.moderatorPW'),
                dataIndex: 'moderatorPW',
                sortable: true,
//                width: 100
                hidden: true,
            }, {
                header: _('bbbx.hasBeenForciblyEnded'),
                dataIndex: 'hasBeenForciblyEnded',
                sortable: true,
                width: 70,
                fixed: true
            }, {
                header: _('bbbx.running'),
                dataIndex: 'running',
                sortable: true,
                width: 80,
                fixed: true
            }, {
                header: _('bbbx.participants'),
                dataIndex: 'participantCount',
                sortable: true,
                width: 100,
                fixed: true,
//                hidden: true,
            }, {
                header: _('bbbx.listeners'),
                dataIndex: 'listenerCount',
                sortable: true,
                width: 100,
                fixed: true,
                hidden: true,
            }, {
                header: _('bbbx.voices'),
                dataIndex: 'voiceParticipantCount',
                sortable: true,
//                width: 100
                fixed: true,
                hidden: true,
            }, {
                header: _('bbbx.videos'),
                dataIndex: 'videoCount',
                sortable: true,
//                width: 100
                fixed: true,
                hidden: true,
            }, {
                header: _('bbbx.duration'),
                dataIndex: 'duration',
                sortable: true,
                width: 100,
                fixed: true,
                hidden: true,
            }, {
                header: _('bbbx.hasUserJoined'),
                dataIndex: 'hasUserJoined',
                sortable: false,
                width: 100,
                fixed: true,
//                hidden: true,
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
                text: _('bbbx.meeting_join')
                , handler: this.joinMeeting
            }, '-', {
                text: _('bbbx.meeting_end')
                , handler: this.endMeeting
            }];
    },
    createMeeting: function () {
        if (typeof (this.meetingWindow) === 'undefined') {
            this.meetingWindow = MODx.load({
                xtype: 'bbbx-window-meeting',
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
        }
        this.meetingWindow.reset();
        this.meetingWindow.show();
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
                '<tr><td>meetingName</td><td>: {meetingName}</td></tr>' +
                '<tr><td>meetingID</td><td>: {meetingID}</td></tr>' +
                '<tr><td>createTime</td><td>: {createTime}</td></tr>' +
                '<tr><td>voiceBridge</td><td>: {voiceBridge}</td></tr>' +
                '<tr><td>dialNumber</td><td>: {dialNumber}</td></tr>' +
                '<tr><td>duration</td><td>: {duration}</td></tr>' +
                '<tr><td>attendeePW</td><td>: {attendeePW}</td></tr>' +
                '<tr><td>moderatorPW</td><td>: {moderatorPW}</td></tr>' +
                '<tr><td></td><td>' +
                '<a href="{joinURL}" target="_blank" class="x-btn x-btn-small bbbx-action-btn">Join</a>' +
                '<a href="javascript:void(0);" class="x-btn x-btn-small bbbx-btn-danger bbbx-action-btn bbbx-btn-end" data-meetingid="{meetingID}" data-moderatorpw="{moderatorPW}">End</a>' +
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