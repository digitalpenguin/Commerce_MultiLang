Commerce_MultiLang.grid.Products = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'commerce_multilang-grid-products'
        ,url: Commerce_MultiLang.config.connectorUrl
        ,baseParams: {
            action: 'mgr/product/getlist'
        }
        ,save_action: 'mgr/product/updatefromgrid'
        ,autosave: true
        ,fields: ['id','sku','main_image','name','category','category_id','type_id','type','type_name','type_variations','description','price'
            ,'price_formatted', 'stock','weight_formatted','weight','weight_unit','target', 'variation_fields',
            'product_listing','alias','properties','images','delivery_type','tax_group','langs']
        ,autoHeight: true
        ,paging: true
        ,remoteSort: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 40
        },{
            header: _('commerce_multilang.product.image')
            ,dataIndex: 'main_image'
            ,fixed:true
            ,width: 140
            ,renderer: function(value){
                if(value) {
                    var url = '';
                    if(Commerce_MultiLang.config.baseImageUrl.charAt(0) !== '/') {
                        url = '/'+ Commerce_MultiLang.config.baseImageUrl;
                    }
                    return '<img style="max-width:100%;" src="'+ url + value + '" />';
                } else {
                    return '<img style="max-width:100%;" src="'+ Commerce_MultiLang.config.assetsUrl +'img/placeholder.jpg" />';
                }
            }
        },{
            header: _('commerce_multilang.product.sku')
            ,dataIndex: 'sku'
            ,width: 100
            ,editor: { xtype: 'textfield' }
        },{
            header: _('commerce_multilang.product.name')
            ,dataIndex: 'name'
            ,width: 200
        },{
            header: _('commerce_multilang.product.category')
            ,dataIndex: 'category'
            ,width: 200
            ,renderer: function(value, meta, record) {
                return value + ' <br><span style="color:#888;">(Category ID:' +record.data['category_id']+')</span>';
            }
        },{
            header: _('commerce_multilang.product.type')
            ,dataIndex: 'type'
            ,width: 200
            ,renderer: function(value, meta, record) {
                return record.data['type_name'] + '<br><span style="color:#888;">'+record.data['type_variations']+'</span>';
            }
        },{
            header: _('commerce_multilang.product.alias')
            ,dataIndex: 'alias'
            ,width: 160
        },{
            header: _('commerce_multilang.product.stock')
            ,dataIndex: 'stock'
            ,width: 100
            ,editor: { xtype: 'textfield' }
        },{
            header: _('commerce_multilang.product.price')
            ,dataIndex: 'price_formatted'
            ,width: 100
        },{
            header: _('commerce_multilang.product.weight')
            ,dataIndex: 'weight'
            ,width: 100
            ,editor: { xtype: 'textfield' }
            ,renderer: function(value, meta, record) {
                return value+' '+record.data['weight_unit'];
            }
        }]
        ,tbar: [{
            text: _('commerce_multilang.product.create')
            ,handler: this.createProduct
            ,scope: this
        },'->',{
            xtype: 'textfield'
            ,emptyText: _('commerce_multilang.global.search') + '...'
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
    });
    Commerce_MultiLang.grid.Products.superclass.constructor.call(this,config);
};
Ext.extend(Commerce_MultiLang.grid.Products,MODx.grid.Grid,{
    windows: {}

    ,getMenu: function() {
        var m = [];
        m.push({
            text: _('commerce_multilang.product.update')
            ,handler: this.updateProduct
        });
        m.push('-');
        m.push({
            text: _('commerce_multilang.product.remove')
            ,handler: this.removeProduct
        });
        this.addContextMenuItem(m);
    }
    
    ,createProduct: function(btn,e) {
        var win = Ext.getCmp('commerce_multilang-window-product-create');
        if(win) {
            win.show(e.target);
        } else {
            var createProduct = MODx.load({
                xtype: 'commerce_multilang-window-product-create'
                ,id:'commerce_multilang-window-product-create'
                ,listeners: {
                    'success': {fn:function() { this.refresh(); },scope:this}
                }
            });
            //createProduct.addLanguageTabs(this.store.reader.jsonData.languages);
            createProduct.addCategoryLanguage(this.store.reader.jsonData.default_language,this.store.reader.jsonData.default_context);
            createProduct.show(e.target);
        }
    }

    ,updateProduct: function(btn,e,isUpdate) {
        if (!this.menu.record || !this.menu.record.id) return false;
        var win = Ext.getCmp('commerce_multilang-window-product-update');
        if(win) {
            win.show(e.target);
        } else {
            var mask = new Ext.LoadMask(Ext.get(this.el), {msg:'Loading product...'});
            mask.show();

            // Local var so it's usable throughou t whole function.
            var record = this.menu.record;

            // match the records to grab variation values
            var results = this.store.reader.jsonData.results;
            results.forEach(function(row,index) {
                if(row.id === record.id) {
                    record = row;
                }
            });

            var updateProduct = MODx.load({
                xtype: 'commerce_multilang-window-product-update'
                , title: _('commerce_multilang.product.update')
                ,id: 'commerce_multilang-window-product-update'
                , action: 'mgr/product/update'
                , record: record
                , listeners: {
                    'success': {
                        fn: function () {
                            this.refresh();
                        }, scope: this
                    }
                }
            });

            updateProduct.fp.getForm().reset();
            updateProduct.record.languages = this.store.reader.jsonData.languages;
            updateProduct.record.product_id = record.id;
            updateProduct.fp.getForm().setValues(this.menu.record);


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
                            updateProduct.createVariationGrid(variations);
                            updateProduct.addLanguageTabs(langTabs,variations,record);
                            updateProduct.doLayout();
                            //console.log(record);
                            mask.hide();
                            updateProduct.show(e.target);

                        },scope:this}
                }
            });
        }
    }
    
    ,removeProduct: function(btn,e) {
        if (!this.menu.record) return false;
        
        MODx.msg.confirm({
            title: _('commerce_multilang.product.remove')
            ,text: _('commerce_multilang.product.remove_confirm')
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

});
Ext.reg('commerce_multilang-grid-products',Commerce_MultiLang.grid.Products);

Commerce_MultiLang.window.ProductCreate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('commerce_multilang.product.create')
        ,closeAction: 'close'
        ,width:600
        ,url: Commerce_MultiLang.config.connectorUrl
        ,action: 'mgr/product/create'
        ,keys: []
        ,fields: [{
            style:'padding:15px 0'
            ,html:'<h4>Add Product</h4><p>Translations can only be added after the product has been created.</p>'
        },{
            xtype: 'modx-tabs'
            ,id: 'product-create-window-tabs'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,activeTab: 0
            ,hideMode: 'offsets'
            ,items: [{
                title:'General'
                ,layout:'form'
                ,items:[{
                    xtype: 'textfield'
                    ,name: 'id'
                    ,hidden: true
                },{
                    layout: 'column'
                    ,border: false
                    ,items: [{
                        columnWidth: .66
                        ,layout: 'form'
                        ,items: [/*{
                            xtype:'commerce_multilang-combo-categories'
                            ,fieldLabel: 'Categories'
                            ,anchor: '100%'
                            ,id: 'commerce_multilang-combo-categories'

                            ,name: 'categories'
                            ,hiddenName: 'categories[]'
                        },*/{
                            xtype: 'textfield'
                            ,fieldLabel: _('name')
                            ,name: 'name'
                            ,anchor: '100%'
                            ,autocomplete:'off'
                        }]
                    },{
                        columnWidth: .33
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'textfield'
                            ,fieldLabel: _('commerce_multilang.product.sku')
                            ,name: 'sku'
                            ,anchor: '100%'
                        }]
                    }]
                },{
                    layout: 'column'
                    ,border: false
                    ,items: [{
                        columnWidth: .66
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'commerce_multilang-combo-category'
                            ,fieldLabel: _('commerce_multilang.product.category')
                            ,id: 'product-create-category-combo'
                            ,name: 'category'
                            ,hiddenName: 'category'
                            ,anchor: '100%'
                        }]
                    },{
                        columnWidth: .33
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'textfield'
                            ,fieldLabel: _('commerce_multilang.product.price')
                            ,name: 'price'
                            ,anchor: '100%'
                        }]
                    }]
                },{
                    layout: 'column'
                    ,border: false
                    ,items: [{
                        columnWidth: .33
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'commerce_multilang-combo-product-type'
                            ,fieldLabel: _('commerce_multilang.product.type')
                            ,name: 'type'
                            ,hiddenName: 'type'
                            ,anchor: '100%'
                        }]
                    },{
                        columnWidth: .33
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'textfield'
                            ,fieldLabel: _('commerce_multilang.product.stock')
                            ,name: 'stock'
                            ,anchor: '100%'
                            ,value: 1
                        }]
                    },{
                        columnWidth: .20
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'textfield'
                            ,fieldLabel: _('commerce_multilang.product.weight')
                            ,name: 'weight'
                            ,anchor: '100%'
                            ,value: 1
                        }]
                    },{
                        columnWidth: .13
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'commerce_multilang-combo-weightunit'
                            ,fieldLabel: _('commerce_multilang.product.unit')
                            ,name: 'weight_unit'
                            ,hiddenName: 'weight_unit'
                            ,anchor: '100%'
                            ,value:'kg'
                        }]
                    }]
                },{
                    layout: 'column'
                    ,border: false
                    ,items: [{
                        columnWidth: .5
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'commerce_multilang-combo-taxgroup'
                            ,fieldLabel: _('commerce_multilang.product.tax_group')
                            ,name: 'tax_group'
                            ,hiddenName: 'tax_group'
                            ,anchor: '100%'
                            ,value:1
                        }]
                    }, {
                        columnWidth: .5
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'commerce_multilang-combo-deliverytype'
                            ,fieldLabel: _('commerce_multilang.product.delivery_type')
                            ,name: 'delivery_type'
                            ,hiddenName: 'delivery_type'
                            ,anchor: '100%'
                            ,value:1
                        }]
                    }]
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,name: 'description'
                    ,anchor: '100%'
                }]
            }]
        }/*,{
            //TODO: Add image selection
        }*/]
    });
    Commerce_MultiLang.window.ProductCreate.superclass.constructor.call(this,config);
};
Ext.extend(Commerce_MultiLang.window.ProductCreate,MODx.Window,{
    addLanguageTabs: function(languages) {
        var tabs = Ext.getCmp('product-create-window-tabs');
        var imageTab = [{
            title: 'Images'
            ,disabled:true
        }];
        tabs.add(imageTab);
        languages.forEach(function(item) {
            var tab = [{
                title: item['name']+' ('+item['lang_key']+')'
                ,disabled:true
            }];

            tabs.add(tab);
            //var lastTab = tabs.items.length-1;
            //tabs.items.items[lastTab].disable();
            // Set the current language on the category combo

        });
    }
    ,addCategoryLanguage: function(defaultLanguage,defaultContext) {
        // Set the default language on the category combo
        var comboStore = Ext.getCmp('product-create-category-combo').getStore();
        comboStore.setBaseParam('lang_key',defaultLanguage);
        comboStore.setBaseParam('context_key',defaultContext);
        comboStore.setBaseParam('action','mgr/product/category/getlist');
    }
});
Ext.reg('commerce_multilang-window-product-create',Commerce_MultiLang.window.ProductCreate);


