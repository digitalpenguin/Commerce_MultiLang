var CommerceMultiLang = function(config) {
    config = config || {};
CommerceMultiLang.superclass.constructor.call(this,config);
};
Ext.extend(CommerceMultiLang,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {}
});
Ext.reg('commercemultilang',CommerceMultiLang);
CommerceMultiLang = new CommerceMultiLang();