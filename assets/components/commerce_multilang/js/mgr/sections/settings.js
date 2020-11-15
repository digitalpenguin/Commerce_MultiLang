Ext.onReady(function() {
    MODx.load({ xtype: 'commerce_multilang-page-settings'});
});

Commerce_MultiLang.page.Settings = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'commerce_multilang-panel-settings'
            ,renderTo: 'commerce_multilang-panel-settings-div'
        }]
    });
    Commerce_MultiLang.page.Settings.superclass.constructor.call(this,config);
};
Ext.extend(Commerce_MultiLang.page.Settings,MODx.Component);
Ext.reg('commerce_multilang-page-settings',Commerce_MultiLang.page.Settings);