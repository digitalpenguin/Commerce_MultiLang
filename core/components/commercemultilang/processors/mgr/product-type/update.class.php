<?php
/**
 * Update a product type
 * 
 * @package commercemultilang
 * @subpackage processors
 */

class CommerceMultiLangProductTypeUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'CommerceMultiLangProductType';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.producttype';
}
return 'CommerceMultiLangProductTypeUpdateProcessor';