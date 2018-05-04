<?php

/**
 * Get list of variations name associated with this product.
 *
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangGetVariationsProcessor extends modObjectGetListProcessor {
    public $classKey = 'CommerceMultiLangProductVariation';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $product = $this->modx->getObject('CommerceMultiLangProductData',array(
            'product_id'    =>  $this->getProperty('product_id')
        ));
        if(!$product) return false;
        $c->where(array(
            'type_id'   =>  $product->get('type')
        ));
        return parent::prepareQueryBeforeCount($c);
    }

}
return 'CommerceMultiLangGetVariationsProcessor';