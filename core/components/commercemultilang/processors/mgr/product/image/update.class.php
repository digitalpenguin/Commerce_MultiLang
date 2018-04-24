<?php
/**
 * Update a product image
 * 
 * @package commercemultilang
 * @subpackage processors
 */

class CommerceMultiLangProductImageUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'CommerceMultiLangProductImage';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.productimage';
}
return 'CommerceMultiLangProductImageUpdateProcessor';