CommerceMultiLang.window.ProductUpdate = function(config) {

    Ext.applyIf(config,{
        title: _('commerce_multilang.product.update')
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
                            ,fieldLabel: _('commerce_multilang.product.sku')
                            ,name: 'sku'
                            ,anchor: '100%'
                        },{
                            xtype: 'commercemultilang-combo-product-type'
                            ,fieldLabel: _('commerce_multilang.product.type')
                            ,name: 'type'
                            ,hiddenName: 'type'
                            ,anchor: '100%'
                        },{
                            xtype: 'textfield'
                            ,fieldLabel: _('commerce_multilang.product.price')
                            ,name: 'price'
                            ,anchor: '100%'
                        },{
                            xtype: 'textfield'
                            ,fieldLabel: _('commerce_multilang.product.stock')
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
                                    ,fieldLabel: _('commerce_multilang.product.weight')
                                    ,name: 'weight'
                                    ,anchor: '100%'
                                }]
                            },{
                                columnWidth: .33
                                ,layout: 'form'
                                ,items: [{
                                    xtype: 'commercemultilang-combo-weightunit'
                                    ,fieldLabel: _('commerce_multilang.product.weight_unit')
                                    ,name: 'weight_unit'
                                    ,hiddenName: 'weight_unit'
                                    ,anchor: '100%'
                                }]
                            }]
                        },{
                            xtype: 'commercemultilang-combo-deliverytype'
                            ,fieldLabel: _('commerce_multilang.product.delivery_type')
                            ,name: 'delivery_type'
                            ,hiddenName: 'delivery_type'
                            ,anchor: '100%'
                        },{
                            xtype: 'commercemultilang-combo-taxgroup'
                            ,fieldLabel: _('commerce_multilang.product.tax_group')
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
                    html:'<h2>'+_('commerce_multilang.product_variation.product_variations')+'</h2>' +
                    '<p>'+_('commerce_multilang.product_variation.intro')+'</p>'
                }]
            }]
        }]
    });
    CommerceMultiLang.window.ProductUpdate.superclass.constructor.call(this,config);
    this.on('afterrender', function() {
        var languages = this.config.record.languages;
        languages.forEach(function (language, index) {
            if (MODx.loadRTE) {
                MODx.loadRTE('product-content-'+language.lang_key);
            }
        });

    });
};
Ext.extend(CommerceMultiLang.window.ProductUpdate,MODx.Window,{
    /**
     * This function generates each language tab in the update window.
     * It needs to be run before the window is shown so everything is rendered correctly.
     * Also adds vertical tabs which include any variation fields.
     * @param langTabs
     * @param variations
     * @param record
     */
    addLanguageTabs: function(langTabs,variations,record) {
        var tabs = Ext.getCmp('product-update-window-tabs');
        langTabs.forEach(function(langTab) {
            var fields = [];
            variations.forEach(function(variation){
                // Make a capitalised version of the field name for the label.
                var name = variation['name'].charAt(0).toUpperCase() + variation['name'].slice(1);
                var field = new Ext.form.TextField({
                    fieldLabel: name
                    ,id: variation['name']+'_' + langTab['lang_key']
                    ,name: variation['name'] + '_' + langTab['lang_key']
                    ,value: langTab.fields ? langTab.fields[variation['name'] + '_' + langTab['lang_key']] : ''
                    ,anchor: '80%'
                });

                // Manually set the variation values after creation.
                Object.keys(record).forEach(function(key) {
                    //console.log(key, record[key]);
                    if(key === field.name) {
                        field.setValue(record[key]);
                    }
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

                        ,style:'margin:0 5px 0 30px;'
                        ,items:[{
                            layout:'column'
                            ,items:[{
                                columnWidth:.5
                                ,layout:'form'
                                ,forceLayout:true
                                ,items:[{
                                    xtype: 'textfield'
                                    ,fieldLabel: _('name')
                                    ,name: 'name_'+langTab['lang_key']
                                    ,value: langTab.fields ? langTab.fields['name']: ''
                                    ,anchor: '100%'
                                },{
                                    xtype: 'commercemultilang-combo-category'
                                    ,fieldLabel: _('commerce_multilang.product.category')
                                    ,id: 'product-update-category-combo'+langTab['lang_key']
                                    ,name: 'category_'+langTab['lang_key']
                                    ,hiddenName: 'category_'+langTab['lang_key']
                                    ,value: langTab.fields ? langTab.fields['category']: ''
                                    ,anchor: '100%'
                                }]
                            },{
                                columnWidth: .5
                                ,layout: 'form'
                                ,forceLayout:true
                                ,items: [{
                                    xtype: 'textarea'
                                    ,fieldLabel: _('description')
                                    ,id:'product-description-'+langTab['lang_key']
                                    ,name: 'description_'+langTab['lang_key']
                                    ,value: langTab.fields ? langTab.fields['description']: ''
                                    ,anchor:'100%'
                                    ,height: 101
                                }]

                            }]
                        },{
                            xtype: 'textarea'
                            ,fieldLabel: _('content')
                            ,id:'product-content-'+langTab['lang_key']
                            ,name: 'content_'+langTab['lang_key']
                            ,value: langTab.fields ? langTab.fields['content']: ''
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
            ,variations: variations
            ,fields: this.getVariationGridFields(variations)
            ,columns: this.getVariationColumns(variations)
        });
        Ext.getCmp('variation-tab').add(grid);
        return true;
    }

    ,getVariationGridFields: function(variations) {
        var fields = ['id','image','product_id','name','alias','langs',
            'sku','weight','weight_formatted','weight_unit','price','price_formatted','stock'];
        variations.forEach(function(variation) {
            var lcName = variation['name'].toLowerCase();
            fields.push(lcName);
        });
        return fields;
    }

    ,getVariationColumns: function(variations) {
        var columns = [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 10
        },{
            header: _('commerce_multilang.product.image')
            ,dataIndex: 'image'
            ,fixed:true
            ,width: 100
            ,renderer: function(value, meta, record) {
                if(value) {
                    var url = '';
                    if(CommerceMultiLang.config.baseImageUrl.charAt(0) !== '/') {
                        url = '/'+CommerceMultiLang.config.baseImageUrl;
                    }
                    return '<img style="max-width:100%;" title="'+record['name']+'"  src="' + url + value + '">';
                } else {
                    return '<img style="max-width:100%;" title="'+record['name']+'"  src="'+ CommerceMultiLang.config.assetsUrl +'img/placeholder.jpg">';
                }
            }
        },{
            header: _('commerce_multilang.product.sku')
            ,dataIndex: 'sku'
        },{
            header: _('commerce_multilang.product.price')
            ,dataIndex: 'price_formatted'
        },{
            header: _('commerce_multilang.product.stock')
            ,dataIndex: 'stock'
        },{
            header: _('commerce_multilang.product.weight')
            ,dataIndex: 'weight_formatted'
        }];
        variations.forEach(function(variation) {

            var newCol = {
                header: variation['display_name'],
                dataIndex: variation['name']
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
        ,fields: ['id','image','product_id','title','description','languages',
            'alt','main','position','langs']
        ,autoHeight: true
        ,paging: true
        ,pageSize: 10
        ,remoteSort: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 40
        },{
            header: _('commerce_multilang.product_image.image')
            ,dataIndex: 'image'
            ,fixed:true
            ,width: 100
            ,renderer: function(value){
                if(value) {
                    var url = '';
                    if(CommerceMultiLang.config.baseImageUrl.charAt(0) !== '/') {
                        url = '/'+ CommerceMultiLang.config.baseImageUrl;
                    }
                    return '<img style="max-width:100%;" src="' + url + value + '" />';
                } else {
                    return '<img style="max-width:100%;" src="'+ CommerceMultiLang.config.assetsUrl +'img/placeholder.jpg" />';
                }
            }
        },{
            header: _('commerce_multilang.product_image.title')
            ,dataIndex: 'title'
            ,width: 100
        },{
            header: _('commerce_multilang.product_image.description')
            ,dataIndex: 'description'
            ,width: 200
        },{
            header: _('commerce_multilang.product_image.main')
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
            header: _('commerce_multilang.product_image.position')
            ,dataIndex: 'position'
            ,width: 60
            ,hidden:true
            //,editor: { xtype: 'numberfield', allowDecimal: false, allowNegative: false }
        }]
        ,tbar: ['->',{
            text: _('commerce_multilang.product_image.add')
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
            text: _('commerce_multilang.product_image.edit')
            ,handler: this.updateProductImage
        });
        m.push({
            text: _('commerce_multilang.product_image.make_main')
            ,handler: this.makeMainImage
        });
        m.push('-');
        m.push({
            text: _('commerce_multilang.product_image.remove')
            ,handler: this.removeProductImage
        });
        this.addContextMenuItem(m);
    }

    ,createProductImage: function(btn,e) {
        var languages = JSON.stringify(this.languages);
        var createProductImage = MODx.load({
            xtype: 'commercemultilang-window-product-image-create'
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

        // Local var so it's usable throughout whole function.
        var record = this.menu.record;

        var updateProductImage = MODx.load({
            xtype: 'commercemultilang-window-product-image-update'
            ,title: _('commerce_multilang.product_image.update')
            ,action: 'mgr/product/image/update'
            ,record: record
            ,listeners: {
                'success': {fn:function() { this.refresh(); },scope:this}
            }
        });

        updateProductImage.fp.getForm().reset();


        var langTabs = this.store.reader.jsonData.languages;
        langTabs.forEach(function (langTab, index) {
            record.langs.forEach(function (lang, index) {
                if (langTab.lang_key === lang.lang_key) {
                    langTab['fields'] = lang;
                }
            });
        });
        updateProductImage.addLanguageTabs(langTabs,record);
        updateProductImage.fp.getForm().setValues(record);

        langTabs.forEach(function(langTab){
            updateProductImage.renderImageOnLoad(langTab);
        });

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
            title: _('commerce_multilang.product_image.remove')
            ,text: _('commerce_multilang.product_image.remove_confirm')
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

CommerceMultiLang.window.ProductImageCreate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('commerce_multilang.product_image.add')
        ,id:'commercemultilang-window-product-image-create'
        ,closeAction: 'close'
        ,url: CommerceMultiLang.config.connectorUrl
        ,action: 'mgr/product/image/create'
        ,fields:[{
            layout:'column',
            items:[{
                columnWidth:.4
                ,id:'product-image-create-left-col'
                ,layout:'form'
                ,items:[{
                    xtype: 'modx-combo-browser'
                    ,id: 'create-product-image-select'
                    ,fieldLabel: 'Select Image'
                    ,name: 'image'
                    ,anchor:'100%'
                    ,rootId: '/'
                    ,rootVisible:true
                    ,hideSourceCombo: true
                    ,listeners: {
                        'select' : function(value){
                            Ext.getCmp('commercemultilang-window-product-image-create').renderImage(value);
                        }
                    }
                },{
                    html:'<img style="max-width:100%; margin-top:10px;" ' +
                    'src="'+ CommerceMultiLang.config.assetsUrl +'img/placeholder.jpg" />'
                    ,id:'create-product-image-preview'
                }]
            },{
                columnWidth:.6
                ,layout: 'form'
                ,items:[{
                    xtype: 'textfield'
                    ,fieldLabel: _('commerce_multilang.product_image.title')
                    ,name: 'title'
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('commerce_multilang.product_image.alt')
                    ,name: 'alt'
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,name: 'description'
                    ,anchor: '100%'
                }]
            }]
        }]
    });
    CommerceMultiLang.window.ProductImageCreate.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.window.ProductImageCreate,MODx.Window,{
    renderImage:function(value) {
        var leftCol = Ext.getCmp('product-image-create-left-col');
        var url = value.fullRelativeUrl;
        //console.log(value);
        if(url.charAt(0) !== '/') {
            url = '/'+url;
        }
        leftCol.remove('create-product-image-preview');
        leftCol.add({
            html: '<img style="width:100%; margin-top:10px;" src="' + url + '" />'
            ,id: 'create-product-image-preview'
        });
        leftCol.doLayout();
    }
});
Ext.reg('commercemultilang-window-product-image-create',CommerceMultiLang.window.ProductImageCreate);

CommerceMultiLang.window.ProductImageUpdate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('commerce_multilang.product_image.update')
        ,id:'commercemultilang-window-product-image-update'
        ,closeAction: 'close'
        ,url: CommerceMultiLang.config.connectorUrl
        ,action: 'mgr/product/image/update'
        ,fields: [{
            xtype: 'textfield'
            ,name: 'id'
            ,hidden: true
        },{
            xtype: 'modx-tabs'
            ,style:'padding:15px 0 0 0'
            ,id: 'product-image-update-window-tabs'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,activeTab: 0
            ,hideMode: 'offsets'
            ,deferredRender: false
        }]
    });
    CommerceMultiLang.window.ProductImageUpdate.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.window.ProductImageUpdate,MODx.Window,{
    addLanguageTabs: function(langTabs,record) {
        //console.log(record);
        var tabs = Ext.getCmp('product-image-update-window-tabs');
        langTabs.forEach(function(langTab) {

            //Grab the correct record for this language.
            var langRecord = '';
            record.langs.forEach(function(lang) {
                if(lang['lang_key'] === langTab['lang_key']) {
                    langRecord = lang;
                }
            });

            var tab = [{
                title: langTab['name']+' ('+langTab['lang_key']+')'
                ,layout:'form'
                ,id: 'variation-update-language-tab-'+langTab['lang_key']
                ,cls:'language-tab'
                ,forceLayout: true // important! if not added new tabs will not submit.
                ,items:[{
                    layout:'column',
                    items:[{
                        columnWidth:.4
                        ,id:'product-image-update-left-col-'+langTab['lang_key']
                        ,layout:'form'
                        ,items:[{
                            xtype: 'modx-combo-browser'
                            ,id: 'update-product-image-select-'+langTab['lang_key']
                            ,fieldLabel: 'Select Image'
                            //,source: CommerceMultiLang.config.institutionMediaSource
                            ,name: 'image_'+langTab['lang_key']
                            ,anchor:'100%'
                            ,rootId: '/'
                            //,openTo: 'institution/'+config.record['alias']+'/'
                            ,rootVisible:true
                            ,hideSourceCombo: true
                            ,value:langRecord['image']
                            ,listeners: {
                                'select' : function(value){
                                    Ext.getCmp('commercemultilang-window-product-image-update').renderImage(value,langTab);
                                }
                            }
                        },{
                            html:'<img style="max-width:100%; margin-top:10px;" ' +
                            'src="'+ CommerceMultiLang.config.assetsUrl +'img/placeholder.jpg" />'
                            ,id:'update-product-image-preview-'+langTab['lang_key']
                        }]
                    },{
                        columnWidth:.6
                        ,layout: 'form'
                        ,items:[{
                            xtype: 'textfield'
                            ,fieldLabel: _('commerce_multilang.product_image.title')
                            ,name: 'title_'+langTab['lang_key']
                            ,anchor: '100%'
                            ,value: langRecord['title']
                        },{
                            xtype: 'textfield'
                            ,fieldLabel: _('commerce_multilang.product_image.alt')
                            ,name: 'alt_'+langTab['lang_key']
                            ,anchor: '100%'
                            ,value: langRecord['alt']
                        },{
                            xtype: 'textarea'
                            ,fieldLabel: _('description')
                            ,name: 'description_'+langTab['lang_key']
                            ,anchor: '100%'
                            ,value: langRecord['description']
                        }]
                    }]
                }]
            }];
            tabs.add(tab);
        });
    }

    ,renderImage:function(value,langTab) {
        var leftCol = Ext.getCmp('product-image-update-left-col-'+langTab['lang_key']);
        var url = value.fullRelativeUrl;use
        //console.log(value);
        if(url.charAt(0) !== '/') {
            url = '/'+url;
        }
        leftCol.remove('update-product-image-preview-'+langTab['lang_key']);
        leftCol.add({
            html: '<img style="width:100%; margin-top:10px;" src="' + url + '" />'
            ,id: 'update-product-image-preview-'+langTab['lang_key']
        });
        leftCol.doLayout();
    }
    ,renderImageOnLoad:function(langTab) {
        var leftCol = Ext.getCmp('product-image-update-left-col-'+langTab['lang_key']);
        var url = CommerceMultiLang.config.baseImageUrl + Ext.getCmp('update-product-image-select-'+langTab['lang_key']).getValue();
        if(url) {
            if (url.charAt(0) !== '/') {
                url = '/' + url;
            }
            leftCol.remove('update-product-image-preview-'+langTab['lang_key']);
            leftCol.add({
                html: '<img style="width:100%; margin-top:10px;" src="' + url + '" />'
                , id: 'update-product-image-preview-'+langTab['lang_key']
            });
            leftCol.doLayout();
        }
    }
});
Ext.reg('commercemultilang-window-product-image-update',CommerceMultiLang.window.ProductImageUpdate);

