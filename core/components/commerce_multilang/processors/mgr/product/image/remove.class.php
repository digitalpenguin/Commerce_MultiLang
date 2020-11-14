<?php
/**
 * Remove a product image.
 * 
 * @package commerce_multilang
 * @subpackage processors
 */
class CMLProductImageRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'CMLProductImage';
    public $languageTopics = array('commerce_multilang:default');
    public $objectType = 'commerce_multilang.productimage';
}
return 'CMLProductImageRemoveProcessor';