<?php
/**
 * Remove a product and children (variations).
 * 
 * @package commerce_multilang
 * @subpackage processors
 */
class CMLProductRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'CMLProduct';
    public $languageTopics = array('commerce_multilang:default');
    public $objectType = 'commerce_multilang.product';

    public function afterRemove() {
        // Remove alias from the removed product.
        $objectData = $this->modx->getObject('CMLProductData',[
            'product_id'    =>  $this->object->get('id')
        ]);
        $objectData->set('alias','');
        $objectData->save();

        $c = $this->modx->newQuery($this->classKey);
        $c->innerJoin('CMLProductData','ProductData','ProductData.product_id=CMLProduct.id');
        $c->where([
            'ProductData.parent'    =>  $this->object->get('id')
        ]);
        $children = $this->modx->getCollection($this->classKey,$c);
        foreach($children as $child) {
            $child->remove();
        }
        return parent::afterRemove();
    }
}
return 'CMLProductRemoveProcessor';