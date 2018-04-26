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
                title: 'Bundles'
                ,layout:'anchor'
                ,items: [{
                    html: '<p>'+_('commercemultilang.product.intro_msg')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                }]
            }]
        }]
        ,listeners: {
            render: this.getButtons
        }
    });
    CommerceMultiLang.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.panel.Home,MODx.Panel,{
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
        modab.add({
            xtype:'button',
            text:'<i class="icon icon-cog"></i> &nbsp;Product Settings',
            handler: this.loadSettingsPage,
            scope:this
        });

        modab.doLayout();
    }
    ,loadSettingsPage: function() {
        MODx.loadPage('settings', 'namespace=commercemultilang');
    }
    ,loadOrdersPage: function() {
        MODx.loadPage('index&ca=orders', 'namespace=commerce');
    }
});
Ext.reg('commercemultilang-panel-home',CommerceMultiLang.panel.Home);