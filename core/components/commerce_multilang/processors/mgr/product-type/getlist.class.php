<?php
/**
 * Get list of product types
 *
 * @package commerce_multilang
 * @subpackage processors
 */
class CMLProductTypeGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'CMLProductType';
    public $languageTopics = array('commerce_multilang:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'commerce_multilang.producttype';
}
return 'CMLProductTypeGetListProcessor';