<?php
/**
 * Remove a product.
 * 
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'CommerceMultiLangProduct';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.product';
}
return 'CommerceMultiLangProductRemoveProcessor';