<?php

/**
 * Get list of variations name associated with this product.
 *
 * @package commerce_multilang
 * @subpackage processors
 */
class CMLGetVariationsProcessor extends modObjectGetListProcessor {
    public $classKey = 'CMLProductVariation';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $product = $this->modx->getObject('CMLProductData',array(
            'product_id'    =>  $this->getProperty('product_id')
        ));
        if(!$product) return false;
        $c->where(array(
            'type_id'   =>  $product->get('type')
        ));
        return parent::prepareQueryBeforeCount($c);
    }

}
return 'CMLGetVariationsProcessor';