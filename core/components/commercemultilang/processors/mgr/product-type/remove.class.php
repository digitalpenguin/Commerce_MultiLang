<?php
/**
 * Remove a product type.
 * 
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductTypeRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'CommerceMultiLangProductType';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.producttype';

    public function beforeRemove() {
        $variations = $this->modx->getCollection('CommerceMultiLangProductVariation',array(
            'type_id'   => $this->object->get('id')
        ));
        foreach($variations as $variation) {
            $count = $this->modx->getCount('CommerceMultiLangAssignedVariation',array(
                'variation_id'  =>  $variation->get('id')
            ));
            if($count) {
                return 'One of the variations belonging to this product type is currently being used by active products. You need to change the related product types before removing.';
            }
        }
        return parent::beforeRemove();
    }
}
return 'CommerceMultiLangProductTypeRemoveProcessor';