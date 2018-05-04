CommerceMultiLang.window.ProductUpdate = function(config) {

    Ext.applyIf(config,{
        title: _('commercemultilang.product.update')
        ,closeAction: 'close'
        ,width:1000
        ,id:'commercemultilang-window-product-update'
        ,url: CommerceMultiLang.config.connectorUrl
        ,action: 'mgr/product/update'
        ,allowDrop:false // Must be turned off to prevent vtabs error!
        ,keys:[]
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
                ,cls: 'main-wrapper'
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
                    html:'<h2 style="margin-top:5px; width:87%; overflow:hidden;">'+ config.record['name'] +'</h2>' +
                    '<p style="max-height:20px; width:87%; overflow:hidden;">'+ config.record['description'] +'</p>'

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
                            xtype: 'commercemultilang-combo-product-type'
                            ,fieldLabel: _('commercemultilang.product.type')
                            ,name: 'type'
                            ,hiddenName: 'type'
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
                            layout: 'column'
                            ,border: false
                            ,items: [{
                                columnWidth: .66
                                ,layout: 'form'
                                ,items: [{
                                    xtype: 'textfield'
                                    ,fieldLabel: _('commercemultilang.product.weight')
                                    ,name: 'weight'
                                    ,anchor: '100%'
                                }]
                            },{
                                columnWidth: .33
                                ,layout: 'form'
                                ,items: [{
                                    xtype: 'commercemultilang-combo-weightunit'
                                    ,fieldLabel: _('commercemultilang.product.weight_unit')
                                    ,name: 'weight_unit'
                                    ,hiddenName: 'weight_unit'
                                    ,anchor: '100%'
                                }]
                            }]
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
                    },{
                        columnWidth: .66
                        ,layout:'form'
                        ,items: [{
                            xtype: 'commercemultilang-grid-product-images'
                            ,style:'margin-top:-23px;'
                            ,baseParams:{
                                action: 'mgr/product/image/getlist'
                                ,product_id: config.record.id
                            }
                        }]
                    }]
                }]
            },{
                title:'Variations'
                ,layout:'form'
                ,id:'variation-tab'
                ,cls: 'main-wrapper'
                ,items:[{
                    html:'<h2>'+_('commercemultilang.product_variation.product_variations')+'</h2>' +
                    '<p>'+_('commercemultilang.product_variation.intro')+'</p>'


                }]
            }]
        }]
    });
    CommerceMultiLang.window.ProductUpdate.superclass.constructor.call(this,config);
    this.on('afterrender', function() {
        var languages = this.config.record.languages;
        languages.forEach(function (language, index) {
            if (MODx.loadRTE) {
                MODx.loadRTE('product-description-'+language.lang_key);
            }
        });

    });
};
Ext.extend(CommerceMultiLang.window.ProductUpdate,MODx.Window,{

    /**
     * This function generates each language tab in the update window.
     * It needs to be run before the window is shown so everything is rendered correctly.
     * @param langTabs
     * @param variations
     */
    addLanguageTabs: function(langTabs,variations) {
        var tabs = Ext.getCmp('product-update-window-tabs');
        langTabs.forEach(function(langTab) {
            var fields = [];
            variations.forEach(function(variation){
                var field = new Ext.form.TextField({
                    fieldLabel: variation['name']
                    ,id: variation['name']+'_' + langTab['lang_key']
                    ,name: variation['name'] + '_' + langTab['lang_key']
                    ,value: langTab.fields ? langTab.fields[variation['name'] + '_' + langTab['lang_key']] : ''
                    ,anchor: '80%'
                });
                fields.push(field);
            });

            var tab = [{
                title: langTab['name']+' ('+langTab['lang_key']+')'
                ,layout:'form'
                ,cls:'language-tab'
                ,forceLayout:true // important! if not added new tabs will not submit.
                ,items:[{
                    html:'<h2 style="margin:20px 15px">'+ langTab['name']+' ('+langTab['lang_key']+')' +'</h2>'
                },{
                    xtype: 'modx-vtabs'
                    ,defaults: { border: false ,autoHeight: true }
                    ,border: true

                    ,items:[{
                        title: 'Main'
                        ,layout:'form'
                        ,forceLayout:true
                        ,cls: 'main-wrapper'
                        ,items:[{
                            xtype: 'textfield'
                            ,fieldLabel: _('name')
                            ,name: 'name_'+langTab['lang_key']
                            ,value: langTab.fields ? langTab.fields['name']: ''
                            ,anchor: '60%'
                        },{
                            xtype: 'commercemultilang-combo-category'
                            ,fieldLabel: _('commercemultilang.product.category')
                            ,id: 'product-update-category-combo'+langTab['lang_key']
                            ,name: 'category_'+langTab['lang_key']
                            ,hiddenName: 'category_'+langTab['lang_key']
                            ,value: langTab.fields ? langTab.fields['category']: ''
                            ,anchor: '60%'
                        },{
                            xtype: 'textarea'
                            ,fieldLabel: _('description')
                            ,id:'product-description-'+langTab['lang_key']
                            ,name: 'description_'+langTab['lang_key']
                            ,value: langTab.fields ? langTab.fields['description']: ''
                            ,anchor: '100%'
                        }]
                    },{
                        title: 'Variations'
                        ,id: 'language-variation-vtab-'+langTab['lang_key']
                        ,layout:'form'
                        ,forceLayout:true
                        ,cls: 'main-wrapper'
                        ,items: fields
                    }]
                }]
            }];
            //console.log(tab);
            tabs.add(tab);

            // Set the current language on the category combo
            var comboStore = Ext.getCmp('product-update-category-combo'+langTab['lang_key']).getStore();
            comboStore.setBaseParam('lang_key',langTab['lang_key']);
            comboStore.setBaseParam('context_key',langTab['context_key']);
            comboStore.setBaseParam('lang_name',langTab['name']);
            comboStore.setBaseParam('action','mgr/product/category/getlist');
        });
    }

    ,createVariationGrid: function(variations) {
        var grid = new CommerceMultiLang.grid.ProductUpdateVariations({
            baseParams:{
                action: 'mgr/product/variation/getlist'
                ,product_id: this.config.record.id
            }
            ,fields: this.getVariationGridFields(variations)
            ,columns: this.getVariationColumns(variations)
        });
        Ext.getCmp('variation-tab').add(grid);
        return true;
    }

    ,getVariationGridFields: function(variations) {
        var fields = ['id','image','product_id','name'];
        variations.forEach(function(variation) {
            fields.push(variation['name']);
        });
        return fields;
    }

    ,getVariationColumns: function(variations) {
        var columns = [{
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
        }];
        variations.forEach(function(variation) {
            var newCol = {
                header: variation['name'],
                dataIndex: variation['name'],
            };
            columns.push(newCol);
        });

        return columns;
    }
});
Ext.reg('commercemultilang-window-product-update',CommerceMultiLang.window.ProductUpdate);



