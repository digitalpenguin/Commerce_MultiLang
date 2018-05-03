<?php

/**
 * Get list of variations name associated with this product.
 *
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangGetVariationsProcessor extends modObjectGetListProcessor {
    public $classKey = 'CommerceMultiLangAssignedVariation';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->where(array('product_id'=>$this->getProperty('product_id')));
        return parent::prepareQueryBeforeCount($c);
    }

}
return 'CommerceMultiLangGetVariationsProcessor';