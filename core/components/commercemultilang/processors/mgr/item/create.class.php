<?php
/**
 * Create an Item
 * 
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangItemCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'CommerceMultiLangItem';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.item';

    public function beforeSet(){
        $items = $this->modx->getCollection($this->classKey);

        $this->setProperty('position', count($items));

        return parent::beforeSet();
    }

    public function beforeSave() {
        $name = $this->getProperty('name');

        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('commercemultilang.err.item_name_ns'));
        } else if ($this->doesAlreadyExist(array('name' => $name))) {
            $this->addFieldError('name',$this->modx->lexicon('commercemultilang.err.item_name_ae'));
        }
        return parent::beforeSave();
    }
}
return 'CommerceMultiLangItemCreateProcessor';
