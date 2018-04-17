<?php
/**
 * Create a Product Image
 * 
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductImageCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'CommerceMultiLangProductImage';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.product_image';
    protected $langKeys = array();


    public function beforeSave() {
        $title = $this->getProperty('title');
        if (empty($title)) {
            $this->addFieldError('title',$this->modx->lexicon('commercemultilang.err.product_image_title_ns'));
        }
        return parent::beforeSave();
    }

}
return 'CommerceMultiLangProductImageCreateProcessor';
