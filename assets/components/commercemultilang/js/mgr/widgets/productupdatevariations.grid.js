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
        ,fields: ['id','image','product_id','title','description','languages','alt','main','position']
        ,autoHeight: true
        ,paging: true
        ,pageSize: 10
        ,remoteSort: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 40
        },{
            header: _('commercemultilang.product_image.image')
            ,dataIndex: 'image'
            ,fixed:true
            ,width: 100
        },{
            header: _('commercemultilang.product_image.title')
            ,dataIndex: 'title'
            ,width: 100
        },{
            header: _('commercemultilang.product_image.description')
            ,dataIndex: 'description'
            ,width: 200
        },{
            header: _('commercemultilang.product_image.main')
            ,dataIndex: 'main'
            ,width: 40
        },{
            header: _('commercemultilang.product_image.position')
            ,dataIndex: 'position'
            ,width: 60
            ,hidden:true
            ,editor: { xtype: 'numberfield', allowDecimal: false, allowNegative: false }
        }]
        ,tbar: ['->',{
            text: _('commercemultilang.product_image.add')
            ,handler: this.createProductUpdateVariation
            ,scope: this
        }]
        /*,listeners: {
            'render': function(grid) {
                grid.languages = Ext.getCmp('commercemultilang-window-product-update').config.record.languages;
                grid.product_id = Ext.getCmp('commercemultilang-window-product-update').config.record.id;
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
            text: _('commercemultilang.product_image.edit')
            ,handler: this.updateProductUpdateVariation
        });
        m.push('-');
        m.push({
            text: _('commercemultilang.product_image.remove')
            ,handler: this.removeProductUpdateVariation
        });
        this.addContextMenuItem(m);
    }

    ,createProductUpdateVariation: function(btn,e) {
        var languages = JSON.stringify(this.languages);
        var createProductImage = MODx.load({
            xtype: 'commercemultilang-window-product-variation'
            ,baseParams: {
                action: 'mgr/product/variation/create'
                ,languages: languages
                ,product_id: Ext.getCmp('commercemultilang-window-product-update').config.record.id
            }
            ,listeners: {
                'success': {fn:function() { this.refresh(); },scope:this}
            }
        });

        createProductImage.show(e.target);
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