<?php
/**
 * Create a Product Type
 * 
 * @package commerce_multilang
 * @subpackage processors
 */
class CMLProductTypeCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'CMLProductType';
    public $languageTopics = array('commerce_multilang:default');
    public $objectType = 'commerce_multilang.producttype';
    protected $languages = array();

    public function beforeSet(){
        $items = $this->modx->getCollection($this->classKey);
        $this->setProperty('position', count($items));
        return parent::beforeSet();
    }

    public function beforeSave() {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('commerce_multilang.err.product_type_name_ns'));
        } else if ($this->doesAlreadyExist(array('name' => $name))) {
            $this->addFieldError('name',$this->modx->lexicon('commerce_multilang.err.product_type_name_ae'));
        }
        return parent::beforeSave();
    }
}
return 'CMLProductTypeCreateProcessor';