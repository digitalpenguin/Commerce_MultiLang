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
        ,fields: ['id','sku','main_image','name','description','price','stock','weight','weight_unit','target',
            'properties','images','delivery_type','tax_group','langs']
        ,autoHeight: true
        ,paging: true
        ,remoteSort: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 40
        },{
            header: _('commercemultilang.product.image')
            ,dataIndex: 'main_image'
            ,fixed:true
            ,width: 140
            ,renderer: function(value){
                return '<img style="width:100%;" src="' + value + '" />';
            }
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
        }/*,{
            header: _('commercemultilang.product.position')
            ,dataIndex: 'position'
            ,width: 60
            ,editor: { xtype: 'numberfield', allowDecimal: false, allowNegative: false }
        }*/]
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
            xtype: 'commercemultilang-window-product-create'
            ,listeners: {
                'success': {fn:function() { this.refresh(); },scope:this}
            }
        });
        createProduct.addLanguageTabs(this.store.reader.jsonData.languages);
        createProduct.show(e.target);
    }

    ,updateProduct: function(btn,e,isUpdate) {
        if (!this.menu.record || !this.menu.record.id) return false;

        var updateProduct = MODx.load({
            xtype: 'commercemultilang-window-product-update'
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
        var record = this.menu.record;
        var langTabs = this.store.reader.jsonData.languages;
        langTabs.forEach(function(langTab,index) {
            record.langs.forEach(function(lang,index) {
                if(langTab.lang_key === lang.lang_key) {
                    //console.log(lang);
                    langTab['fields'] = lang;
                }
            });
            //console.log(langTabs);
        });

        updateProduct.addLanguageTabs(langTabs);
        updateProduct.doLayout();
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
});
Ext.reg('commercemultilang-grid-products',CommerceMultiLang.grid.Products);

CommerceMultiLang.window.ProductCreate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('commercemultilang.product.create')
        ,closeAction: 'close'
        ,width:600
        ,url: CommerceMultiLang.config.connectorUrl
        ,action: 'mgr/product/create'
        ,fields: [{
            style:'padding:15px 0'
            ,html:'<h4>Add Product</h4><p>Images and translations can only be added after the product has been created.</p>'
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
                    xtype: 'textfield'
                    ,fieldLabel: _('name')
                    ,name: 'name'
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
                        }]
                    }, {
                        columnWidth: .33
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'textfield'
                            ,fieldLabel: _('commercemultilang.product.price')
                            ,name: 'price'
                            ,anchor: '100%'
                        }]
                    },{
                        columnWidth: .33
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'textfield'
                            ,fieldLabel: _('commercemultilang.product.stock')
                            ,name: 'stock'
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
                            xtype: 'textfield'
                            ,fieldLabel: _('commercemultilang.product.weight')
                            ,name: 'weight'
                            ,anchor: '100%'
                        }]
                    }, {
                        columnWidth: .33
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'commercemultilang-combo-weightunit'
                            ,fieldLabel: _('commercemultilang.product.weight_unit')
                            ,name: 'weight_unit'
                            ,hiddenName: 'weight_unit'
                            ,anchor: '100%'
                        }]
                    },{
                        columnWidth: .33
                        ,layout: 'form'
                        ,items: []
                    }]
                },{
                    layout: 'column'
                    ,border: false
                    ,items: [{
                        columnWidth: .5
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'commercemultilang-combo-taxgroup'
                            ,fieldLabel: _('commercemultilang.product.tax_group')
                            ,name: 'tax_group'
                            ,hiddenName: 'tax_group'
                            ,anchor: '100%'
                        }]
                    }, {
                        columnWidth: .5
                        ,layout: 'form'
                        ,items: [{
                            xtype: 'commercemultilang-combo-deliverytype'
                            ,fieldLabel: _('commercemultilang.product.delivery_type')
                            ,name: 'delivery_type'
                            ,hiddenName: 'delivery_type'
                            ,anchor: '100%'
                        }]
                    }]
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,name: 'description'
                    ,anchor: '100%'
                }]
            }]
        }]
    });
    CommerceMultiLang.window.ProductCreate.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.window.ProductCreate,MODx.Window,{
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
        });
    }
});
Ext.reg('commercemultilang-window-product-create',CommerceMultiLang.window.ProductCreate);


