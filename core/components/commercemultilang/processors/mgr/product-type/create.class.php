<?php
/**
 * Create a Product Type
 * 
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductTypeCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'CommerceMultiLangProductType';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.producttype';
    protected $languages = array();

    public function beforeSet(){
        $items = $this->modx->getCollection($this->classKey);
        $this->setProperty('position', count($items));
        return parent::beforeSet();
    }

    public function beforeSave() {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('commercemultilang.err.product_type_name_ns'));
        } else if ($this->doesAlreadyExist(array('name' => $name))) {
            $this->addFieldError('name',$this->modx->lexicon('commercemultilang.err.product_type_name_ae'));
        }
        return parent::beforeSave();
    }
}
return 'CommerceMultiLangProductTypeCreateProcessor';