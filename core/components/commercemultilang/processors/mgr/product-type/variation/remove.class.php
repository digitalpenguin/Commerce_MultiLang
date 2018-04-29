<?php
/**
 * Remove a product variation.
 * 
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductVariationRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'CommerceMultiLangProductVariation';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.productvariation';
}
return 'CommerceMultiLangProductVariationRemoveProcessor';