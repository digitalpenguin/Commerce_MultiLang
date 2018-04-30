CommerceMultiLang.combo.ProductType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: CommerceMultiLang.config.connectorUrl
        ,baseParams: {
            action: 'mgr/product-type/getlist'
        }
        ,fields: ['id','name']
        ,mode: 'remote'
        ,displayField: 'name'
        ,valueField: 'id'
        ,typeAhead: true
        ,editable:true
        ,forceSelection:true
    });
    CommerceMultiLang.combo.ProductType.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.combo.ProductType,MODx.combo.ComboBox);
Ext.reg('commercemultilang-combo-product-type',CommerceMultiLang.combo.ProductType);

CommerceMultiLang.combo.Language = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: CommerceMultiLang.config.connectorUrl
        ,baseParams: {
            action: 'mgr/product-type/variation/language/getlist'
        }
        ,fields: ['lang_key','name']
        ,mode: 'remote'
        ,displayField: 'name'
        ,valueField: 'lang_key'
        ,typeAhead: true
        ,editable:true
        ,forceSelection:true
    });
    CommerceMultiLang.combo.Language.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.combo.Language,MODx.combo.ComboBox);
Ext.reg('commercemultilang-combo-language',CommerceMultiLang.combo.Language);

CommerceMultiLang.combo.WeightUnit = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: CommerceMultiLang.config.connectorUrl
        ,baseParams: {
            action: 'mgr/product/weightunit/getlist'
        }
        ,fields: ['id','weight_unit']
        ,mode: 'remote'
        ,displayField: 'weight_unit'
        ,valueField: 'weight_unit'
        ,typeAhead: true
        ,editable:true
        ,forceSelection:true
    });
    CommerceMultiLang.combo.WeightUnit.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.combo.WeightUnit,MODx.combo.ComboBox);
Ext.reg('commercemultilang-combo-weightunit',CommerceMultiLang.combo.WeightUnit);

CommerceMultiLang.combo.TaxGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: CommerceMultiLang.config.connectorUrl
        ,baseParams: {
            action: 'mgr/product/taxgroup/getlist'
        }
        ,fields: ['id','name']
        ,mode: 'remote'
        ,displayField: 'name'
        ,valueField: 'id'
        ,typeAhead: true
        ,editable:true
        ,forceSelection:true
    });
    CommerceMultiLang.combo.TaxGroup.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.combo.TaxGroup,MODx.combo.ComboBox);
Ext.reg('commercemultilang-combo-taxgroup',CommerceMultiLang.combo.TaxGroup);

CommerceMultiLang.combo.DeliveryType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: CommerceMultiLang.config.connectorUrl
        ,baseParams: {
            action: 'mgr/product/deliverytype/getlist'
        }
        ,fields: ['id','name']
        ,mode: 'remote'
        ,displayField: 'name'
        ,valueField: 'id'
        ,typeAhead: true
        ,editable:true
        ,forceSelection:true
    });
    CommerceMultiLang.combo.DeliveryType.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.combo.DeliveryType,MODx.combo.ComboBox);
Ext.reg('commercemultilang-combo-deliverytype',CommerceMultiLang.combo.DeliveryType);

CommerceMultiLang.combo.Category = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: CommerceMultiLang.config.connectorUrl
        ,baseParams: {
            action: 'mgr/product/category/getlist'
        }
        ,fields: ['id','pagetitle', {
            name: 'display',
            convert: function(v, rec) { return rec['pagetitle'] + ' - (Category ID:' + rec['id'] + ')'}
        }]
        ,mode: 'remote'
        ,displayField: 'display'
        ,valueField: 'id'
        ,typeAhead: true
        ,editable:true
        ,forceSelection:true
    });
    CommerceMultiLang.combo.Category.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang.combo.Category,MODx.combo.ComboBox);
Ext.reg('commercemultilang-combo-category',CommerceMultiLang.combo.Category);

