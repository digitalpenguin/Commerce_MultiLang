<?php
/**
 * Get list Items
 *
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangItemGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'CommerceMultiLangItem';
    public $languageTopics = array('commercemultilang:default');
    public $defaultSortField = 'position';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'commercemultilang.item';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                    'name:LIKE' => '%'.$query.'%',
                    'OR:description:LIKE' => '%'.$query.'%',
                ));
        }
        return $c;
    }
}
return 'CommerceMultiLangItemGetListProcessor';