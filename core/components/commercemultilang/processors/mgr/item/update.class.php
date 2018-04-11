<?php
/**
 * Update an Item
 * 
 * @package commercemultilang
 * @subpackage processors
 */

class CommerceMultiLangItemUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'CommerceMultiLangItem';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.item';

    public function beforeSet() {
        $name = $this->getProperty('name');

        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('commercemultilang.err.item_name_ns'));

        } else if ($this->modx->getCount($this->classKey, array('name' => $name)) && ($this->object->name != $name)) {
            $this->addFieldError('name',$this->modx->lexicon('commercemultilang.err.item_name_ae'));
        }
        return parent::beforeSet();
    }

}
return 'CommerceMultiLangItemUpdateProcessor';