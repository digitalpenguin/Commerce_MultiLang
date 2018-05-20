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
    protected $languages = array();

    public function initialize() {
        $this->languages = json_decode($this->getProperty('languages'));
        return parent::initialize();
    }

    public function beforeSet() {
        $items = $this->modx->getCollection($this->classKey);
        $this->setProperty('position', count($items));
        return parent::beforeSet();
    }

    public function beforeSave() {
        $title = $this->getProperty('title');
        if (empty($title)) {
            $this->addFieldError('title',$this->modx->lexicon('commercemultilang.err.product_image_title_ns'));
        }
        $count = $this->modx->getCount('CommerceMultiLangProductImage',array(
            'product_id'    =>  $this->getProperty('product_id')
        ));
        if(!$count) {
            $this->object->set('main',1);
        } else {
            $this->object->set('main',0);
        }
        return parent::beforeSave();
    }

    public function afterSave() {
        foreach($this->languages as $language) {
            //$this->modx->log(1,print_r($language,true));
            $imageLanguage = $this->modx->newObject('CommerceMultiLangProductImageLanguage');
            $imageLanguage->set('product_image_id',$this->object->get('id'));
            $imageLanguage->set('lang_key',$language->lang_key);
            $imageLanguage->set('title',$this->getProperty('title'));
            $imageLanguage->set('image',$this->getProperty('image'));
            $imageLanguage->set('description',$this->getProperty('description'));
            $imageLanguage->set('alt',$this->getProperty('alt'));
            $imageLanguage->save();
        }

        return parent::afterSave();
    }

}
return 'CommerceMultiLangProductImageCreateProcessor';
