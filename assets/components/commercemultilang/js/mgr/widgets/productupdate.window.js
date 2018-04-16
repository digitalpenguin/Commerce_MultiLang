CommerceMultiLang.window.ProductUpdate = function(config) {
    Ext.applyIf(config,{
        title: _('commercemultilang.product.update')
        ,closeAction: 'close'
        ,width:1000
        ,url: CommerceMultiLang.config.connectorUrl
        ,action: 'mgr/product/update'
        ,fields: [{
            xtype: 'modx-tabs'
            ,style:'padding:15px 0 0 0'
            ,id: 'product-update-window-tabs'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,activeTab: 0
            ,hideMode: 'offsets'
            ,deferredRender: false
            ,items: [{
                title:'General'
                ,layout:'form'
                ,items:[{
                    xtype: 'textfield'
                    ,name: 'id'
                    ,hidden: true
                },{
                    xtype: 'textfield'
                    ,name: 'name'
                    ,value: config.record['name']
                    ,hidden: true
                },{
                    html:'<h2 style="margin-top:15px;">'+ config.record['name'] +'</h2>'
                    ,anchor: '100%'
                },{
                    layout: 'column'
                    ,border: false
                    ,items: [{
                        columnWidth: .33
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'textfield'
                            ,fieldLabel: _('commercemultilang.product.sku')
                            ,name: 'sku'
                            ,anchor: '100%'
                        },{
                            xtype: 'textfield'
                            ,fieldLabel: _('commercemultilang.product.price')
                            ,name: 'price'
                            ,anchor: '100%'
                        },{
                            xtype: 'textfield'
                            ,fieldLabel: _('commercemultilang.product.stock')
                            ,name: 'stock'
                            ,anchor: '100%'
                        },{
                            xtype: 'textfield'
                            ,fieldLabel: _('commercemultilang.product.weight')
                            ,name: 'weight'
                            ,anchor: '100%'
                        },{
                            xtype: 'commercemultilang-combo-weightunit'
                            ,fieldLabel: _('commercemultilang.product.weight_unit')
                            ,name: 'weight_unit'
                            ,hiddenName: 'weight_unit'
                            ,anchor: '100%'
                        },{
                            xtype: 'commercemultilang-combo-deliverytype'
                            ,fieldLabel: _('commercemultilang.product.delivery_type')
                            ,name: 'delivery_type'
                            ,hiddenName: 'delivery_type'
                            ,anchor: '100%'
                        },{
                            xtype: 'commercemultilang-combo-taxgroup'
                            ,fieldLabel: _('commercemultilang.product.tax_group')
                            ,name: 'tax_group'
                            ,hiddenName: 'tax_group'
                            ,anchor: '100%'
                        }]
                    }]
                }]
            }]
        }]
    });
    CommerceMultiLang.window.ProductUpdate.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.window.ProductUpdate,MODx.Window,{
    addLanguageTabs: function(langTabs) {
        var tabs = Ext.getCmp('product-update-window-tabs');
        //console.log(langTabs);
        langTabs.forEach(function(langTab) {
            var tab = [{
                title: langTab['name']+' ('+langTab['lang_key']+')'
                ,layout:'form'
                ,forceLayout:true // important! if not added new tabs will not submit.
                ,items:[{
                    xtype: 'textfield'
                    ,fieldLabel: _('name')
                    ,name: 'name_'+langTab['lang_key']
                    ,value: langTab.fields ? langTab.fields['name']: ''
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,name: 'description_'+langTab['lang_key']
                    ,value: langTab.fields ? langTab.fields['description']: ''
                    ,anchor: '100%'
                }]
            }];
            tabs.add(tab);
        });
    }
});
Ext.reg('commercemultilang-window-product-update',CommerceMultiLang.window.ProductUpdate);