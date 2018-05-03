<?php
/**
 * Get list of languages
 *
 * @package commercemultilang
 * @subpackage processors
 */
class CommerceMultiLangLanguageGetListProcessor extends modProcessor {
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $langKeys = array();

    public function process() {
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
        $data = array();
        $data['success'] = true;
        $data['count'] = count($this->langKeys);
        $data['results'] = $this->langKeys;
        return $this->modx->toJSON($data);
    }
}
return 'CommerceMultiLangLanguageGetListProcessor';
