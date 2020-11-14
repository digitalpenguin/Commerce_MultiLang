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
        $count = $this->modx->getCount('CMLAssignedVariation',array(
            'variation_id'  =>  $this->object->get('id')
        ));
        //$this->modx->log(1,$count);

        if($count) {
            return 'This variation is currently being used by active products. You need to change the related product types before removing.';
        }
        return parent::beforeRemove();
    }
}
return 'CMLProductVariationRemoveProcessor';