<?php
/**
 * Get list of products and currently used languages based on context cultureKey setting.
 *
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductChildGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'CommerceMultiLangProduct';
    public $languageTopics = array('commercemultilang:default');
    public $defaultSortDirection = 'ASC';
    public $objectType = 'commercemultilang.product';
    protected $langKeys = array();
    protected $defaultLanguage = null;
    protected $defaultContext = null;
    //protected $extraCols = array();
    protected $productType = null;

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

    /*protected function loadExtraColumns() {
        //$this->modx->log(1,'ParentID: '.$this->getProperty('product_id'));
        $parentData = $this->modx->getObject('CommerceMultiLangProductData',array(
            'product_id'    =>  $this->getProperty('product_id')
        ));
        if($parentData) {
            $this->productType = $this->modx->getObject('CommerceMultiLangProductType', array(
                'id' => $parentData->get('type')
            ));

            //$this->modx->log(1, print_r($this->productType->toArray(), true));
            if ($this->productType) {
                $cols = $this->modx->getCollection('CommerceMultiLangProductVariation', array(
                    'type_id' => $this->productType->get('id')
                ));
                foreach ($cols as $col) {
                    //$this->modx->log(1, print_r($col->toArray(), true));
                    array_push($this->extraCols, $col->get('name'));
                }
            } else {
                $this->modx->log(1, 'Unable to load the product type that\'s specified in productData.');
            }
        } else {
            $this->modx->log(1, 'Unable to load $parentData. Check product_id of parent.');
        }
    }*/

    public function initialize() {
        $this->getLanguages();
        //$this->loadExtraColumns();
        // Default language and context are used so we know where to put data when creating new products.
        $this->defaultLanguage = $this->modx->getOption('commercemultilang.default_lang');

        foreach($this->langKeys as $langKey) {
            if($langKey['lang_key'] == $this->defaultLanguage) {
                $this->defaultContext = $langKey['context_key'];
            }
        }

        return parent::initialize();
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('CommerceMultiLangProductData','ProductData','ProductData.product_id=CommerceMultiLangProduct.id');
        $c->leftJoin('CommerceMultiLangProductLanguage','ProductLanguage',array(
            'ProductLanguage.product_id=CommerceMultiLangProduct.id',
            'ProductLanguage.lang_key'=>$this->defaultLanguage
        ));
        $c->leftJoin('CommerceMultiLangProductImage','ProductImage',array(
            'ProductImage.product_id=CommerceMultiLangProduct.id',
            'ProductImage.main' =>  true
        ));
        $c->leftJoin('CommerceMultiLangProductImageLanguage','ProductImageLanguage',array(
            'ProductImageLanguage.product_image_id=ProductImage.id'
        ));
        $c->leftJoin('modResource','Category',array(
            'ProductLanguage.category=Category.id'
        ));

        $c->select(
            'CommerceMultiLangProduct.*,
                    ProductData.type,
                    ProductData.product_listing,
                    ProductData.alias,
                    ProductImageLanguage.image AS main_image,
                    Category.pagetitle AS category,
                    Category.id AS category_id'
        );
        $c->where(array(
            'removed:=' =>  0,
            'AND:CommerceMultiLangProduct.class_key:!='=>'comProduct',
            'AND:ProductData.parent:=' =>  $this->getProperty('product_id')
        ));
        //$c->prepare();
        //$this->modx->log(1,$c->toSQL());

        // Set sort field
        $c->sortby('ProductData.position');

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

        $assignedVariations = $this->modx->getCollection('CommerceMultiLangAssignedVariation',array(
            'product_id'    =>  $row['id']
        ));
        foreach($assignedVariations as $variation) {
            $row[$variation->get('name').'_'.$variation->get('lang_key')] = $variation->get('value');
        }

        // Gets variation fields assigned to this product type.
        $variations = $this->modx->getCollection('CommerceMultiLangAssignedVariation',array(
            'product_id'    =>  $row['id'],
            'lang_key'      =>  $this->defaultLanguage
        ));
        foreach($variations as $variation) {
            //$this->modx->log(1,print_r($variation->toArray(),true));
            $row[$variation->name] = $variation->value;
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
            //'extra_cols' => $this->extraCols,
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
return 'CommerceMultiLangProductChildGetListProcessor';