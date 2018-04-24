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
        // Grabs related data table
        $productData = $this->modx->getObject('CommerceMultiLangProductData',array(
            'product_id' => $this->object->get('id')
        ));
        foreach($this->getProperties() as $key => $value) {
            $productData->set($key,$value);
        }
        $productData->save();

        // Grabs related language table
        $productLanguages = $this->modx->getCollection('CommerceMultiLangProductLanguage',array(
            'product_id' => $this->object->get('id')
        ));
        foreach($productLanguages as $productLanguage) {
            $langKey = $productLanguage->get('lang_key');
            $lkLength = strlen($langKey);
            foreach($this->getProperties() as $key => $value) {
                // Get the lang_key from the submitted field name and check if it matches current language row
                if(substr($key, -$lkLength) == $langKey) {
                    // If there's a match extract the field name with underscore and lang_key
                    $keyLength = strlen($key);
                    $fieldLength = $keyLength - $lkLength;
                    $fieldName = substr($key,0,$fieldLength-1); //-1 to compensate for underscore
                    // Set the new value
                    //$this->modx->log(1,'field name: '.$fieldName);
                    $productLanguage->set($fieldName,$value);
                }
            }
            // After going through all fields, save this language.
            $productLanguage->save();

            // Overwrite base product name and description with default language.
            if($this->modx->getOption('commercemultilang.default_lang')) {
                if ($productLanguage->get('lang_key') == $this->modx->getOption('commercemultilang.default_lang')) {
                    $this->object->set('name', $productLanguage->get('name'));
                    $this->object->set('description', $productLanguage->get('description'));
                    $this->object->save();
                }
            }
        }
        return parent::afterSave();
    }

}
return 'CommerceMultiLangProductUpdateProcessor';