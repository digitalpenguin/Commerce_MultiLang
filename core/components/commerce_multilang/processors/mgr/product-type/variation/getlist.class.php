<?php
/**
 * Get list of product variations
 *
 * @package commerce_multilang
 * @subpackage processors
 */
class CommerceMultiLangProductVariationGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'CommerceMultiLangProductVariation';
    public $languageTopics = array('commerce_multilang:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'commerce_multilang.productvariation';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->where(array('type_id'=>$this->getProperty('type_id')));
        return parent::prepareQueryBeforeCount($c);
    }
}
return 'CommerceMultiLangProductVariationGetListProcessor';