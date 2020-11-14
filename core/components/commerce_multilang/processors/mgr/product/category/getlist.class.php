<?php
/**
 * Get list of Categories
 *
 * @package commerce_multilang
 * @subpackage processors
 */
class CMLCategoryGetListProcessor extends modProcessor {
    protected $categoryRoot;
    protected $contextKey;

    public function initialize() {
        $this->contextKey = $this->getProperty('context_key');
        $context = $this->modx->getContext($this->contextKey);
        $this->categoryRoot = $context->getOption('commerce_multilang.category_root_id');
        return parent::initialize();
    }

    public function process() {
        $catIds = $this->modx->getChildIds($this->categoryRoot,10,array('context' => $this->contextKey));
        //$this->modx->log(1,print_r($catIds,true));
        $c = $this->modx->newQuery('modResource');
        // need to check if any children otherwise :IN filter will give an error.
        if(count($catIds)) {
            $c->where(array(
                'id:='  => $this->categoryRoot,
                'OR:id:IN'    => $catIds
            ));
        } else {
            $c->where(array(
                'id:='  => $this->categoryRoot
            ));
        }
        $categories = $this->modx->getCollection('modResource',$c);
        $categoriesArray = array();
        foreach ($categories as $category) {
            array_push($categoriesArray,$category->toArray());
        }
        $data = array();
        $data['success'] = true;
        $data['count'] = count($categoriesArray);
        $data['results'] = $categoriesArray;
        return $this->modx->toJSON($data);
    }
}
return 'CMLCategoryGetListProcessor';