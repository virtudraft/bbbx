var BBBx = function (config) {
    config = config || {};
    BBBx.superclass.constructor.call(this, config);
};
Ext.extend(BBBx, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}
});
Ext.reg('bbbx', BBBx);
BBBx = new BBBx();