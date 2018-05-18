<?php
/**
 * Update a product image
 * 
 * @package commercemultilang
 * @subpackage processors
 */

class CommerceMultiLangProductImageUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'CommerceMultiLangProductImage';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.productimage';

    public function afterSave() {
        // Grabs related language table
        $imageLanguages = $this->modx->getCollection('CommerceMultiLangProductLanguage',[
            'product_id'    => $this->object->get('id')
        ]);
        foreach ($imageLanguages as $language) {
            $langKey = $language->get('lang_key');
        }
        return parent::afterSave();
    }

}
return 'CommerceMultiLangProductImageUpdateProcessor';