<?php
/**
 * Update a product variation
 * 
 * @package commerce_multilang
 * @subpackage processors
 */

class CMLProductVariationUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'CMLProductVariation';
    public $languageTopics = array('commerce_multilang:default');
    public $objectType = 'commerce_multilang.productvariation';
}
return 'CMLProductVariationUpdateProcessor';