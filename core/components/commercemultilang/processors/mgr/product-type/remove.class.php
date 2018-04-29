<?php
/**
 * Remove a product type.
 * 
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductTypeRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'CommerceMultiLangProductType';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.producttype';
}
return 'CommerceMultiLangProductTypeRemoveProcessor';