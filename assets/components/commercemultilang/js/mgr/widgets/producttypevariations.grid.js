/**
 * This is the product variations grid inside the product type window where editors can specify variations a type has.
 * @param config
 * @constructor
 */
CommerceMultiLang.grid.ProductTypeVariations = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'commercemultilang-grid-product-type-variations'
        ,url: CommerceMultiLang.config.connectorUrl
        ,baseParams:{
            action: 'mgr/product-type/variation/getlist'
        }
        ,save_action: 'mgr/product-type/variation/updatefromgrid'
        ,autosave: true
        ,fields: ['id','name','lang_key','position']
        ,autoHeight: true
        ,paging: true
        ,pageSize: 10
        ,remoteSort: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 40
        },{
            header: _('commercemultilang.product_type_variation.name')
            ,dataIndex: 'name'
            ,width: 200
            ,editor: { xtype: 'textfield' }
        },{
            header: _('commercemultilang.product_type_variation.language')
            ,dataIndex: 'lang_key'
            ,width: 100
            ,editor: { xtype: 'textarea' }
        },{
            header: _('commercemultilang.product_type_variation.position')
            ,dataIndex: 'position'
            ,width: 60
            ,hidden:true
            ,editor: { xtype: 'numberfield', allowDecimal: false, allowNegative: false }
        }]
        ,tbar: [{
            text: _('commercemultilang.product_type_variation.create')
            ,handler: this.createProductTypeVariation
            ,scope: this
        }]
        ,listeners: {
            'render': function(grid) {
                grid.type_id = Ext.getCmp('commercemultilang-window-product-type-update').config.record.type_id;
            }
        }
    });
    CommerceMultiLang.grid.ProductTypeVariations.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.grid.ProductTypeVariations,MODx.grid.Grid,{
    windows: {}

    ,getMenu: function() {
        var m = [];
        m.push({
            text: _('commercemultilang.product_type_variation.edit')
            ,handler: this.updateProductTypeVariation
        });
        m.push('-');
        m.push({
            text: _('commercemultilang.product_type_variation.remove')
            ,handler: this.removeProductTypeVariation
        });
        this.addContextMenuItem(m);
    }

    ,createProductTypeVariation: function(btn,e) {
        var win = Ext.getCmp('commercemultilang-window-product-type-variation-create');
        if(win) {
            win.show(e.target);
        } else {
            var createProductTypeVariation = MODx.load({
                xtype: 'commercemultilang-window-product-type-variation-create'
                ,id: 'commercemultilang-window-product-type-variation-create'
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
        var win = Ext.getCmp('commercemultilang-window-product-type-variation-update');
        if(win) {
            win.show(e.target);
        } else {
            var updateProductType = MODx.load({
                xtype: 'commercemultilang-window-product-type-variation-update'
                ,title: _('commercemultilang.product_type_variation.update')
                ,action: 'mgr/product-type/update'
                ,id:'commercemultilang-window-product-type-'+this.menu.record['id']
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
            title: _('commercemultilang.product_type_variation.remove')
            ,text: _('commercemultilang.product_type_variation.remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/product-type/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:function(r) { this.refresh(); },scope:this}
            }
        });
    }
});
Ext.reg('commercemultilang-grid-product-type-variations',CommerceMultiLang.grid.ProductTypeVariations);

CommerceMultiLang.window.ProductTypeVariationCreate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('commercemultilang.product_type_variation.create')
        ,id:'commercemultilang-window-product-type-variation-create'
        ,closeAction: 'close'
        ,url: CommerceMultiLang.config.connectorUrl
        ,action: 'mgr/product-type/variation/create'
        ,keys: []
        ,fields: [{
            xtype: 'textfield'
            ,name: 'id'
            ,hidden: true
        },{
            xtype: 'commercemultilang-combo-language'
            ,fieldLabel: 'Language'//_('commercemultilang.product_type_variation.language')
            ,name: 'lang_key'
            ,hiddenName: 'lang_key'
            ,anchor: '100%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('commercemultilang.product_type_variation.name')
            ,name: 'name'
            ,anchor: '100%'
        }]
    });
    CommerceMultiLang.window.ProductTypeVariationCreate.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.window.ProductTypeVariationCreate,MODx.Window);
Ext.reg('commercemultilang-window-product-type-variation-create',CommerceMultiLang.window.ProductTypeVariationCreate);



CommerceMultiLang.window.ProductTypeVariationUpdate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('commercemultilang.product_type.update')
        ,id:'commercemultilang-window-product-type-variation-update'
        ,closeAction: 'close'
        ,url: CommerceMultiLang.config.connectorUrl
        ,action: 'mgr/product-type/variation/update'
        ,keys: []
        ,fields: [{
            xtype: 'textfield'
            ,name: 'id'
            ,hidden: true
        },{
            xtype: 'textfield'
            ,fieldLabel: _('commercemultilang.product_type_variation.name')
            ,name: 'name'
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,anchor: '100%'
        }]
    });
    CommerceMultiLang.window.ProductTypeVariationUpdate.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.window.ProductTypeVariationUpdate,MODx.Window);
Ext.reg('commercemultilang-window-product-type-variation-update',CommerceMultiLang.window.ProductTypeVariationUpdate);