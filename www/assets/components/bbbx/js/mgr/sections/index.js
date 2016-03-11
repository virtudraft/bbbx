Ext.onReady(function () {
    MODx.load({xtype: 'bbbx-page-home'});
});
BBBx.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
                xtype: 'bbbx-panel-home',
                renderTo: 'bbbx-panel-home-div'
            }]
    });
    BBBx.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(BBBx.page.Home, MODx.Component);
Ext.reg('bbbx-page-home', BBBx.page.Home);