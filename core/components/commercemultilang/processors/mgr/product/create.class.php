<?php
/**
 * Create a Product
 * 
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangProductCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'CommerceMultiLangProduct';
    public $languageTopics = array('commercemultilang:default');
    public $objectType = 'commercemultilang.product';
    protected $langKeys = array();
    protected $alias;
    protected $variationData = array();
    //protected $flatRowData = array();

    protected function getLanguages() {
        $c = $this->modx->newQuery('modContext');
        $c->leftJoin('modContextSetting','ContextSettings','modContext.key=ContextSettings.context_key');
        $c->select('modContext.key,ContextSettings.key as setting_key,ContextSettings.value as lang_key');
        $c->where(array(
            'modContext.key:!=' => 'mgr',
            'AND:ContextSettings.key:=' => 'cultureKey'
        ));
        $contexts = $this->modx->getCollection('modContext',$c);
        foreach ($contexts as $context) {
            $contextArray = $context->toArray();
            //$this->modx->log(1,print_r($contextArray,true));
            $lang = array();
            $lang['context_key'] = $contextArray['key'];
            $lang['lang_key'] = $contextArray['lang_key'];
            $lang['name'] = $contextArray['name'];
            array_push($this->langKeys,$lang);
        }
    }

    public function initialize() {
        $this->getLanguages();
        return parent::initialize();
    }

    public function beforeSave() {
        $this->generateProductAlias($this->object->get('name'));
        return parent::beforeSave();
    }

    protected function generateProductAlias($text) {
        $letters = array(
            '–', '"','\'', '«', '»', '&', '÷', '>','<', '$', '/'
        );
        $text = str_replace($letters, " ", $text);
        $text = str_replace("&", "and", $text);
        $text = str_replace("?", "", $text);
        $alias = strtolower(str_replace(" ", "-", $text));
        $count = $this->modx->getCount('CommerceMultiLangProductData',array(
            'alias' => $alias
        ));
        if ($count) {
            $this->addFieldError('name',$this->modx->lexicon('commercemultilang.err.product_alias_ae'));
        }
        $this->alias = $alias;
    }

    public function afterSave() {


        $productData = $this->modx->newObject('CommerceMultiLangProductData');
        $productData->set('product_id',$this->object->get('id'));
        $productData->set('alias', $this->alias);
        $productData->set('parent', 0);
        $productData->set('product_listing',1);
        $productData->set('type',$this->object->get('type'));
        $productData->save();

        // These can only be loaded after the CommerceMultiLangProductData object exists.
        $this->loadVariationFields();

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

            // Set the variation field values by creating new many-to-many records for each one.
            foreach($this->variationData as $variation) {
                $varField = $this->modx->newObject('CommerceMultiLangAssignedVariation');
                $varField->set('variation_id',$variation->get('id'));
                $varField->set('product_id',$this->object->get('id'));
                $varField->set('name',$variation->get('name'));
                $varField->set('lang_key',$langKey['lang_key']);
                $varField->set('value','');
                $varField->save();
            }
            //$this->flatRowData['productLanguages'][$productLang->get('id')] = $productLang;
        }

        //$this->flatRowData['productData'] = $productData;
        //$this->createFlatRow();
        return parent::afterSave();
    }


    protected function loadVariationFields() {
        $productData = $this->modx->getObject('CommerceMultiLangProductData',array(
            'product_id'    =>  $this->object->get('id')
        ));
        if($productData) {
            $variations = $this->modx->getCollection('CommerceMultiLangProductVariation',array(
                'type_id'   =>  $productData->get('type')
            ));
            foreach($variations as $variation) {
                array_push($this->variationData,$variation);
            }
        }
    }

    /**
     * EXPERIMENTAL - experimental function for creating a flat row on save. Not yet functional and not decided yet
     * if it's a good way to go. Also requires the included plugin loadFlatRow which should be loaded at the onMODXInit
     * system event. It will load the table into xpdo when modx initialises.
     */
    /*protected function createFlatRow() {
        $class = 'CommerceMultiLangFlatRow';
        $tableName = $this->modx->escape($this->modx->getTableName($class));
        $productArray = $this->flatRowData['product']->toArray();
        $productDataArray = $this->flatRowData['productData']->toArray();
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
    }*/

    /**
     * loadXpdoField - required by the experimental function loadFlatRow
     * @param $class
     * @param $field
     * @param $dbType
     * @param $phpType
     * @param bool $index
     *
     */
    /*protected function loadXpdoField($class,$field,$dbType,$phpType,$index=false) {
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
    }*/


}
return 'CommerceMultiLangProductCreateProcessor';
