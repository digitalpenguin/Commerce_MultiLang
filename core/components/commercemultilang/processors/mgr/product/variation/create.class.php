<?php
require dirname(dirname(__FILE__)).'/create.class.php';
/**
 * Create a Product
 * 
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductChildCreateProcessor extends CommerceMultiLangProductCreateProcessor {
    protected $parentObj = null;
    protected $variationData = array();

    public function initialize() {
        $this->getLanguages();
        $this->loadParentProduct();
        return parent::initialize();
    }

    protected function loadParentProduct() {
        $this->parentObj = $this->modx->getObject($this->classKey, array('id' => $this->getProperty('parent')));
    }

    public function beforeSet() {
        // Make sure we've got the parent.
        if(!$this->parentObj) return $this->failure('Unable to load parent product.');

        //TODO: Duplicate parent values into new object.
        //TODO: Read variation fields for this product type, then set those values.

        return parent::beforeSet();
    }

    protected function getVariationFields() {
        //$this->modx->log(1,$this->getProperty('parent_id'));
    }

    public function beforeSave() {

        $variations = $this->getVariationFields();

        //$aliasText = $this->object->get('name').'-'.$this->object->''
        $this->generateProductAlias($this->object->get('name'));
        //return parent::beforeSave();
        return false;
    }

    public function afterSave() {
        //$this->flatRowData['product'] = $this->object;
        foreach ($this->langKeys as $langKey) {
            $productLang = $this->modx->newObject('CommerceMultiLangProductLanguage');
            $productLang->set('product_id', $this->object->get('id'));
            $productLang->set('name', $this->object->get('name'));
            $productLang->set('lang_key', $langKey['lang_key']);
            $productLang->set('description', $this->object->get('description'));

            if($langKey['lang_key'] == $this->modx->getOption('commercemultilang.default_lang')) {
                $productLang->set('category', $this->object->get('category'));
            } else {
                $context = $this->modx->getContext($langKey['context_key']);
                $rootCategoryId = $context->getOption('commercemultilang.category_root_id');
                if($rootCategoryId) {
                    // Set category_root_id for any language other than default. User can change later.
                    $productLang->set('category', $rootCategoryId);
                } else {
                    // The category_root_id setting isn't set on context, add 0 as category
                    $productLang->set('category', 0);
                }
            }
            $productLang->save();
            //$this->flatRowData['productLanguages'][$productLang->get('id')] = $productLang;
        }
        $productData = $this->modx->newObject('CommerceMultiLangProductData');
        $productData->set('product_id',$this->object->get('id'));
        $productData->set('alias', $this->alias);
        $productData->set('parent', 0);
        $productData = $this->setProductListing($productData);
        foreach($this->getProperties() as $key => $value) {
            $productData->set($key,$value);
        }
        $productData->save();
        //$this->flatRowData['productData'] = $productData;
        //$this->createFlatRow();
        return parent::afterSave();
    }

    public function setProductListing($productData) {
        $productData->set('product_listing',0);
        $productData->set('parent',$this->getProperty('parent_id'));
        return $productData;
    }

    /**
     * Creates a new column directly on the DB table to represent the new variation.
     */
    protected function createVariationColumns() {
        $class = 'CommerceMultiLangProductLanguage';
        $tableName = $this->modx->escape($this->modx->getTableName($class));

        $productArray = $this->variationData['product']->toArray();
        $productDataArray = $this->variationData['productData']->toArray();
        $fieldRow = array();
        $fieldRow['product_id'] = $productArray['id'];
        foreach($productArray as $k=>$v) {
            switch($k) {
                case 'id':
                case 'removed':
                case 'removed_on':
                case 'removed_by':
                case 'action':
                case 'removed_on_formatted':
                case 'link':
                case 'edit_link':
                    continue;
                default:
                    $fieldRow[$k] = $productArray[$k];
                    $this->loadXpdoField($class,$k,'text','string');
            }
        }
        $fieldRow['alias'] = $productDataArray['alias'];
        $this->loadXpdoField($class,'alias','varchar','string',true);
        foreach ($this->flatRowData['productLanguages'] as $lang) {
            $fieldRow['name_'.$lang->get('lang_key')] = $lang->get('name');
            $name = $this->modx->escape('name_'.$lang->get('lang_key'));

            $fieldRow['description_'.$lang->get('lang_key')] = $lang->get('description');
            $description = $this->modx->escape('description_'.$lang->get('lang_key'));
            //TODO: find work-around so table names can have hypens in xpdo
        }
        foreach($fieldRow as $k => $v) {
            $rs = $this->modx->query("SELECT {$this->modx->escape($k)} FROM {$tableName}");
            if(!$rs) {
                if($k == 'alias') {
                    $this->modx->query("ALTER TABLE {$tableName} ADD {$this->modx->escape($k)} VARCHAR( 190 ) NOT NULL");
                    $this->modx->query("ALTER TABLE {$tableName} ADD INDEX FOR col ({$this->modx->escape($k)})");
                    $this->modx->query("ALTER TABLE {$tableName} ADD DEFAULT ('') FOR {$this->modx->escape($k)}");

                } else {
                    $this->modx->query("ALTER TABLE {$tableName} ADD {$this->modx->escape($k)} TEXT NOT NULL");
                    $this->modx->query("ALTER TABLE {$tableName} ADD DEFAULT ('') FOR {$this->modx->escape($k)}");
                }
            }
        }
        $newRow = $this->modx->newObject('CommerceMultiLangFlatRow');
        foreach($fieldRow as $k => $field) {
            //$this->modx->log(1,print_r($this->modx->map[$class]['fieldMeta'],true));
            //$this->modx->log(1,print_r($this->modx->map[$class]['fieldMeta'][$k],true));
            $newRow->set($k,$field);
        }
        $newRow->save();
    }

    /**
     * loadXpdoField - required by the experimental function loadFlatRow
     * @param $class
     * @param $field
     * @param $dbType
     * @param $phpType
     * @param bool $index
     *
     */
    protected function loadXpdoField($class,$field,$dbType,$phpType,$index=false) {
        if (!isset($this->modx->map[$class]['fieldMeta'][$field])) {
            $this->modx->map[$class]['fields'][$field] = 0;
            $this->modx->map[$class]['fieldMeta'][$field] = array(
                'dbtype' => $dbType,
                'phptype' => $phpType,
                'null' => false,
                'default' => '',
            );
            if($index) {
                $this->modx->map[$class]['fieldMeta'][$field]['index'] = 'index';
            }
            if($dbType == 'varchar') {
                $this->modx->map[$class]['fieldMeta'][$field]['precision'] = 190;
            }
        }
    }


}
return 'CommerceMultiLangProductChildCreateProcessor';
