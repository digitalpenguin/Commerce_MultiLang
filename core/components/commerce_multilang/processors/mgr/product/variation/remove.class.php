<?php
/**
 * Remove a product.
 * 
 * @package commerce_multilang
 * @subpackage processors
 */
class CMLProductRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'CMLProduct';
    public $languageTopics = array('commerce_multilang:default');
    public $objectType = 'commerce_multilang.product';
}
return 'CMLProductRemoveProcessor';