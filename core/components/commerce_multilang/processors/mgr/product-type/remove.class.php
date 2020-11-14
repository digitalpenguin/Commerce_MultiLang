<?php
/**
 * Remove a product type.
 * 
 * @package commerce_multilang
 * @subpackage processors
 */
class CommerceMultiLangProductTypeRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'CommerceMultiLangProductType';
    public $languageTopics = array('commerce_multilang:default');
    public $objectType = 'commerce_multilang.producttype';

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