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
        ,autoHeight: true
        ,paging: true
        ,pageSize: 10
        ,remoteSort: true
        ,tbar: [{
            text: _('commercemultilang.product.variation_create')
            ,handler: this.createProductUpdateVariation
            ,scope: this
        }]
    });
    CommerceMultiLang.grid.ProductUpdateVariations.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.grid.ProductUpdateVariations,MODx.grid.Grid,{
    windows: {}

    ,getMenu: function() {
        var m = [];
        m.push({
            text: _('commercemultilang.product.variation_edit')
            ,handler: this.updateProductUpdateVariation
        });
        m.push('-');
        m.push({
            text: _('commercemultilang.product.variation_remove')
            ,handler: this.removeProductUpdateVariation
        });
        this.addContextMenuItem(m);
    }

    ,createProductUpdateVariation: function(btn,e) {
        var win = Ext.getCmp('commercemultilang-window-product-variation-create');
        if(win) {
            win.show(e.target);
        } else {
            var createProductVariation = MODx.load({
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
            createProductVariation.addVariationFields(this.config);
            var parentWin = Ext.getCmp('commercemultilang-window-product-update');
            parentWin.record['image'] = parentWin.record['main_image'];
            createProductVariation.fp.getForm().setValues(parentWin.record);
            // If parent product has a main image, render it on window load
            if(parentWin.record['main_image']) {
                createProductVariation.renderImageOnLoad();
            }
            createProductVariation.show(e.target);
        }
    }

    ,updateProductUpdateVariation: function(btn,e,isUpdate) {
        if (!this.menu.record || !this.menu.record.id) return false;

        var win = Ext.getCmp('commercemultilang-window-product-variation-update');
        if(win) {
            win.show(e.target);
        } else {

            var record = this.menu.record;
            // match the records to grab variation values
            var results = this.store.reader.jsonData.results;
            results.forEach(function(row,index) {
                if(row.id === record.id) {
                    record = row;
                }
            });

            var updateProductVariation = MODx.load({
                xtype: 'commercemultilang-window-product-variation-update'
                , id: 'commercemultilang-window-product-variation-update'
                , title: _('commercemultilang.product.variation_edit')
                , action: 'mgr/product/variation/update'
                , record: record
                , listeners: {
                    'success': {
                        fn: function () {
                            this.refresh();
                        }, scope: this
                    }
                }
            });


            updateProductVariation.fp.getForm().reset();
            updateProductVariation.record.languages = this.store.reader.jsonData.languages;
            updateProductVariation.record.product_id = record.id;
            updateProductVariation.fp.getForm().setValues(record);

            var langTabs = this.store.reader.jsonData.languages;
            langTabs.forEach(function (langTab, index) {
                record.langs.forEach(function (lang, index) {
                    if (langTab.lang_key === lang.lang_key) {
                        langTab['fields'] = lang;
                    }
                });
            });

            // Grab variation names for this product
            var variations = null;
            MODx.Ajax.request({
                url: this.config.url
                ,params: {
                    action: 'mgr/product/variation/getcolumns'
                    ,product_id: record.id
                }
                ,listeners: {
                    'success': {fn:function(r) {
                            variations = r.results;
                            updateProductVariation.addLanguageTabs(langTabs,variations,record);

                            updateProductVariation.renderImageOnLoad();
                            updateProductVariation.doLayout();
                            updateProductVariation.show(e.target);

                        },scope:this}
                }
            });
            updateProductVariation.show(e.target);
        }
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



CommerceMultiLang.window.ProductVariationCreate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: 'Create Product Variation'
        ,closeAction: 'close'
        ,width:600
        ,url: CommerceMultiLang.config.connectorUrl
        ,action: 'mgr/product/variation/create'
        ,allowDrop: false // Must be turned off to prevent tabs error!
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
                    ,id: 'update-product-image-select'
                    ,fieldLabel: 'Select Image'
                    ,name: 'image'
                    ,anchor:'100%'
                    ,hideSourceCombo: true
                    ,listeners: {
                        'select' : this.renderImage
                    }
                },{
                    html:'<img style="max-width:100%; margin-top:10px;" ' +
                    'src="'+ CommerceMultiLang.config.assetsUrl +'img/placeholder.jpg" />'
                    ,id:'create-product-variation-image-preview'
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
            fields.push({
                xtype: 'textfield'
                ,fieldLabel: variation['display_name']
                ,name: variation['name']
                ,anchor: '100%'
            });
        });

        Ext.getCmp('product-variation-create-right-col').add(fields);
    }
    ,renderImage:function(value) {
        var leftCol = Ext.getCmp('product-variation-create-left-col');
        var url = CommerceMultiLang.config.baseImageUrl + value.fullRelativeUrl;
        console.log(value);
        if(url.charAt(0) !== '/') {
            url = '/'+url;
        }
        leftCol.remove('create-product-variation-image-preview');
        leftCol.add({
            html: '<img style="width:100%; margin-top:10px;" src="' + url + '" />'
            ,id: 'create-product-variation-image-preview'
        });
        leftCol.doLayout();
    }
    ,renderImageOnLoad:function() {
        var leftCol = Ext.getCmp('product-variation-create-left-col');
        var url = CommerceMultiLang.config.baseImageUrl + Ext.getCmp('update-product-image-select').getValue();
        if(url.charAt(0) !== '/') {
            url = '/'+url;
        }
        leftCol.remove('create-product-variation-image-preview');
        leftCol.add({
            html: '<img style="width:100%; margin-top:10px;" src="' + url + '" />'
            ,id: 'create-product-variation-image-preview'
        });
        leftCol.doLayout();
    }

});
Ext.reg('commercemultilang-window-product-variation-create',CommerceMultiLang.window.ProductVariationCreate);


