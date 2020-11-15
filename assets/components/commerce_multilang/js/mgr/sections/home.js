Ext.onReady(function() {
    MODx.load({ xtype: 'commerce_multilang-page-home'});
});

Commerce_MultiLang.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'commerce_multilang-panel-home'
            ,renderTo: 'commerce_multilang-panel-home-div'
        }]
    });
    Commerce_MultiLang.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(Commerce_MultiLang.page.Home,MODx.Component);
Ext.reg('commerce_multilang-page-home',Commerce_MultiLang.page.Home);