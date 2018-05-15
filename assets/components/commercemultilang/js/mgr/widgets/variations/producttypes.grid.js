/**
 * This is the product types grid where editors can specify types of product and variations a type has.
 * @param config
 * @constructor
 */
CommerceMultiLang.grid.ProductTypes = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'commercemultilang-grid-product-types'
        ,url: CommerceMultiLang.config.connectorUrl
        ,baseParams:{
            action: 'mgr/product-type/getlist'
        }
        ,save_action: 'mgr/product-type/updatefromgrid'
        ,autosave: true
        ,fields: ['id','name','description','position']
        ,autoHeight: true
        ,paging: true
        ,pageSize: 10
        ,remoteSort: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 40
        },{
            header: _('commercemultilang.product_type.name')
            ,dataIndex: 'name'
            ,width: 200
            ,editor: { xtype: 'textfield' }
        },{
            header: _('commercemultilang.product_type.description')
            ,dataIndex: 'description'
            ,width: 300
            ,editor: { xtype: 'textarea' }
        },{
            header: _('commercemultilang.product_type.position')
            ,dataIndex: 'position'
            ,width: 60
            ,hidden:true
            ,editor: { xtype: 'numberfield', allowDecimal: false, allowNegative: false }
        }]
        ,tbar: [{
            text: _('commercemultilang.product_type.create')
            ,handler: this.createProductType
            ,scope: this
        }]
    });
    CommerceMultiLang.grid.ProductTypes.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.grid.ProductTypes,MODx.grid.Grid,{
    windows: {}

    ,getMenu: function() {
        var m = [];
        m.push({
            text: _('commercemultilang.product_type.edit')
            ,handler: this.updateProductType
        });
        m.push('-');
        m.push({
            text: _('commercemultilang.product_type.remove')
            ,handler: this.removeProductType
        });
        this.addContextMenuItem(m);
    }

    ,createProductType: function(btn,e) {
        var win = Ext.getCmp('commercemultilang-window-product-type');
        if(win) {
            win.show(e.target);
        } else {
            var createProductType = MODx.load({
                xtype: 'commercemultilang-window-product-type-create'
                ,id: 'commercemultilang-window-product-type'
                ,baseParams: {
                    action: 'mgr/product-type/create'
                    //,type_id: Ext.getCmp('commercemultilang-window-product-type-update').config.record.id
                }
                , listeners: {
                    'success': {
                        fn: function () {
                            this.refresh();
                        }, scope: this
                    }
                }
            });

            createProductType.show(e.target);
        }
    }

    ,updateProductType: function(btn,e,isUpdate) {
        if (!this.menu.record || !this.menu.record.id) return false;
        var win = Ext.getCmp('commercemultilang-window-product-type-update');
        if(win) {
            win.show(e.target);
        } else {
            var updateProductType = MODx.load({
                xtype: 'commercemultilang-window-product-type-update'
                ,title: _('commercemultilang.product_type.update')
                ,action: 'mgr/product-type/update'
                ,type_id: this.menu.record.id
                ,id:'commercemultilang-window-product-type-update'
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
            updateProductType.record.type_id = this.menu.record.id;
            updateProductType.fp.getForm().setValues(this.menu.record);
            updateProductType.show(e.target);
        }
    }

    ,removeProductType: function(btn,e) {
        if (!this.menu.record) return false;

        MODx.msg.confirm({
            title: _('commercemultilang.product_type.remove')
            ,text: _('commercemultilang.product_type.remove_confirm')
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
Ext.reg('commercemultilang-grid-product-types',CommerceMultiLang.grid.ProductTypes);

CommerceMultiLang.window.ProductTypeCreate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('commercemultilang.product_type.create')
        ,id:'commercemultilang-window-product-type-create'
        ,closeAction: 'close'
        ,width:600
        ,url: CommerceMultiLang.config.connectorUrl
        ,action: 'mgr/product-type/create'
        ,keys: []
        ,fields: [{
            xtype: 'textfield'
            ,name: 'id'
            ,hidden: true
        },{
            xtype: 'textfield'
            ,fieldLabel: _('commercemultilang.product_type.name')
            ,name: 'name'
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,anchor: '100%'
        }]
    });
    CommerceMultiLang.window.ProductTypeCreate.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.window.ProductTypeCreate,MODx.Window);
Ext.reg('commercemultilang-window-product-type-create',CommerceMultiLang.window.ProductTypeCreate);



CommerceMultiLang.window.ProductTypeUpdate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('commercemultilang.product_type.update')
        ,id:'commercemultilang-window-product-type-update'
        ,closeAction: 'close'
        ,width:600
        ,url: CommerceMultiLang.config.connectorUrl
        ,action: 'mgr/product-type/update'
        ,keys: []
        ,fields: [{
            xtype: 'textfield'
            ,name: 'id'
            ,hidden: true
        },{
            layout:'column'
            ,items:[{
                columnWidth: .5
                ,layout:'form'
                ,items:[{
                    xtype: 'textfield'
                    ,fieldLabel: _('commercemultilang.product_type.name')
                    ,name: 'name'
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,name: 'description'
                    ,anchor: '100%'
                }]
            },{
                columnWidth: .5
                ,layout:'form'
                ,items:[{
                    xtype: 'commercemultilang-grid-product-type-variations'
                    ,preventRender: true
                    ,baseParams:{
                        action: 'mgr/product-type/variation/getlist'
                        ,type_id: config.record.id
                    }
                }]
            }]
        }]
    });
    CommerceMultiLang.window.ProductTypeUpdate.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.window.ProductTypeUpdate,MODx.Window);
Ext.reg('commercemultilang-window-product-type-update',CommerceMultiLang.window.ProductTypeUpdate);