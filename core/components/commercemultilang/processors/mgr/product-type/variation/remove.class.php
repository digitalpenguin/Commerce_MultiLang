<?php
/**
 * Remove a product variation.
 * 
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductVariationRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'CommerceMultiLangProductVariation';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.productvariation';

    public function beforeRemove() {
        // Don't allow the remove if the variation is being used.
        $count = $this->modx->getCount('CommerceMultiLangAssignedVariation',array(
            'variation_id'  =>  $this->object->get('id')
        ));
        //$this->modx->log(1,$count);

        if($count) {
            return 'This variation is currently being used by active products. You need to change the related product types before removing.';
        }
        return parent::beforeRemove();
    }
}
return 'CommerceMultiLangProductVariationRemoveProcessor';