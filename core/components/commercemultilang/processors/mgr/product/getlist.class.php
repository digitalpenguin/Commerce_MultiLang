<?php
/**
 * Get list of products
 *
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'CommerceMultiLangProduct';
    public $languageTopics = array('commercemultilang:default');
    public $defaultSortField = 'position';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'commercemultilang.product';

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
return 'CommerceMultiLangProductGetListProcessor';