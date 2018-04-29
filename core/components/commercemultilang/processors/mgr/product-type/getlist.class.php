<?php
/**
 * Get list of product types
 *
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductTypeGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'CommerceMultiLangProductType';
    public $languageTopics = array('commercemultilang:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'commercemultilang.producttype';
}
return 'CommerceMultiLangProductTypeGetListProcessor';