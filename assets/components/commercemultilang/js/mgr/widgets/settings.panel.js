CommerceMultiLang.panel.Settings = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,layout:'anchor'
        ,items: [{
            html: '<h2>Commerce <span style="font-size:15px; position:relative; top:-5px;">>></span> Products ' +
            '<span style="font-size:15px; position:relative; top:-5px;">>></span> Settings </h2>'//'<h2>'+_('commercemultilang')+'</h2>'
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
                }/*,{
                    xtype: 'commercemultilang-grid-products'
                    ,preventRender: true
                    ,cls: 'main-wrapper'
                }*/]
            },{
                title: 'Bundles'
                ,layout:'anchor'
                ,items: [{
                    html: '<p>Groups of products sold together.</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                }]
            }]
        }]
        ,listeners: {
            render: this.getButtons
        }
    });
    CommerceMultiLang.panel.Settings.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.panel.Settings,MODx.Panel,{
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
            text:'<i class="icon icon-shopping-basket"></i> &nbsp;Products',
            handler: this.loadHomePage,
            scope:this
        });

        modab.doLayout();
    }
    ,loadHomePage: function() {
        MODx.loadPage('home', 'namespace=commercemultilang');
    }
    ,loadOrdersPage: function() {
        MODx.loadPage('index&ca=orders', 'namespace=commerce');
    }
});
Ext.reg('commercemultilang-panel-settings',CommerceMultiLang.panel.Settings);