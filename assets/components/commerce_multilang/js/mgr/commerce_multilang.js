var Commerce_MultiLang = function(config) {
    config = config || {};
    Commerce_MultiLang.superclass.constructor.call(this,config);
};
Ext.extend(Commerce_MultiLang,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {}
});
Ext.reg('commerce_multilang',Commerce_MultiLang);
Commerce_MultiLang = new Commerce_MultiLang();