CommerceMultiLang.window.ProductVariationUpdate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('commercemultilang.product_type.update')
        ,id:'commercemultilang-window-product-variation'
        ,closeAction: 'close'
        ,url: CommerceMultiLang.config.connectorUrl
        ,action: 'mgr/product-type/variation/update'
        ,allowDrop: false // Must be turned off to prevent tabs error!
        ,keys: []
        ,fields: [{
            xtype: 'textfield'
            ,name: 'id'
            ,hidden: true
        },{
            xtype: 'modx-tabs'
            ,id: 'product-variation-update-window-tabs'
            ,defaults: { border: false ,autoHeight: true }
            ,style:'margin-top:15px;'
            ,deferredRender: false
            ,forceLayout:true
            ,border: true
            ,items: [{
                title:'General'
                ,layout:'form'
                ,items:[{
                    layout: 'column'
                    ,border: false
                    ,items: [{
                        columnWidth: .4
                        ,id:'product-variation-update-left-col'
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'modx-combo-browser'
                            ,id: 'update-product-image-select'
                            ,fieldLabel: 'Select Image'
                            ,name: 'image'
                            ,anchor:'100%'
                            ,hideSourceCombo: true
                            ,listeners: {
                                'select' : this.renderImage
                            }
                        },{
                            html:'<img style="max-width:100%; margin-top:10px;" ' +
                            'src="'+ CommerceMultiLang.config.assetsUrl +'img/placeholder.jpg" />'
                            ,id:'update-product-variation-image-preview'
                        }]
                    },{
                        columnWidth: .6
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
                    }]
                }]
            }]
        }]
    });
    CommerceMultiLang.window.ProductVariationUpdate.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.window.ProductVariationUpdate,MODx.Window,{

    addLanguageTabs: function(langTabs,variations,record) {
        //console.log(record);
        var tabs = Ext.getCmp('product-variation-update-window-tabs');
        langTabs.forEach(function(langTab) {
            var fields = [];
            variations.forEach(function(variation){
                // Make a capitalised version of the field name for the label.
                var field = new Ext.form.TextField({
                    fieldLabel: variation['display_name']
                    ,id: 'var_'+variation['name']+'_' + langTab['lang_key']
                    ,name: variation['name'] + '_' + langTab['lang_key']
                    ,value: langTab.fields ? langTab.fields[variation['name'] + '_' + langTab['lang_key']] : ''
                    ,anchor: '60%'
                });

                // Manually set the variation values after creation.
                Object.keys(record).forEach(function(key) {
                    if(key === field.name) {
                        field.setValue(record[key]);
                    }
                });
                fields.push(field);
            });

            var tab = [{
                title: langTab['name']+' ('+langTab['lang_key']+')'
                ,layout:'form'
                ,id: 'variation-update-language-tab-'+langTab['lang_key']
                ,cls:'language-tab'
                ,forceLayout: true // important! if not added new tabs will not submit.
                ,items:fields
            }];
            //console.log(tab);
            tabs.add(tab);
        });
    }

    ,renderImage:function(value) {
        var leftCol = Ext.getCmp('product-variation-update-left-col');
        var url = CommerceMultiLang.config.baseImageUrl + value.fullRelativeUrl;
        console.log(value);
        if(url.charAt(0) !== '/') {
            url = '/'+url;
        }
        leftCol.remove('update-product-variation-image-preview');
        leftCol.add({
            html: '<img style="width:100%; margin-top:10px;" src="' + url + '" />'
            ,id: 'update-product-variation-image-preview'
        });
        leftCol.doLayout();
    }
    ,renderImageOnLoad:function() {
        var leftCol = Ext.getCmp('product-variation-update-left-col');
        var url = CommerceMultiLang.config.baseImageUrl + Ext.getCmp('update-product-image-select').getValue();
        if(url) {
            if (url.charAt(0) !== '/') {
                url = '/' + url;
            }
            leftCol.remove('update-product-variation-image-preview');
            leftCol.add({
                html: '<img style="width:100%; margin-top:10px;" src="' + url + '" />'
                , id: 'update-product-variation-image-preview'
            });
            leftCol.doLayout();
        }
    }
});
Ext.reg('commercemultilang-window-product-variation-update',CommerceMultiLang.window.ProductVariationUpdate);

