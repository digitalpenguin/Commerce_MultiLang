CommerceMultiLang.grid.Products = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'commercemultilang-grid-products'
        ,url: CommerceMultiLang.config.connectorUrl
        ,baseParams: {
            action: 'mgr/product/getlist'
        }
        ,save_action: 'mgr/product/updatefromgrid'
        ,autosave: true
        ,fields: ['id','sku','main_image','name','description','price','stock','weight','weight_unit','target','properties','images','position']
        ,autoHeight: true
        ,paging: true
        ,remoteSort: true
        ,ddGroup: 'commercemultilangProductDDGroup'
        ,enableDragDrop: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 70
        },{
            header: _('commercemultilang.product.image')
            ,dataIndex: 'main_image'
            ,fixed:true
            ,width: 140
            ,editor: { xtype: 'textfield' }
        },{
            header: _('commercemultilang.product.sku')
            ,dataIndex: 'sku'
            ,width: 100
            ,editor: { xtype: 'textfield' }
        },{
            header: _('commercemultilang.product.name')
            ,dataIndex: 'name'
            ,width: 200
            ,editor: { xtype: 'textfield' }
        },{
            header: _('commercemultilang.product.stock')
            ,dataIndex: 'stock'
            ,width: 40
            ,editor: { xtype: 'textfield' }
        },{
            header: _('commercemultilang.product.description')
            ,dataIndex: 'description'
            ,width: 350
            ,editor: { xtype: 'textfield' }
        },{
            header: _('commercemultilang.product.position')
            ,dataIndex: 'position'
            ,width: 60
            ,editor: { xtype: 'numberfield', allowDecimal: false, allowNegative: false }
        }]
        ,tbar: [{
            text: _('commercemultilang.product.create')
            ,handler: this.createProduct
            ,scope: this
        },'->',{
            xtype: 'textfield'
            ,emptyText: _('commercemultilang.global.search') + '...'
            ,listeners: {
                'change': {fn:this.search,scope:this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this);
                            this.blur();
                            return true;
                        }
                        ,scope: cmp
                    });
                },scope:this}
            }
        }]
        ,listeners: {
            'render': function(g) {
                var ddrow = new Ext.ux.dd.GridReorderDropTarget(g, {
                    copy: false
                    ,listeners: {
                        'beforerowmove': function(objThis, oldIndex, newIndex, records) {
                        }

                        ,'afterrowmove': function(objThis, oldIndex, newIndex, records) {

                            MODx.Ajax.request({
                                url: CommerceMultiLang.config.connectorUrl
                                ,params: {
                                    action: 'mgr/product/reorder'
                                    ,idItem: records.pop().id
                                    ,oldIndex: oldIndex
                                    ,newIndex: newIndex
                                }
                                ,listeners: {

                                }
                            });
                        }

                        ,'beforerowcopy': function(objThis, oldIndex, newIndex, records) {
                        }

                        ,'afterrowcopy': function(objThis, oldIndex, newIndex, records) {
                        }
                    }
                });

                Ext.dd.ScrollManager.register(g.getView().getEditorParent());
            }
            ,beforedestroy: function(g) {
                Ext.dd.ScrollManager.unregister(g.getView().getEditorParent());
            }

        }
    });
    CommerceMultiLang.grid.Products.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.grid.Products,MODx.grid.Grid,{
    windows: {}

    ,getMenu: function() {
        var m = [];
        m.push({
            text: _('commercemultilang.product.update')
            ,handler: this.updateProduct
        });
        m.push('-');
        m.push({
            text: _('commercemultilang.product.remove')
            ,handler: this.removeProduct
        });
        this.addContextMenuItem(m);
    }
    
    ,createProduct: function(btn,e) {

        var createProduct = MODx.load({
            xtype: 'commercemultilang-window-product'
            ,listeners: {
                'success': {fn:function() { this.refresh(); },scope:this}
            }
        });

        createProduct.show(e.target);
    }

    ,updateProduct: function(btn,e,isUpdate) {
        if (!this.menu.record || !this.menu.record.id) return false;

        var updateProduct = MODx.load({
            xtype: 'commercemultilang-window-product'
            ,title: _('commercemultilang.product.update')
            ,action: 'mgr/product/update'
            ,record: this.menu.record
            ,listeners: {
                'success': {fn:function() { this.refresh(); },scope:this}
            }
        });

        updateProduct.fp.getForm().reset();
        updateProduct.fp.getForm().setValues(this.menu.record);
        updateProduct.show(e.target);
    }
    
    ,removeProduct: function(btn,e) {
        if (!this.menu.record) return false;
        
        MODx.msg.confirm({
            title: _('commercemultilang.product.remove')
            ,text: _('commercemultilang.product.remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/product/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:function(r) { this.refresh(); },scope:this}
            }
        });
    }

    ,search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    
    ,getDragDropText: function(){
        return this.selModel.selections.items[0].data.name;
    }
});
Ext.reg('commercemultilang-grid-products',CommerceMultiLang.grid.Products);

CommerceMultiLang.window.Product = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('commercemultilang.product.create')
        ,closeAction: 'close'
        ,url: CommerceMultiLang.config.connectorUrl
        ,action: 'mgr/product/create'
        ,fields: [{
            xtype: 'textfield'
            ,name: 'id'
            ,hidden: true
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,anchor: '100%'
        },{
            xtype: 'textfield'
            ,name: 'position'
            ,hidden: true
        }]
    });
    CommerceMultiLang.window.Product.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.window.Product,MODx.Window);
Ext.reg('commercemultilang-window-product',CommerceMultiLang.window.Product);

