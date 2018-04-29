<?php
/**
 * Get list of product variations
 *
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductVariationGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'CommerceMultiLangProductVariation';
    public $languageTopics = array('commercemultilang:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'commercemultilang.productvariation';
}
return 'CommerceMultiLangProductVariationGetListProcessor';