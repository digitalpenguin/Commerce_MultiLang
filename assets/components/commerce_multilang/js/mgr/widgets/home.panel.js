Commerce_MultiLang.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,layout:'anchor'
        ,items: [{
            html: '<h2 id="commerce_multilang-header">Commerce <span style="font-size:15px; position:relative; top:-5px;">>></span> Products</h2>'//'<h2>'+_('commerce_multilang')+'</h2>'
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
                    html: '<p>'+_('commerce_multilang.product.intro_msg')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'commerce_multilang-grid-products'
                    ,preventRender: true
                    ,cls: 'main-wrapper'
                }]
            },{
                title: 'Types'
                ,layout:'anchor'
                ,items: [{
                    html: '<p>'+_('commerce_multilang.product.variations_intro')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype:'commerce_multilang-grid-product-types'
                    ,preventRender: true
                    ,cls: 'main-wrapper'
                }]
            }]
        }]
        ,listeners: {
            render: this.getButtons
        }
    });
    Commerce_MultiLang.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(Commerce_MultiLang.panel.Home,MODx.Panel,{
    getButtons: function(e) {
        // get rid of the side bar
        //Ext.getCmp('modx-layout').hideLeftbar();
        var modab = new MODx.toolbar.ActionButtons;
        modab.add({
            xtype:'button',
            text:'<i class="icon icon-cart-arrow-down"></i> &nbsp;Orders',
            handler: this.loadOrdersPage,
            scope:this
        });
        /*modab.add({
            xtype:'button',
            text:'<i class="icon icon-cog"></i> &nbsp;Product Settings',
            handler: this.loadSettingsPage,
            scope:this
        });*/

        modab.doLayout();
    }
    ,loadSettingsPage: function() {
        MODx.loadPage('settings', 'namespace=commerce_multilang');
    }
    ,loadOrdersPage: function() {
        MODx.loadPage('index&ca=orders', 'namespace=commerce');
    }
});
Ext.reg('commerce_multilang-panel-home',Commerce_MultiLang.panel.Home);