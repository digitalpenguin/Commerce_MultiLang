CommerceMultiLang.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,layout:'anchor'
        ,items: [{
            html: '<h2>'+_('commercemultilang')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,activeTab: 0
            ,hideMode: 'offsets'
            ,items: [{
                title: _('commercemultilang.item.items')
                ,layout:'anchor'
                ,items: [{
                    html: '<p>'+_('commercemultilang.item.intro_msg')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'commercemultilang-grid-items'
                    ,preventRender: true
                    ,cls: 'main-wrapper'
                }]
            }]
        }]
    });
    CommerceMultiLang.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.panel.Home,MODx.Panel);
Ext.reg('commercemultilang-panel-home',CommerceMultiLang.panel.Home);
