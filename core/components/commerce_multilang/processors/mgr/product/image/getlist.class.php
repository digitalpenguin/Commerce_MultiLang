<?php
/**
 * Get list of product images
 *
 * @package commerce_multilang
 * @subpackage processors
 */
class CMLProductImageGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'CMLProductImage';
    public $languageTopics = array('commerce_multilang:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'commerce_multilang.productimage';
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

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('CMLProductImageLanguage','ProductImageLanguage',array(
            'CommerceMultiLangProductImage.id=ProductImageLanguage.product_image_id',
            'ProductImageLanguage.lang_key'    =>  $this->modx->getOption('commerce_multilang.default_lang')
        ));
        $c->where(array(
            'product_id'=>$this->getProperty('product_id')
        ));
        $c->sortby('main','DESC');
        $c->select('CommerceMultiLangProductImage.*,CommerceMultiLangProductImage.product_id,ProductImageLanguage.title,ProductImageLanguage.image,ProductImageLanguage.alt,ProductImageLanguage.description');
        /*$c->prepare();
        $this->modx->log(1,$c->toSQL());*/
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                    'title:LIKE' => '%'.$query.'%'
                ));
        }
        return $c;
    }

    public function prepareRow(xPDOObject $object) {
        $row = parent::prepareRow($object);
        $languages = $this->modx->getCollection('CommerceMultiLangProductImageLanguage',array(
            'product_image_id'    => $object->get('id')
        ));
        $langs = array();
        foreach($languages as $lang) {
            $langArray = $lang->toArray();
            array_push($langs,$langArray);
        }
        $row['langs'] = $langs;
        return $row;
    }

    public function outputArray(array $array,$count = false) {
        if ($count === false) { $count = count($array); }
        $output = json_encode(array(
            'success' => true,
            'languages' => $this->langKeys,
            'total' => $count,
            'results' => $array
        ));
        if ($output === false) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Processor failed creating output array due to JSON error '.json_last_error());
            return json_encode(array('success' => false));
        }
        return $output;
    }
}
return 'CommerceMultiLangProductImageGetListProcessor';