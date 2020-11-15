<?php
/**
 * Create a Product
 * 
 * @package commerce_multilang
 * @subpackage processors
 */
class CMLProductCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'CMLProduct';
    public $languageTopics = array('commerce_multilang:default');
    public $objectType = 'commerce_multilang.product';
    protected $langKeys = array();
    protected $alias;
    protected $variationData = array();
    //protected $flatRowData = array();

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
        $this->generateProductAlias($this->object->get('name'));
        return parent::beforeSave();
    }

    protected function generateProductAlias($text) {
        $text = $this->modx->sanitizeString($text);
        $letters = array(
            '–', '"','\'', '«', '»', '&', '÷', '>','<', '$', '/'
        );
        $text = str_replace($letters, " ", $text);
        $text = str_replace("&", "and", $text);
        $text = str_replace("?", "", $text);
        $alias = strtolower(str_replace(" ", "-", $text));
        $c = $this->modx->newQuery('CMLProduct');
        $c->leftJoin('CMLProductData','ProductData','ProductData.product_id=CMLProduct.id');
        $c->where([
            'ProductData.alias'                 => $alias,
            'CMLProduct.removed'  => 0
        ]);
        $count = $this->modx->getCount('CMLProduct',$c);
        if ($count) {
            $this->addFieldError('name',$this->modx->lexicon('commerce_multilang.err.product_alias_ae'));
        }
        $this->alias = $alias;
    }

    public function afterSave() {


        $productData = $this->modx->newObject('CMLProductData');
        $productData->set('product_id',$this->object->get('id'));
        $productData->set('alias', $this->alias);
        $productData->set('parent', 0);
        $productData->set('product_listing',1);
        $productData->set('type',$this->object->get('type'));
        $items = $this->modx->getCollection($this->classKey);
        $productData->set('position', count($items));
        $productData->save();

        // These can only be loaded after the CMLProductData object exists.
        $this->loadVariationFields();

        //$this->flatRowData['product'] = $this->object;
        foreach ($this->langKeys as $langKey) {
            $productLang = $this->modx->newObject('CMLProductLanguage');
            $productLang->set('product_id', $this->object->get('id'));
            $productLang->set('name', $this->object->get('name'));
            $productLang->set('lang_key', $langKey['lang_key']);
            $productLang->set('description', $this->object->get('description'));

            if($langKey['lang_key'] == $this->modx->getOption('commerce_multilang.default_lang')) {
                $productLang->set('category', $this->object->get('category'));
            } else {
                $context = $this->modx->getContext($langKey['context_key']);
                $rootCategoryId = $context->getOption('commerce_multilang.category_root_id');
                if($rootCategoryId) {
                    // Set category_root_id for any language other than default. User can change later.
                    $productLang->set('category', $rootCategoryId);
                } else {
                    // The category_root_id setting isn't set on context, add 0 as category
                    $productLang->set('category', 0);
                }
            }
            $productLang->save();

            // Set the variation field values by creating new many-to-many records for each one.
            foreach($this->variationData as $variation) {
                $varField = $this->modx->newObject('CMLAssignedVariation');
                $varField->set('variation_id',$variation->get('id'));
                $varField->set('product_id',$this->object->get('id'));
                $varField->set('type_id',$this->object->get('type'));
                $varField->set('name',$variation->get('name'));
                $varField->set('lang_key',$langKey['lang_key']);
                $varField->set('value','');
                $varField->save();
            }
        }

        return parent::afterSave();
    }

    protected function loadVariationFields() {
        $productData = $this->modx->getObject('CMLProductData',array(
            'product_id'    =>  $this->object->get('id')
        ));
        if($productData) {
            $variations = $this->modx->getCollection('CMLProductVariation',array(
                'type_id'   =>  $productData->get('type')
            ));
            foreach($variations as $variation) {
                array_push($this->variationData,$variation);
            }
        }
    }

}
return 'CMLProductCreateProcessor';
