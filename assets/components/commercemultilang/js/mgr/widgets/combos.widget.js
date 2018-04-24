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
            convert: function(v, rec) { return rec['pagetitle'] + ' (id:' + rec['id'] + ')'}
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