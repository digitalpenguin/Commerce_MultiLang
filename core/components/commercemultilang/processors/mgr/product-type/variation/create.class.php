<?php
/**
 * Create a Product Variation
 * 
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductVariationCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'CommerceMultiLangProductVariation';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.productvariation';
    protected $languages = [];

    protected function getLanguages() {
        $c = $this->modx->newQuery('modContext');
        $c->leftJoin('modContextSetting','ContextSettings','modContext.key=ContextSettings.context_key');
        $c->select('modContext.key,ContextSettings.key as setting_key,ContextSettings.value as lang_key');
        $c->where(array(
            'modContext.key:!=' => 'mgr',
            'AND:ContextSettings.key:=' => 'cultureKey'
        ));
        $contexts = $this->modx->getCollection('modContext',$c);
        foreach ($contexts as $context) {
            $contextArray = $context->toArray();
            $lang = array();
            $lang['context_key'] = $contextArray['key'];
            $lang['lang_key'] = $contextArray['lang_key'];
            $lang['name'] = $contextArray['name'];
            $this->languages[] = $lang;
        }
    }

    public function initialize() {
        $this->getLanguages();
        return parent::initialize();
    }

    public function beforeSave() {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('commercemultilang.err.product_type_variation_name_ns'));
        } else {
            // Set display name to what the user entered.
            $this->object->set('display_name',$name);

            // For the field name remove all non-alphanumeric chars and convert spaces to underscores.
            $name = preg_replace('!\s+!', '_', $name);
            $name = preg_replace('/[^a-z0-9]/i', '', $name);
            $name = strtolower($name);
            $this->object->set('name',$name);
        }
        return parent::beforeSave();
    }

    public function afterSave() {
        // Check if there are any products of this type. If so, assign the new variation to them.
        $c = $this->modx->newQuery('CommerceMultiLangProduct');
        $c->setClassAlias('Product');
        $c->leftJoin('CommerceMultiLangProductData','ProductData','ProductData.product_id=Product.id');
        $c->select('Product.id,Product.removed,ProductData.type');
        //$c->prepare();
        //$this->modx->log(1,$c->toSQL());
        $c->where(array(
            'Product.removed'   =>  0,
            'ProductData.type'  =>  $this->object->get('type_id')
        ));
        $products = $this->modx->getCollection('CommerceMultiLangProduct',$c);
        foreach ($products as $product) {
            foreach($this->languages as $language) {
                $assignedVar = $this->modx->newObject('CommerceMultiLangAssignedVariation');
                $assignedVar->set('variation_id',$this->object->get('id'));
                $assignedVar->set('product_id',$product->get('id'));
                $assignedVar->set('name',$this->object->get('name'));
                $assignedVar->set('lang_key',$language['lang_key']);
                $assignedVar->set('value','');
                $assignedVar->save();
            }
        }
        return parent::afterSave();
    }
}
return 'CommerceMultiLangProductVariationCreateProcessor';