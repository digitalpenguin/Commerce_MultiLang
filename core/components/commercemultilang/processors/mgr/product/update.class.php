<?php
/**
 * Update a product
 * 
 * @package commercemultilang
 * @subpackage processors
 */

class CommerceMultiLangProductUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'CommerceMultiLangProduct';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.product';
    protected $variationData = array();

    public function initialize() {
        $this->loadVariationFields();
        return parent::initialize();
    }

    public function beforeSet() {
        $name = $this->getProperty('name');
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('commercemultilang.err.product_name_ns'));
        } else if ($this->modx->getCount($this->classKey, array('name' => $name)) && ($this->object->name != $name)) {
            $this->addFieldError('name',$this->modx->lexicon('commercemultilang.err.product_name_ae'));
        }
        return parent::beforeSet();
    }

    public function afterSave() {
        // Grabs related data table
        $productData = $this->modx->getObject('CommerceMultiLangProductData',[
            'product_id' => $this->object->get('id')
        ]);
        foreach($this->getProperties() as $key => $value) {
            if($key == 'id') continue; // don't use comProduct id as data id
            $productData->set($key,$value);
        }
        $productData->save();


        // Grabs related language table
        $productLanguages = $this->modx->getCollection('CommerceMultiLangProductLanguage',[
            'product_id'    => $this->object->get('id')
        ]);

        // Check that all variation products match the product type. It may have changed!
        $count = $this->modx->getCount('CommerceMultiLangProductData',[
            'type:!='      =>  $this->object->get('type'),
            'AND:parent:='    =>  $this->object->get('id')
        ]);
        // If children product exist that have a different type, loop through and update them to be the same as the parent.
        if($count) {
            $children = $this->modx->getCollection('CommerceMultiLangProductData',[
                'type:!='      =>  $this->object->get('type'),
                'AND:parent:='    =>  $this->object->get('id')
            ]);
            foreach($children as $child) {
                $child->set('type',$this->object->get('type'));
                $child->save();
            }
        }

        // In case the product type has changed, check if correct variation assignments exist. If not, create them.
        $variations = $this->modx->getCollection('CommerceMultiLangProductVariation',[
            'type_id'   =>  $this->object->get('type')
        ]);
        $this->addAssignedVariations($variations,$productLanguages,$this->object->get('id'));

        // Now we do the same thing for each child resource and make sure they also have the
        // correct assigned variations for the product type.
        $children = $this->modx->getCollection('CommerceMultiLangProductData',[
            'parent'    =>  $this->object->get('id')
        ]);
        foreach($children as $child) {
            $this->addAssignedVariations($variations,$productLanguages,$child->get('product_id'));
        }

        // Load language tab values into each product language table and assigned variation.
        foreach($productLanguages as $productLanguage) {
            $langKey = $productLanguage->get('lang_key');

            // Grab all the assigned variation values for this product
            $assignedVariations = $this->modx->getCollection('CommerceMultiLangAssignedVariation',[
                'product_id'    =>  $this->object->get('id'),
                'lang_key'      =>  $langKey
            ]);

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

    /**
     * Loads current variation fields for this product type.
     */
    protected function loadVariationFields() {
        $productData = $this->modx->getObject('CommerceMultiLangProductData',[
            'product_id'    =>  $this->getProperty('id')
        ]);
        if($productData) {
            $variations = $this->modx->getCollection('CommerceMultiLangProductVariation',[
                'type_id'   =>  $productData->get('type')
            ]);

            foreach($variations as $variation) {
                array_push($this->variationData,$variation);
            }
        }

    }

    /**
     * Checks if the correct variations have been assigned to the given product based on its type.
     *
     * @param array $variations
     * @param array $productLanguages
     * @param $productId
     */
    protected function addAssignedVariations(array $variations, array $productLanguages, $productId) {
        foreach($variations as $variation) {
            $assignments = $this->modx->getCollection('CommerceMultiLangAssignedVariation',[
                'product_id'    =>  $productId,
                'variation_id'  =>  $variation->get('id')
            ]);
            $names = [];
            foreach($assignments as $assignment) {
                $names[] = $assignment->get('name');
            }
            if(!in_array($variation->get('name'),$names)) {
                foreach($productLanguages as $language) {
                    $newAssignment = $this->modx->newObject('CommerceMultiLangAssignedVariation');
                    $newAssignment->set('name', $variation->get('name'));
                    $newAssignment->set('product_id', $productId);
                    $newAssignment->set('variation_id', $variation->get('id'));
                    $newAssignment->set('lang_key',$language->get('lang_key'));
                    $newAssignment->save();
                }
            }
        }
    }
}
return 'CommerceMultiLangProductUpdateProcessor';