CommerceMultiLang.combo.Categories = function (config, getStore) {
    config = config || {};
    Ext.applyIf(config, {
        name:'categories[]'
        ,displayField: 'pagetitle'
        ,valueField: 'id'
        ,fields: ['pagetitle', 'id']
        ,mode: 'remote'
        ,triggerAction: 'all'
        ,forceSelection: true
        ,allowAddNewData:true
        ,preventDuplicates:true
        ,renderFieldButtons:false
        ,pageSize: 20
        ,url: CommerceMultiLang.config.connectorUrl
        ,baseParams:{
            action: 'mgr/product/category/getList'
        }
        ,minChars:0
        ,lazyInit: false
        ,listeners: {
            'focus': function(combo) {
                combo.doQuery('',true);
            }
        }
    });
    Ext.applyIf(config,{
        store: new Ext.data.JsonStore({
            url: config.url
            ,root: 'results'
            ,totalProperty: 'total'
            ,fields: config.fields
            ,errorReader: MODx.util.JSONReader
            ,baseParams: config.baseParams || {}
            ,remoteSort: config.remoteSort || false
            ,autoDestroy: true
        })

    });
    if (getStore === true) {
        config.store.load();
        return config.store;
    }

    CommerceMultiLang.combo.Categories.superclass.constructor.call(this, config);
    this.config = config;
    return this;
};
Ext.extend(CommerceMultiLang.combo.Categories, Ext.ux.form.SuperBoxSelect,{
    addItemBox : function(itemVal,itemDisplay,itemCaption, itemClass, itemStyle) {
        var hConfig, parseStyle = function(s){
            var ret = '';
            switch(typeof s){
                case 'function' :
                    ret = s.call();
                    break;
                case 'object' :
                    for(var p in s){
                        ret+= p +':'+s[p]+';';
                    }
                    break;
                case 'string' :
                    ret = s + ';';
            }
            return ret;
        }, itemKey = Ext.id(null,'sbx-item'), box = new Ext.ux.form.SuperBoxSelectItem({
            owner: this,
            disabled: this.disabled,
            renderTo: this.wrapEl,
            cls: this.extraItemCls + ' ' + itemClass,
            style: parseStyle(this.extraItemStyle) + ' ' + itemStyle,
            caption: itemCaption,
            display: itemDisplay,
            value:  itemVal,
            key: itemKey,
            listeners: {
                'remove': function(item){
                    if(this.fireEvent('beforeremoveitem',this,item.value) === false){
                        return false;
                    }
                    this.items.removeKey(item.key);
                    if(this.removeValuesFromStore){
                        if(this.usedRecords.containsKey(item.value)){
                            this.store.add(this.usedRecords.get(item.value));
                            this.usedRecords.removeKey(item.value);
                            this.sortStore();
                            if(this.view){
                                this.view.render();
                            }
                        }
                    }
                    if(!this.preventMultipleRemoveEvents){
                        this.fireEvent.defer(250,this,['removeitem',this,item.value, this.findInStore(item.value)]);
                    }
                },
                destroy: function(){
                    this.collapse();
                    this.autoSize().manageClearBtn().validateValue();
                },
                scope: this
            }
        });
        box.render();

        hConfig = {
            tag :'input',
            type :'hidden',
            value : itemVal,
            name : (this.hiddenName || this.name)
        };

        if(this.disabled){
            Ext.apply(hConfig,{
                disabled : 'disabled'
            })
        }
        box.hidden = this.el.insertSibling({
            tag:'input',
            type:'hidden',
            value: itemVal,
            name: (this.hiddenName || this.name+'['+itemVal+']') // could also be this.name+'[]' I assume
        },'before');

        this.items.add(itemKey,box);
        this.applyEmptyText().autoSize().manageClearBtn().validateValue();
    }
});
Ext.reg('commercemultilang-combo-categories', CommerceMultiLang.combo.Categories);
