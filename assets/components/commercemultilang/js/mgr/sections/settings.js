Ext.onReady(function() {
    MODx.load({ xtype: 'commercemultilang-page-settings'});
});

CommerceMultiLang.page.Settings = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'commercemultilang-panel-settings'
            ,renderTo: 'commercemultilang-panel-settings-div'
        }]
    });
    CommerceMultiLang.page.Settings.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.page.Settings,MODx.Component);
Ext.reg('commercemultilang-page-settings',CommerceMultiLang.page.Settings);