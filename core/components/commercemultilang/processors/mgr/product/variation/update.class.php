<?php
/**
 * Update a product
 * 
 * @package commercemultilang
 * @subpackage processors
 */

class CommerceMultiLangProductChildUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'CommerceMultiLangProduct';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.product';
    protected $variationData = array();

    public function beforeSave() {
        $sku = $this->getProperty('sku');
        if (empty($sku)) {
            $this->addFieldError('sku',$this->modx->lexicon('commercemultilang.err.product_type_sku_ns'));
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
        $productData = $this->modx->getObject('CommerceMultiLangProductData',array(
            'product_id' => $this->object->get('id')
        ));
        $productData->set('product_id',$this->object->get('id'));
        $productData->set('alias', ''); // Children products shouldn't have an alias.
        $productData->save();

        $productImages = $this->modx->getCollection('CommerceMultiLangProductImage',[
            'product_id'    =>  $this->object->get('id')
        ]);

        // Grabs related language table
        $productLanguages = $this->modx->getCollection('CommerceMultiLangProductLanguage',array(
            'product_id' => $this->object->get('id')
        ));
        foreach($productLanguages as $productLanguage) {
            $langKey = $productLanguage->get('lang_key');

            foreach($productImages as $productImage) {
                $imageLanguage = $this->modx->getObject('CommerceMultiLangProductImageLanguage',[
                    'product_image_id'  =>  $productImage->get('id'),
                    'lang_key'          =>  $langKey
                ]);
                $imageLanguage->set('image',$this->object->get('image'));
                $imageLanguage->set('product_image_id',$productImage->get('id'));
                $imageLanguage->set('lang_key',$langKey);
                $imageLanguage->set('title',$this->object->get('title'));
                $imageLanguage->set('alt',$this->object->get('alt'));
                $imageLanguage->set('description',$this->object->get('description'));
                $imageLanguage->save();
            }

            // Grab all the assigned variation values for this product
            $assignedVariations = $this->modx->getCollection('CommerceMultiLangAssignedVariation',array(
                'product_id'    =>  $this->object->get('id'),
                'lang_key'      =>  $langKey
            ));

            $lkLength = strlen($langKey);
            foreach($this->getProperties() as $key => $value) {
                // Get the lang_key from the submitted field name and check if it matches current language row
                if(substr($key, -$lkLength) == $langKey) {
                    // If there's a match extract the field name with underscore and lang_key
                    $keyLength = strlen($key);
                    $fieldLength = $keyLength - $lkLength;
                    $fieldName = substr($key,0,$fieldLength-1); //-1 to compensate for underscore
                    // Set the new value
                    //$this->modx->log(1,'field name: '.$fieldName);
                    $productLanguage->set($fieldName,$value);

                    // Insert variation values into each field.
                    foreach($assignedVariations as $assignedVariation) {
                        if($fieldName == $assignedVariation->get('name')) {
                            $assignedVariation->set('value', $value);
                            $assignedVariation->save();
                        }
                    }
                }
            }
            // After going through all fields, save this language.
            $productLanguage->save();

            // Overwrite base product name and description with default language.
            if($this->modx->getOption('commercemultilang.default_lang')) {
                if ($productLanguage->get('lang_key') == $this->modx->getOption('commercemultilang.default_lang')) {
                    $this->object->set('name', $productLanguage->get('name'));
                    $this->object->set('description', $productLanguage->get('description'));
                    $this->object->save();
                }
            }
        }
        return parent::afterSave();
    }

    protected function loadVariationFields() {
        $productData = $this->modx->getObject('CommerceMultiLangProductData',array(
            'product_id'    =>  $this->getProperty('id')
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
return 'CommerceMultiLangProductChildUpdateProcessor';