<?php
/**
 * Get list of products and currently used languages based on context cultureKey setting.
 *
 * @package commerce_multilang
 * @subpackage processors
 */
class CMLProductGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'CMLProduct';
    public $languageTopics = array('commerce_multilang:default');
    public $defaultSortField = 'name';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'commerce_multilang.product';
    protected $langKeys = array();
    protected $defaultLanguage = null;
    protected $defaultContext = null;

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

        // Default language and context are used so we know where to put data when creating new products.
        $this->defaultLanguage = $this->modx->getOption('commerce_multilang.default_lang');

        foreach($this->langKeys as $langKey) {
            // If no default language has been set, set it with the manager_language setting.
            // TODO: Change this to an install option later
            if($this->defaultLanguage == '' || $this->defaultLanguage == null) {
                $managerLang = $this->modx->getOption('manager_language');
                // make sure the manager_language key is one of the context languages
                if($langKey['lang_key'] == $managerLang) {
                    $setting = $this->modx->getObject('modSystemSetting',array(
                        'key'   =>  'commerce_multilang.default_lang'
                    ));
                    $setting->set('value',$managerLang);
                    $setting->save();
                    $cacheRefreshOptions =  [ 'system_settings' => [] ];
                    $this->modx->cacheManager->refresh($cacheRefreshOptions);
                }
            }

            if($langKey['lang_key'] == $this->defaultLanguage) {
                $this->defaultContext = $langKey['context_key'];
            }
        }

        return parent::initialize();
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('CMLProductData','ProductData','ProductData.product_id=CMLProduct.id');
        $c->leftJoin('CMLProductLanguage','ProductLanguage',array(
            'ProductLanguage.product_id=CMLProduct.id',
            'ProductLanguage.lang_key'=>$this->defaultLanguage
        ));
        $c->leftJoin('CMLProductImage','ProductImage',array(
            'ProductImage.product_id=CMLProduct.id',
            'ProductImage.main' =>  true
        ));
        $c->leftJoin('CMLProductImageLanguage','ProductImageLanguage',array(
            'ProductImageLanguage.product_image_id=ProductImage.id'
        ));
        $c->leftJoin('modResource','Category',array(
            'ProductLanguage.category=Category.id'
        ));
        $c->select('CMLProduct.*,
                    ProductData.type,
                    ProductData.product_listing,
                    ProductData.alias,
                    ProductImageLanguage.image AS main_image,
                    Category.pagetitle AS category,
                    Category.id AS category_id');
        $c->where(array(
            'removed:=' =>  0,
            'AND:CMLProduct.class_key:!='=>'comProduct',
            'AND:ProductData.product_listing:=' =>  1
        ));
        $c->groupBy('CMLProduct.sku,CMLProduct.id,ProductImageLanguage.image,Category.pagetitle,Category.id');
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
        $languages = $this->modx->getCollection('CMLProductLanguage',array(
            'product_id'    => $object->get('id')
        ));
        $langs = array();
        foreach($languages as $lang) {
            $langArray = $lang->toArray();
            array_push($langs,$langArray);
        }
        $row['langs'] = $langs;

        // Grab all the assigned variation values for this product
        $assignedVariations = $this->modx->getCollection('CMLAssignedVariation',array(
            'product_id'    =>  $row['id']
        ));
        foreach($assignedVariations as $variation) {
            $row[$variation->get('name').'_'.$variation->get('lang_key')] = $variation->get('value');
        }

        // Display name of product type and associated variations instead of just id
        if($row['type']) {
            $type = $this->modx->getObject('CMLProductType',array(
                'id'    =>  $row['type']
            ));
            if($type) {
                $row['type_name'] = $type->get('name');
                $variations = $this->modx->getCollection('CMLProductVariation',array(
                    'type_id'   =>  $type->get('id')
                ));
                if($variations) {
                    $row['type_variations'] .= '<p style="color:#888;">';
                    $idx=1;
                    foreach ($variations as $variation) {
                        if(count($variations) === $idx) {
                            $row['type_variations'] .= $variation->get('name');
                        } else {
                            $row['type_variations'] .= $variation->get('name') . ', ';
                        }
                        $idx++;
                    }
                    $row['type_variations'] .= '</p>';
                }
            }

        } else {
            //$row['type'] = 'None';
        }
        return $row;
    }

    public function outputArray(array $array,$count = false) {
        if ($count === false) { $count = count($array); }
        $output = json_encode(array(
            'success' => true,
            'languages' => $this->langKeys,
            'default_language' =>$this->defaultLanguage,
            'default_context'   => $this->defaultContext,
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
return 'CMLProductGetListProcessor';