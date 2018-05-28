<?php
/**
 * Remove a product and children (variations).
 * 
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'CommerceMultiLangProduct';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.product';

    public function afterRemove() {
        // Remove alias from the removed product.
        $objectData = $this->modx->getObject('CommerceMultiLangProductData',[
            'product_id'    =>  $this->object->get('id')
        ]);
        $objectData->set('alias','');
        $objectData->save();

        $c = $this->modx->newQuery($this->classKey);
        $c->innerJoin('CommerceMultiLangProductData','ProductData','ProductData.product_id=CommerceMultiLangProduct.id');
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
return 'CommerceMultiLangProductRemoveProcessor';