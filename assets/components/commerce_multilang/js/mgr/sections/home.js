Ext.onReady(function() {
    MODx.load({ xtype: 'commercemultilang-page-home'});
});

CommerceMultiLang.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'commercemultilang-panel-home'
            ,renderTo: 'commerce_multilang-panel-home-div'
        }]
    });
    CommerceMultiLang.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.page.Home,MODx.Component);
Ext.reg('commercemultilang-page-home',CommerceMultiLang.page.Home);