<?php
/**
 * Update a product variation
 * 
 * @package commercemultilang
 * @subpackage processors
 */

class CommerceMultiLangProductVariationUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'CommerceMultiLangProductVariation';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.productvariation';
}
return 'CommerceMultiLangProductVariationUpdateProcessor';