CommerceMultiLang.grid.ProductImages = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'commercemultilang-grid-product-images'
        ,url: CommerceMultiLang.config.connectorUrl
        ,save_action: 'mgr/product/image/updatefromgrid'
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
            ,renderer: function(value){
                if(value) {
                    return '<img style="max-width:100%;" src=' + value + '"/" />';
                } else {
                    return '<img style="max-width:100%;" src="'+ CommerceMultiLang.config.assetsUrl +'img/placeholder.jpg" />';
                }
            }
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
            ,renderer: function(value) {
                if(value) {
                    return '<i class="icon green icon-check"></i>';
                } else {
                    return '<i class="icon red icon-close">';
                }
            }
        },{
            header: _('commercemultilang.product_image.position')
            ,dataIndex: 'position'
            ,width: 60
            ,hidden:true
            //,editor: { xtype: 'numberfield', allowDecimal: false, allowNegative: false }
        }]
        ,tbar: ['->',{
            text: _('commercemultilang.product_image.add')
            ,handler: this.createProductImage
            ,scope: this
        }]
        ,listeners: {
            'render': function(grid) {
                grid.languages = Ext.getCmp('commercemultilang-window-product-update').config.record.languages;
                grid.product_id = Ext.getCmp('commercemultilang-window-product-update').config.record.id;
            }
        }
    });
    CommerceMultiLang.grid.ProductImages.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.grid.ProductImages,MODx.grid.Grid,{
    windows: {}

    ,getMenu: function() {
        var m = [];
        m.push({
            text: _('commercemultilang.product_image.edit')
            ,handler: this.updateProductImage
        });
        m.push({
            text: _('commercemultilang.product_image.make_main')
            ,handler: this.makeMainImage
        });
        m.push('-');
        m.push({
            text: _('commercemultilang.product_image.remove')
            ,handler: this.removeProductImage
        });
        this.addContextMenuItem(m);
    }

    ,createProductImage: function(btn,e) {
        var languages = JSON.stringify(this.languages);
        var createProductImage = MODx.load({
            xtype: 'commercemultilang-window-product-image'
            ,baseParams: {
                action: 'mgr/product/image/create'
                ,languages: languages
                ,product_id: Ext.getCmp('commercemultilang-window-product-update').config.record.id
            }
            ,listeners: {
                'success': {fn:function() { this.refresh(); },scope:this}
            }
        });

        createProductImage.show(e.target);
    }

    ,updateProductImage: function(btn,e,isUpdate) {
        if (!this.menu.record || !this.menu.record.id) return false;

        var updateProductImage = MODx.load({
            xtype: 'commercemultilang-window-product-image'
            ,title: _('commercemultilang.product_image.update')
            ,action: 'mgr/product/image/update'
            ,record: this.menu.record
            ,listeners: {
                'success': {fn:function() { this.refresh(); },scope:this}
            }
        });

        updateProductImage.fp.getForm().reset();
        updateProductImage.fp.getForm().setValues(this.menu.record);
        updateProductImage.show(e.target);
    }

    ,makeMainImage: function(btn,e,isUpdate) {
        if (!this.menu.record || !this.menu.record.id) return false;
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'mgr/product/image/makemain'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:function(r) {
                        this.refresh();
                        Ext.getCmp('commercemultilang-grid-products').refresh();
                    },scope:this}
            }
        });
    }

    ,removeProductImage: function(btn,e) {
        if (!this.menu.record) return false;

        MODx.msg.confirm({
            title: _('commercemultilang.product_image.remove')
            ,text: _('commercemultilang.product_image.remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/product/image/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:function(r) { this.refresh(); },scope:this}
            }
        });
    }
});
Ext.reg('commercemultilang-grid-product-images',CommerceMultiLang.grid.ProductImages);


