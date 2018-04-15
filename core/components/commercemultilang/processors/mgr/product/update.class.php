<?php
/**
 * Update a product
 * 
 * @package commercemultilang
 * @subpackage processors
 */

class CommerceMultiLangProductUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'CommerceMultiLangProduct';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.product';

    public function beforeSet() {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('commercemultilang.err.product_name_ns'));
        } else if ($this->modx->getCount($this->classKey, array('name' => $name)) && ($this->object->name != $name)) {
            $this->addFieldError('name',$this->modx->lexicon('commercemultilang.err.product_name_ae'));
        }
        return parent::beforeSet();
    }

    public function afterSave() {
        $productData = $this->modx->getObject('CommerceMultiLangProductData',array(
            'product_id' => $this->object->get('id')
        ));
        foreach($this->getProperties() as $key => $value) {
            $productData->set($key,$value);
        }
        $productData->save();


        $productLanguages = $this->modx->getCollection('CommerceMultiLangProductLanguage',array(
            'product_id' => $this->object->get('id')
        ));
        foreach($productLanguages as $productLanguage) {
            foreach($this->getProperties() as $key => $value) {
                //if($productLanguage['lang_key'] == ) {
//TODO:!!!!!!!
                //}
            }
        }
        return parent::afterSave();
    }

}
return 'CommerceMultiLangProductUpdateProcessor';