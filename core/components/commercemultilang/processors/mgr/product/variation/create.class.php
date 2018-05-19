<?php
/**
 * Create a Product
 * 
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductChildCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'CommerceMultiLangProduct';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.product';
    protected $parentObj = null;
    protected $parentProductData = null;
    protected $parentLangs = array();
    protected $variationData = array();

    public function initialize() {
        $this->loadParentProduct();
        return parent::initialize();
    }

    protected function loadParentProduct() {
        $this->parentObj = $this->modx->getObject($this->classKey,array('CommerceMultiLangProduct.id' => $this->getProperty('parent')));
        $this->parentLangs = $this->modx->getCollection('CommerceMultiLangProductLanguage',array(
            'product_id'    =>  $this->parentObj->get('id')
        ));

    }

    public function beforeSet() {
        // Make sure we've got the parent.
        if(!$this->parentObj) return $this->failure('Unable to load parent product.');

        // Duplicate parent values into new CommerceMultiLangProduct object (Not the ID!).
        $parentArray = $this->parentObj->toArray();
        foreach($parentArray as $key => $value) {
            if($key != 'id') $this->object->set($key,$value);
        }
        return parent::beforeSet();
    }



    public function beforeSave() {

        $sku = $this->getProperty('sku');
        if (empty($sku)) {
            $this->addFieldError('sku',$this->modx->lexicon('commercemultilang.err.product_type_sku_ns'));
        } else if ($this->doesAlreadyExist(array(
            'sku' => $sku,
            'removed' => 0
        ))) {
            $this->addFieldError('sku',$this->modx->lexicon('commercemultilang.err.product_type_sku_ae'));
        }

        $price = $this->getProperty('price');
        if (empty($price)) {
            $this->addFieldError('price',$this->modx->lexicon('commercemultilang.err.product_type_price_ns'));
        }
        $stock = $this->getProperty('stock');
        if (empty($stock)) {
            $this->addFieldError('stock',$this->modx->lexicon('commercemultilang.err.product_type_stock_ns'));
        }
        $weight = $this->getProperty('weight');
        if (empty($weight)) {
            $this->addFieldError('weight',$this->modx->lexicon('commercemultilang.err.product_type_weight_ns'));
        }

        return parent::beforeSave();

    }

    public function afterSave() {
        $this->loadVariationFields();
        $productData = $this->modx->newObject('CommerceMultiLangProductData');
        $productData->set('product_id',$this->object->get('id'));
        $productData->set('alias', '');
        $productData->set('parent', $this->parentObj->get('id'));
        $productData->set('type', $this->parentProductData->get('type'));
        $productData->set('product_listing',0);
        $productData->save();

        foreach($this->parentLangs as $lang) {
            $productLang = $this->modx->newObject('CommerceMultiLangProductLanguage');
            $productLang->set('product_id', $this->object->get('id'));
            $productLang->set('name', $lang->get('name'));
            $productLang->set('lang_key', $lang->get('lang_key'));
            $productLang->set('description', $lang->get('description'));
            $productLang->set('category',$lang->get('category'));
            $productLang->save();

            // Set the variation field values by creating new many-to-many records for each one.
            foreach($this->variationData as $variation) {
                $varField = $this->modx->newObject('CommerceMultiLangAssignedVariation');
                $varField->set('variation_id',$variation->get('id'));
                $varField->set('product_id',$this->object->get('id'));
                $varField->set('type_id',$this->object->get('type'));
                $varField->set('name',$variation->get('name'));
                $varField->set('lang_key',$lang->get('lang_key'));
                $varField->set('value',$this->getProperty($variation->get('name')));
                $varField->save();
            }

        }
        return parent::afterSave();
    }

    protected function loadVariationFields() {
        $this->parentProductData = $this->modx->getObject('CommerceMultiLangProductData',array(
            'product_id'    =>  $this->getProperty('parent')
        ));
        if($this->parentProductData) {
            $variations = $this->modx->getCollection('CommerceMultiLangProductVariation',array(
                'type_id'   =>  $this->parentProductData->get('type')
            ));

            foreach($variations as $variation) {
                array_push($this->variationData,$variation);
            }
        }

    }



}
return 'CommerceMultiLangProductChildCreateProcessor';