CommerceMultiLang.window.ProductImage = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('commercemultilang.product_image.add')
        ,closeAction: 'close'
        ,url: CommerceMultiLang.config.connectorUrl
        ,action: 'mgr/product/image/create'
        ,fields: [{
            xtype: 'textfield'
            ,name: 'id'
            ,hidden: true
        },{
            xtype: 'textfield'
            ,fieldLabel: _('commercemultilang.product_image.title')
            ,name: 'title'
            ,anchor: '100%'
            ,autocomplete:'off'
        },{
            xtype: 'modx-combo-browser'
            ,id: 'update-product-image-select-' + Ext.id()
            ,fieldLabel: 'Select Image'
            //,source: CommerceMultiLang.config.institutionMediaSource
            ,name: 'image'
            ,anchor:'100%'
            ,rootId: '/'
            //,openTo: 'institution/'+config.record['alias']+'/'
            ,rootVisible:true
            ,hideSourceCombo: true
        },{
            xtype: 'textfield'
            ,fieldLabel: _('commercemultilang.product_image.alt')
            ,name: 'alt'
            ,anchor: '100%'
            ,autocomplete:'off'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,anchor: '100%'
        }]
    });
    CommerceMultiLang.window.ProductImage.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.window.ProductImage,MODx.Window);
Ext.reg('commercemultilang-window-product-image',CommerceMultiLang.window.ProductImage);