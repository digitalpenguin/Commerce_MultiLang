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
        $c->leftJoin('CommerceMultiLangProductImageLanguage','ProductImageLanguage',array(
            'CommerceMultiLangProductImage.id=ProductImageLanguage.product_image_id',
            'ProductImageLanguage.lang_key'    =>  $this->modx->getOption('commercemultilang.default_lang')
        ));
        $c->where(array(
            'product_id'=>$this->getProperty('product_id')
        ));
        $c->sortby('main','DESC');
        $c->select('CommerceMultiLangProductImage.*,CommerceMultiLangProductImage.product_id,ProductImageLanguage.title,ProductImageLanguage.image,ProductImageLanguage.alt,ProductImageLanguage.description');
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