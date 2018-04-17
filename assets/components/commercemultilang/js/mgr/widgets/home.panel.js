CommerceMultiLang.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,layout:'anchor'
        ,items: [{
            html: '<h2>Commerce <span style="font-size:15px; position:relative; top:-5px;">>></span> Products</h2>'//'<h2>'+_('commercemultilang')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,activeTab: 0
            ,hideMode: 'offsets'
            ,items: [{
                title: 'Products'
                ,layout:'anchor'
                ,items: [{
                    html: '<p>'+_('commercemultilang.product.intro_msg')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'commercemultilang-grid-products'
                    ,preventRender: true
                    ,cls: 'main-wrapper'
                }]
            },{
                title: 'Catalogs'
                ,layout:'anchor'
                ,items: [{
                    html: '<p>'+_('commercemultilang.product.intro_msg')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                }]
            }]
        }]
    });
    CommerceMultiLang.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.panel.Home,MODx.Panel);
Ext.reg('commercemultilang-panel-home',CommerceMultiLang.panel.Home);