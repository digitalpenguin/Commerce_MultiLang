/**
 * This is the product variations grid inside the product type window where editors can specify variations a type has.
 * @param config
 * @constructor
 */
Commerce_MultiLang.grid.ProductTypeVariations = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'commerce_multilang-grid-product-type-variations'
        ,url: Commerce_MultiLang.config.connectorUrl
        ,baseParams:{
            action: 'mgr/product-type/variation/getlist'
        }
        ,save_action: 'mgr/product-type/variation/updatefromgrid'
        ,autosave: true
        ,fields: ['id','name','display_name','description','position']
        ,autoHeight: true
        ,paging: true
        ,pageSize: 10
        ,remoteSort: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 40
            ,hidden: true
        },{
            header: _('commerce_multilang.product_type_variation.name')
            ,dataIndex: 'name'
            ,width: 100
        },{
            header: _('commerce_multilang.product_type_variation.display_name')
            ,dataIndex: 'display_name'
            ,width: 120
            ,editor: { xtype: 'textfield' }
        },{
            header: _('commerce_multilang.product_type_variation.description')
            ,dataIndex: 'description'
            ,width: 200
            ,editor: { xtype: 'textfield' }
        },{
            header: _('commerce_multilang.product_type_variation.position')
            ,dataIndex: 'position'
            ,width: 60
            ,hidden:true
            ,editor: { xtype: 'numberfield', allowDecimal: false, allowNegative: false }
        }]
        ,tbar: [{
            text: _('commerce_multilang.product_type_variation.create')
            ,handler: this.createProductTypeVariation
            ,scope: this
        }]
        ,listeners: {
            'render': function(grid) {
                grid.type_id = Ext.getCmp('commerce_multilang-window-product-type-update').config.record.type_id;
                grid.store.setBaseParam('action','mgr/product-type/variation/getlist');
                grid.store.setBaseParam('type_id',grid.type_id);
            }
        }
    });
    Commerce_MultiLang.grid.ProductTypeVariations.superclass.constructor.call(this,config);
};
Ext.extend(Commerce_MultiLang.grid.ProductTypeVariations,MODx.grid.Grid,{
    windows: {}

    ,getMenu: function() {
        var m = [];
        m.push({
            text: _('commerce_multilang.product_type_variation.edit')
            ,handler: this.updateProductTypeVariation
        });
        m.push('-');
        m.push({
            text: _('commerce_multilang.product_type_variation.remove')
            ,handler: this.removeProductTypeVariation
        });
        this.addContextMenuItem(m);
    }

    ,createProductTypeVariation: function(btn,e) {
        var win = Ext.getCmp('commerce_multilang-window-product-type-variation-create');
        if(win) {
            win.show(e.target);
        } else {
            var createProductTypeVariation = MODx.load({
                xtype: 'commerce_multilang-window-product-type-variation-create'
                ,id: 'commerce_multilang-window-product-type-variation-create'
                ,baseParams: {
                    action:'mgr/product-type/variation/create'
                    ,type_id: this.type_id
                }
                , listeners: {
                    'success': {
                        fn: function () {
                            this.refresh();
                        }, scope: this
                    }
                }
            });

            createProductTypeVariation.show(e.target);
        }
    }

    ,updateProductTypeVariation: function(btn,e,isUpdate) {
        if (!this.menu.record || !this.menu.record.id) return false;
        var win = Ext.getCmp('commerce_multilang-window-product-type-variation-update');
        if(win) {
            win.show(e.target);
        } else {
            var updateProductType = MODx.load({
                xtype: 'commerce_multilang-window-product-type-variation-update'
                ,title: _('commerce_multilang.product_type_variation.update')
                ,action: 'mgr/product-type/variation/update'
                ,id:'commerce_multilang-window-product-type-'+this.menu.record['id']
                ,record: this.menu.record
                ,listeners: {
                    'success': {
                        fn: function () {
                            this.refresh();
                        }, scope: this
                    }
                }
            });
            updateProductType.fp.getForm().reset();
            updateProductType.fp.getForm().setValues(this.menu.record);
            updateProductType.show(e.target);
        }
    }

    ,removeProductTypeVariation: function(btn,e) {
        if (!this.menu.record) return false;

        MODx.msg.confirm({
            title: _('commerce_multilang.product_type_variation.remove')
            ,text: _('commerce_multilang.product_type_variation.remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/product-type/variation/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:function(r) { this.refresh(); },scope:this}
            }
        });
    }
});
Ext.reg('commerce_multilang-grid-product-type-variations',Commerce_MultiLang.grid.ProductTypeVariations);

Commerce_MultiLang.window.ProductTypeVariationCreate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('commerce_multilang.product_type_variation.create')
        ,id:'commerce_multilang-window-product-type-variation-create'
        ,closeAction: 'close'
        ,url: Commerce_MultiLang.config.connectorUrl
        ,action: 'mgr/product-type/variation/create'
        ,keys: []
        ,fields: [{
            xtype: 'textfield'
            ,name: 'id'
            ,hidden: true
        },{
            xtype: 'textfield'
            ,fieldLabel: _('commerce_multilang.product_type_variation.name')
            ,name: 'name'
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,anchor: '100%'
        }]
    });
    Commerce_MultiLang.window.ProductTypeVariationCreate.superclass.constructor.call(this,config);
};
Ext.extend(Commerce_MultiLang.window.ProductTypeVariationCreate,MODx.Window);
Ext.reg('commerce_multilang-window-product-type-variation-create',Commerce_MultiLang.window.ProductTypeVariationCreate);



Commerce_MultiLang.window.ProductTypeVariationUpdate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('commerce_multilang.product_type.update')
        ,id:'commerce_multilang-window-product-type-variation-update'
        ,closeAction: 'close'
        ,url: Commerce_MultiLang.config.connectorUrl
        ,action: 'mgr/product-type/variation/update'
        ,keys: []
        ,fields: [{
            xtype: 'textfield'
            ,name: 'id'
            ,hidden: true
        },{
            xtype: 'textfield'
            ,fieldLabel: _('commerce_multilang.product_type_variation.name')
            ,name: 'name'
            ,disabled: true
            ,anchor: '100%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('commerce_multilang.product_type_variation.display_name')
            ,name: 'display_name'
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,anchor: '100%'
        }]
    });
    Commerce_MultiLang.window.ProductTypeVariationUpdate.superclass.constructor.call(this,config);
};
Ext.extend(Commerce_MultiLang.window.ProductTypeVariationUpdate,MODx.Window);
Ext.reg('commerce_multilang-window-product-type-variation-update',Commerce_MultiLang.window.ProductTypeVariationUpdate);