<?php
/**
 * Update a product type
 * 
 * @package commerce_multilang
 * @subpackage processors
 */

class CMLProductTypeUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'CMLProductType';
    public $languageTopics = array('commerce_multilang:default');
    public $objectType = 'commerce_multilang.producttype';
}
return 'CMLProductTypeUpdateProcessor';