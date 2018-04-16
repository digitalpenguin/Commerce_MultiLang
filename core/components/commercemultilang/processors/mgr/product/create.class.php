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
    protected $langKeys = array();

    protected function getLanguages() {
        $c = $this->modx->newQuery('modContext');
        $c->leftJoin('modContextSetting','ContextSettings','modContext.key=ContextSettings.context_key');
        $c->select('modContext.key,ContextSettings.key as setting_key,ContextSettings.value as lang_key');
        $c->where(array(
            'modContext.key:!=' => 'mgr',
            'AND:ContextSettings.key:=' => 'cultureKey'
        ));
        $contexts = $this->modx->getCollection('modContext',$c);
        foreach ($contexts as $context) {
            $contextArray = $context->toArray();
            //$this->modx->log(1,print_r($contextArray,true));
            $lang = array();
            $lang['context_key'] = $contextArray['key'];
            $lang['lang_key'] = $contextArray['lang_key'];
            $lang['name'] = $contextArray['name'];
            array_push($this->langKeys,$lang);
        }
    }

    public function initialize() {
        $this->getLanguages();
        return parent::initialize();
    }

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
        foreach ($this->langKeys as $langKey) {
            $productLang = $this->modx->newObject('CommerceMultiLangProductLanguage');
            $productLang->set('product_id', $this->object->get('id'));
            $productLang->set('name', $this->object->get('name'));
            $productLang->set('lang_key', $langKey['lang_key']);
            $productLang->set('description', $this->object->get('description'));
            $this->modx->log(1, 'This is the description:' . $this->object->get('description'));
            $productLang->save();
        }
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
