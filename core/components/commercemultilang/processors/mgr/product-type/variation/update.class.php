<?php
/**
 * Update a product variation
 * 
 * @package commercemultilang
 * @subpackage processors
 */

class CommerceMultiLangProductVariationUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'CommerceMultiLangProductVariation';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.productvariation';

    public function beforeSave() {
        $name = strtolower($this->getProperty('name'));
        $this->object->set('name',$name);
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('commercemultilang.err.product_type_variation_name_ns'));
        }
        return parent::beforeSave();
    }
}
return 'CommerceMultiLangProductVariationUpdateProcessor';