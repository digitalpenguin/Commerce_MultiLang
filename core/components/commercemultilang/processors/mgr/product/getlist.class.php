<?php
/**
 * Get list of products and currently used languages based on context cultureKey setting.
 *
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'CommerceMultiLangProduct';
    public $languageTopics = array('commercemultilang:default');
    public $defaultSortField = 'sku';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'commercemultilang.product';
    protected $langKeys = array();

    /**
     * Retrieves array of context keys and associated cultureKeys. Excludes mgr.
     */
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
        $c->leftJoin('CommerceMultiLangProductData','ProductData','ProductData.product_id=CommerceMultiLangProduct.id');
        $c->select('CommerceMultiLangProduct.*');
        $c->where(array(
            'removed:='=>0,
            'AND:CommerceMultiLangProduct.class_key:!='=>'comProduct'
        ));
        //$c->prepare();
        //$this->modx->log(1,$c->toSQL());
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                    'name:LIKE' => '%'.$query.'%',
                    'OR:description:LIKE' => '%'.$query.'%',
                ));
        }
        return $c;
    }

    public function prepareRow(xPDOObject $object) {
        $row = parent::prepareRow($object);
        $languages = $this->modx->getCollection('CommerceMultiLangProductLanguage',array(
            'product_id'    => $object->get('id')
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
return 'CommerceMultiLangProductGetListProcessor';