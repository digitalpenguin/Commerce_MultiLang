<?php
/**
 * Remove an Item.
 * 
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangItemRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'CommerceMultiLangItem';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.item';
}
return 'CommerceMultiLangItemRemoveProcessor';