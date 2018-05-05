/**
 * This is the product variation grid within the product update window
 * @param config
 * @constructor
 */
CommerceMultiLang.grid.ProductUpdateVariations = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'commercemultilang-grid-product-update-variations'
        ,url: CommerceMultiLang.config.connectorUrl
        ,baseParams:{
            action: 'mgr/product/variation/getlist'
        }
        ,save_action: 'mgr/product/variation/updatefromgrid'
        ,autosave: true
        ,fields: ['id','image','product_id','name','description','variations','position']
        ,autoHeight: true
        ,paging: true
        ,pageSize: 10
        ,remoteSort: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 40
        },{
            header: _('commercemultilang.product.image')
            ,dataIndex: 'image'
            ,fixed:true
            ,width: 100
            ,renderer: function(value, meta, record) {
                if(value) {
                    return '<img style="max-width:100%;" title="'+record['name']+'"  src=' + value + '"/" />';
                } else {
                    return '<img style="max-width:100%;" title="'+record['name']+'"  src="'+ CommerceMultiLang.config.assetsUrl +'img/placeholder.jpg" />';
                }
            }
        },{
            header: _('commercemultilang.product.name')
            ,dataIndex: 'name'
            ,width: 200
        }]
        ,tbar: [{
            text: _('commercemultilang.product.create')
            ,handler: this.createProductUpdateVariation
            ,scope: this
        }]
        /*,listeners: {
            'render': function(grid) {
                if (grid.store.getCount() === 0) {
                    grid.store.on('load', function() {
                        var extraCols = grid.store.reader.jsonData.extra_cols;
                        var colModel = grid.getColumnModel();

                        extraCols.forEach(function(col) {
                            var newCol = new Ext.grid.Column({
                                id: colModel.config.length,
                                header: col,
                                width: 200,
                                dataIndex: col,
                            });
                            colModel.config.push(newCol);
                        });
                        grid.reconfigure(grid.store,colModel);
                        grid.doLayout();
                        console.log(colModel);
                    },grid,{single: true});
                }
            }
        }*/
    });
    CommerceMultiLang.grid.ProductUpdateVariations.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.grid.ProductUpdateVariations,MODx.grid.Grid,{
    windows: {}

    ,getMenu: function() {
        var m = [];
        m.push({
            text: _('commercemultilang.product_variation.edit')
            ,handler: this.updateProductUpdateVariation
        });
        m.push('-');
        m.push({
            text: _('commercemultilang.product_variation.remove')
            ,handler: this.removeProductUpdateVariation
        });
        this.addContextMenuItem(m);
    }

    ,createProductUpdateVariation: function(btn,e) {
        var win = Ext.getCmp('commercemultilang-window-product-variation-create');
        if(win) {
            win.show(e.target);
        } else {
            var createProduct = MODx.load({
                xtype: 'commercemultilang-window-product-variation-create'
                ,id:'commercemultilang-window-product-variation-create'
                ,baseParams:{
                    action: 'mgr/product/variation/create'
                    ,parent: this.config.baseParams.product_id
                }
                ,listeners: {
                    'success': {fn:function() { this.refresh(); },scope:this}
                }
            });
            createProduct.addVariationFields(this.config);
            createProduct.show(e.target);
        }
    }

    ,updateProductUpdateVariation: function(btn,e,isUpdate) {
        if (!this.menu.record || !this.menu.record.id) return false;

        var updateProductImage = MODx.load({
            xtype: 'commercemultilang-window-product-variation'
            ,title: _('commercemultilang.product_image.update')
            ,action: 'mgr/product/variation/update'
            ,record: this.menu.record
            ,listeners: {
                'success': {fn:function() { this.refresh(); },scope:this}
            }
        });

        updateProductImage.fp.getForm().reset();
        updateProductImage.fp.getForm().setValues(this.menu.record);
        updateProductImage.show(e.target);
    }

    ,removeProductUpdateVariation: function(btn,e) {
        if (!this.menu.record) return false;

        MODx.msg.confirm({
            title: _('commercemultilang.product_image.remove')
            ,text: _('commercemultilang.product_image.remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/product/variation/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:function(r) { this.refresh(); },scope:this}
            }
        });
    }
});
Ext.reg('commercemultilang-grid-product-update-variations',CommerceMultiLang.grid.ProductUpdateVariations);

CommerceMultiLang.window.ProductVariation = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('commercemultilang.product_type.create')
        ,id:'commercemultilang-window-product-variation'
        ,closeAction: 'close'
        ,url: CommerceMultiLang.config.connectorUrl
        ,action: 'mgr/product-type/variation/create'
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
    CommerceMultiLang.window.ProductVariation.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.window.ProductVariation,MODx.Window);
Ext.reg('commercemultilang-window-product-variation',CommerceMultiLang.window.ProductVariation);



CommerceMultiLang.window.ProductVariationCreate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('commercemultilang.product.create')
        ,closeAction: 'close'
        ,width:600
        ,url: CommerceMultiLang.config.connectorUrl
        ,action: 'mgr/product/variation/create'
        ,keys: []
        ,fields: [{
            style:'padding:15px 0'
            ,html:'<h4>Add Variation</h4><p>Adding a variation adds a duplicate of this product with only the following fields being different.</p>'
        },{
            xtype: 'textfield'
            ,name: 'id'
            ,hidden: true
        },{
            layout: 'column'
            ,border: false
            ,items: [{
                columnWidth: .35
                ,id:'product-variation-create-left-col'
                ,layout: 'form'
                ,items: [{
                    xtype: 'modx-combo-browser'
                    ,id: 'update-product-image-select-' + Ext.id()
                    ,fieldLabel: 'Select Image'
                    ,name: 'image'
                    ,anchor:'100%'
                    ,rootId: '/'
                    ,rootVisible:true
                    ,hideSourceCombo: true
                },{
                    html:'<img style="max-width:100%; margin-top:10px;" src="'+ CommerceMultiLang.config.assetsUrl +'img/placeholder.jpg" />'
                }]
            },{
                columnWidth: .3
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
                }]
            },{
                columnWidth: .35
                ,layout: 'form'
                ,id:'product-variation-create-right-col'
                ,items:[]
            }]
        }]

    });
    CommerceMultiLang.window.ProductVariationCreate.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.window.ProductVariationCreate,MODx.Window,{

    /**
     * Takes variations for this product type and injects the fields into the tab.
     * @param config
     */
    addVariationFields: function(config) {
        var fields = [];
        config.variations.forEach(function(variation){
            var lcName = variation['name'].toLowerCase();
            fields.push({
                xtype: 'textfield'
                ,fieldLabel: variation['name']
                ,name: lcName
                ,anchor: '100%'
            });
        });

        Ext.getCmp('product-variation-create-right-col').add(fields);

    }

});
Ext.reg('commercemultilang-window-product-variation-create',CommerceMultiLang.window.ProductVariationCreate);


