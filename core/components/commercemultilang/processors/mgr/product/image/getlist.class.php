<?php
/**
 * Get list of product images
 *
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductImageGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'CommerceMultiLangProductImage';
    public $languageTopics = array('commercemultilang:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'commercemultilang.productimage';
    protected $langKeys = array();

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->where(array(
            'product_id'=>$this->getProperty('product_id')
        ));
        /*$c->prepare();
        $this->modx->log(1,$c->toSQL());*/
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                    'title:LIKE' => '%'.$query.'%'
                ));
        }
        return $c;
    }
}
return 'CommerceMultiLangProductImageGetListProcessor';