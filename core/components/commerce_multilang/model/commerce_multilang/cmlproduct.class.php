<?php

use modmore\Commerce\Admin\Widgets\Form\Tab;
use modmore\Commerce\Admin\Widgets\Form\TextareaField;
use modmore\Commerce\Admin\Widgets\Form\TextField;

/**
 * @package commerce_multilang
 */
class CMLProduct extends comProduct {
    protected $extendedData = null;

    /**
     * Convenience function to get access to service class functions
     * @return Commerce_MultiLang|false
     */
    public function getServiceClass() {
        $commerceMultiLang = $this->adapter->getService('commerce_multilang', 'Commerce_MultiLang', $this->adapter->getOption('commerce_multilang.core_path', null, $this->adapter->getOption('core_path') . 'components/commerce_multilang/') . 'model/commerce_multilang/');
        if (!($commerceMultiLang instanceof Commerce_MultiLang)) {
            $this->adapter->log(MODX_LOG_LEVEL_ERROR, 'Error loading the Commerce_MultiLang service class.');
            return false;
        } else {
            return $commerceMultiLang;
        }
    }

    public function loadExtendedData() {
        $c = $this->adapter->newQuery('CMLProduct');
        $c->setClassAlias('Product');
        $c->leftJoin('CMLProductLanguage','Language',[
            'Language.product_id=Product.id',
            'Language.lang_key'  =>  $this->adapter->getOption('cultureKey')
        ]);
        $c->where(['Product.id' =>  $this->get('id')]);
        $c->select(['Product.*']);
        $c->select($this->adapter->getSelectColumns('CMLProductLanguage',
            'Language','',['lang_key','name','description']));
        //$c->prepare();
        //$this->adapter->log(1,$c->toSQL());

        if ($c->prepare() && $c->stmt->execute()) {
            $this->extendedData =  $c->stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function getName() {
        if(!$this->extendedData) {
            $this->loadExtendedData();
        }
        if (!empty($this->extendedData['name'])) {
            return $this->extendedData['name'];
        }
        return $this->get('name');
    }

    public function getDescription() {
        if(!$this->extendedData) {
            $this->loadExtendedData();
        }
        if (!empty($this->extendedData['description'])) {
            return $this->extendedData['description'];
        }
        return $this->get('description');
    }

    /**
     * Retrieves values for all language fields except the default language in a 2D array.
     * Each sub-array is denoted by the lang_key.
     * @param array $langKeys
     * @return array
     */
    public function getFieldValues(array $langKeys) : array
    {
        $output = [];
        $c = $this->adapter->newQuery('CMLProductLanguage');
        $c->where([
            'product_id:='      =>  $this->get('id'),
            'AND:lang_key:IN'   =>  $langKeys
        ]);
        $languageFields = $this->adapter->getCollection('CMLProductLanguage',$c);
        if(empty($languageFields)) return $output;

        foreach($langKeys as $langKey) {
            foreach ($languageFields as $languageField) {
                if($languageField->get('lang_key') === $langKey) {
                    $output[$langKey]['name'] = $languageField->get('name');
                    $output[$langKey]['description'] = $languageField->get('description');
                }
            }
        }

        return $output;
    }

    /**
     * {@inheritdoc}
     *
     * For Products specifically, this also adds the link and edit_link options to the returned array.
     *
     * @param string $keyPrefix
     * @param bool|false $rawValues
     * @param bool|false $excludeLazy
     * @param bool|false $includeRelated
     * @return array
     */
    public function toArray($keyPrefix = '', $rawValues = false, $excludeLazy = false, $includeRelated = false) : array
    {
        $output = parent::toArray($keyPrefix, $rawValues, $excludeLazy, $includeRelated);

        // Only adjust this if not in the manager context. Keep default lang for the mgr users
        if($this->commerce->modx->context->key !== 'mgr') {

            // Check the current cultureKey and if it matches
            $this->loadExtendedData();
            if (!empty($this->extendedData['name'])) {
                $output['name'] = $this->extendedData['name'];
            }
            if (!empty($this->extendedData['description'])) {
                $output['description'] = $this->extendedData['description'];
            }
        }
        return $output;
    }


    /**
     * Overrides the comProduct function and adds extra language fields in a tab.
     * @return array|\modmore\Commerce\Admin\Widgets\Form\Field[]
     */
    public function getModelFields()
    {
        $fields = parent::getModelFields();

        $fields[] = new Tab($this->commerce, [
            'label' => 'Language Translations',
        ]);

        // Add name and description fields for each language
        $commerceMultiLang = $this->getServiceClass();
        $langKeys = $commerceMultiLang->getLanguageKeys();

        // Grab values for each lang_key
        $fieldValues = $this->getFieldValues($langKeys);
        foreach($langKeys as $langKey) {
            $fields[] = new TextField($this->commerce, [
                'name'          =>  'name_'.$langKey,
                'label'         =>  $this->adapter->lexicon('commerce.name').' ('.$langKey.')',
                'value'         =>  $fieldValues[$langKey]['name'],
            ]);
            $fields[] = new TextareaField($this->commerce, [
                'name'          =>  'description_'.$langKey,
                'label'         =>  $this->adapter->lexicon('commerce.description').' ('.$langKey.')',
                'value'         =>  $fieldValues[$langKey]['description']
            ]);
        }

        return $fields;
    }

    public function save($cacheFlag = null)
    {
        $saved = parent::save($cacheFlag);
        $this->updatePriceIndex();

        // Grab the values of the language fields that were submitted.
        $commerceMultiLang = $this->getServiceClass();
        $langKeys = $commerceMultiLang->getLanguageKeys();

        // Get any existing language objects
        $langObjects = $this->adapter->getCollection('CMLProductLanguage',[
            'product_id'    =>  $this->get('id')
        ]);

        // Get language values via POST and sanitize them.
        foreach($langKeys as $langKey) {

            $properties = [
                'lang_key'  =>  $langKey
            ];

            // Sanitize name value
            if(isset($_POST['name_'.$langKey])) {
                $properties['name'] = filter_input(INPUT_POST,'name_'.$langKey,FILTER_SANITIZE_STRING);
            }

            // Sanitize description value
            if(isset($_POST['description_'.$langKey])) {
                $properties['description'] = filter_input(INPUT_POST,'description_'.$langKey,FILTER_SANITIZE_STRING);
            }

            // Attempt to get existing object - if does not yet exist, create a new one.
            $langObject = $this->adapter->getObject('CMLProductLanguage',[
                'product_id'    =>  $this->get('id'),
                'lang_key'      =>  $langKey
            ]);
            if(!$langObject instanceof CMLProductLanguage) {
                $properties['product_id'] = $this->get('id');
                $langObject = $this->adapter->newObject('CMLProductLanguage');
            }

            $langObject->fromArray($properties);
            $langObject->save();
        }

        return $saved;
    }
}
?>