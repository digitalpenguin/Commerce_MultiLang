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
    protected $languages = array();

    public function beforeSave() {
        $name = strtolower($this->getProperty('name'));
        $this->object->set('name',$name);
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('commercemultilang.err.product_type_variation_name_ns'));
        }
        return parent::beforeSave();
    }
}
return 'CommerceMultiLangProductVariationCreateProcessor';