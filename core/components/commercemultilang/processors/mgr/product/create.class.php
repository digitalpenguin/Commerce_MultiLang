<?php
/**
 * Create a Product
 * 
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'CommerceMultiLangProduct';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.product';

    public function beforeSave() {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('commercemultilang.err.product_name_ns'));
        } else if ($this->doesAlreadyExist(array('name' => $name))) {
            $this->addFieldError('name',$this->modx->lexicon('commercemultilang.err.product_name_ae'));
        }
        return parent::beforeSave();
    }

    public function afterSave() {
        $productLang = $this->modx->newObject('CommerceMultiLangProductLanguage');
        $productLang->set('product_id',$this->object->get('id'));
        $productLang->set('name',$this->object->get('name'));
        $productLang->set('lang_key',$this->modx->getOption('commercemultilang.default_lang'));
        $productLang->set('description',$this->object->get('description'));
        $this->modx->log(1,'This is the description:'.$this->object->get('description'));
        $productLang->save();

        $productData = $this->modx->newObject('CommerceMultiLangProductData');
        $productData->set('product_id',$this->object->get('id'));
        foreach($this->getProperties() as $key => $value) {
            $productData->set($key,$value);
        }
        $productData->save();
        return parent::afterSave();
    }
}
return 'CommerceMultiLangProductCreateProcessor';
