BBBx.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        border: false,
        baseCls: 'modx-formpanel',
        cls: 'container',
        items: [
            {
                layout: 'hbox',
                border: false,
                bodyStyle: 'background-color: transparent;',
                defaults: {
                    border: false
                },
                items: [
                    {
                        html: '<span style="margin-right: 10px; line-height: 24px;">' +
                                '<span style="font-weight: bold; font-size: 24px;">' +
                                _('bbbx') +
                                '</span> ' + BBBx.config.version +
                                '</span>',
                        border: false,
                        cls: 'modx-page-header'
                    }, {
                        xtype: 'panel',
                        html: '<a href="javascript:void(0);" id="bbbx_about">' + _('bbbx.about') + '</a>',
                        border: false,
                        bodyStyle: 'font-size: 10px; margin: 5px; background-color: transparent; line-height: 24px;',
                        listeners: {
                            afterrender: function () {
                                Ext.get('bbbx_about').on('click', function () {
                                    var msg = '';
                                    msg += _('bbbx.about_desc') + '<br/>';
                                    msg += '&copy; 2016, <a href="http://www.virtudraft.com" target="_blank">';
                                    msg += 'www.virtudraft.com';
                                    msg += '</a><br/>';
                                    msg += 'License GPL v3';
                                    Ext.MessageBox.alert('BBBx', msg);
                                });
                            }
                        }
                    }
                ]
            }, {
                xtype: 'modx-tabs',
                defaults: {border: false, autoHeight: true},
                border: true,
                items: [
                    {
                        title: _('bbbx.meetings_scheduled'),
                        defaults: {autoHeight: true},
                        items: [{
                                html: '<p>' + _('bbbx.meetings_scheduled_desc') + '</p>',
                                border: false,
                                bodyCssClass: 'panel-desc'
                            }, {
                                xtype: 'bbbx-grid-scheduledmeetings',
                                cls: 'main-wrapper',
                                preventRender: true
                            }]
                    }, {
                        title: _('bbbx.meetings_running'),
                        defaults: {autoHeight: true},
                        items: [{
                                html: '<p>' + _('bbbx.meetings_running_desc') + '</p>',
                                border: false,
                                bodyCssClass: 'panel-desc'
                            }, {
                                xtype: 'bbbx-grid-runningmeetings',
                                cls: 'main-wrapper',
                                preventRender: true
                            }]
                    }, {
                        title: _('bbbx.recordings'),
                        defaults: {autoHeight: true},
                        items: [{
                                html: '<p>' + _('bbbx.recordings_desc') + '</p>',
                                border: false,
                                bodyCssClass: 'panel-desc'
                            }, {
                                xtype: 'bbbx-grid-recordings',
                                cls: 'main-wrapper',
                                preventRender: true
                            }]
                    }, {
                        title: _('bbbx.configurations'),
                        defaults: {autoHeight: true},
                        items: [{
                                html: '<p>' + _('bbbx.configurations_desc') + '</p>',
                                border: false,
                                bodyCssClass: 'panel-desc'
                            }, {
                                xtype: 'modx-tabs',
                                id: 'bbbx-configurations-tabs',
                                preventRender: true,
                                items: [
                                    {
                                        title: _('bbbx.custom_configs'),
                                        xtype: 'bbbx-grid-configs',
                                        cls: 'main-wrapper',
                                        preventRender: true
                                    }
                                ]
//                            }, {
//                                xtype: 'bbbx-panel-configs',
//                                cls: 'main-wrapper',
//                                preventRender: true
                            }
                        ]
                    }
                ]
                        // only to redo the grid layout after the content is rendered
                        // to fix overflow components' panels, especially when scroll bar is shown up
                , listeners: {
                    'afterrender': function (tabPanel) {
                        tabPanel.doLayout();
                    }
                }
            }]
    });
    BBBx.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(BBBx.panel.Home, MODx.Panel);
Ext.reg('bbbx-panel-home', BBBx.panel.Home);