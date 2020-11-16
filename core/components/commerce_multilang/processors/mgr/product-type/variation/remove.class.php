<?php
/**
 * Remove a product variation.
 * 
 * @package commerce_multilang
 * @subpackage processors
 */
class CMLProductVariationRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'CMLProductVariation';
    public $languageTopics = array('commerce_multilang:default');
    public $objectType = 'commerce_multilang.productvariation';

    public function beforeRemove() {
        // Don't allow the remove if the variation is being used.
        $count = $this->modx->getCount('CMLProductData',[
            'type'  =>  $this->object->get('type_id')
        ]);

        if($count) {
            return $this->modx->lexicon('commerce_multilang.product_variation.remove_error');
        }
        return parent::beforeRemove();
    }
}
return 'CMLProductVariationRemoveProcessor';