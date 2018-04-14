<?php
/**
 * Get list of products
 *
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'CommerceMultiLangProduct';
    public $languageTopics = array('commercemultilang:default');
    public $defaultSortField = 'name';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'commercemultilang.product';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('CommerceMultiLangProductData','ProductData','ProductData.product_id=CommerceMultiLangProduct.id');
        $c->select('CommerceMultiLangProduct.id,ProductData.position');
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                    'name:LIKE' => '%'.$query.'%',
                    'OR:description:LIKE' => '%'.$query.'%',
                ));
        }
        return $c;
    }
}
return 'CommerceMultiLangProductGetListProcessor';