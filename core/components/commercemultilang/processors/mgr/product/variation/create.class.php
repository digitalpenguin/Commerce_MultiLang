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
    protected $alias;
    protected $parentObj = null;
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

        // Grab new submitted values and overwrite any that were set previously.
        /*foreach($this->getProperties() as $key => $value) {
            $this->object->set($key,$value);
            //$this->modx->log(1,$key.' '.$value);
        }*/

        //$this->modx->log(1,print_r($this->object->toArray(),true));

        // Add a count on the end of the new alias
        $count = $this->modx->getCount('CommerceMultiLangProductData',array(
            'parent' => $this->getProperty('parent')
        ));
        if($count) {
            $count = $count+1;
        } else {
            $count = $count+2;
        }
        $this->generateProductAlias($this->object->get('name').$count);
        return parent::beforeSave();

    }

    protected function generateProductAlias($text) {
        $letters = array(
            '–', '"','\'', '«', '»', '&', '÷', '>','<', '$', '/'
        );
        $text = str_replace($letters, " ", $text);
        $text = str_replace("&", "and", $text);
        $text = str_replace("?", "", $text);
        $alias = strtolower(str_replace(" ", "-", $text));

        $this->alias = $alias;
    }

    public function afterSave() {
        $this->loadVariationFields();
        $productData = $this->modx->newObject('CommerceMultiLangProductData');
        $productData->set('product_id',$this->object->get('id'));
        $productData->set('alias', $this->alias);
        $productData->set('parent', $this->parentObj->get('id'));
        $productData->set('type', $this->parentObj->get('type'));
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
                $varField->set('name',$variation->get('name'));
                $varField->set('lang_key',$lang->get('lang_key'));
                $varField->set('value',$this->getProperty($variation->get('name')));
                $varField->save();
            }

        }
        return parent::afterSave();
    }

    protected function loadVariationFields() {
        $productData = $this->modx->getObject('CommerceMultiLangProductData',array(
            'product_id'    =>  $this->getProperty('parent')
        ));
        if($productData) {
            $variations = $this->modx->getCollection('CommerceMultiLangProductVariation',array(
                'type_id'   =>  $productData->get('type')
            ));

            foreach($variations as $variation) {
                array_push($this->variationData,$variation);
            }
        }

    }



}
return 'CommerceMultiLangProductChildCreateProcessor';
