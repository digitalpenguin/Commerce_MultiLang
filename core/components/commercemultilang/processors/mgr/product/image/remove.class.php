<?php
/**
 * Remove a product image.
 * 
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductImageRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'CommerceMultiLangProductImage';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.productimage';
}
return 'CommerceMultiLangProductImageRemoveProcessor';