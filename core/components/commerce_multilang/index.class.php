<?php
require_once dirname(__FILE__) . '/model/commerce_multilang/commerce_multilang.class.php';
/**
 * @package commerce_multilang
 */

abstract class CommerceMultiLangBaseManagerController extends modExtraManagerController {
    /** @var Commerce_MultiLang $commerce_multilang */
    public $commerce_multilang;
    public function initialize() {
        $this->commerce_multilang = new Commerce_MultiLang($this->modx);

        $this->addCss($this->commerce_multilang->getOption('cssUrl').'mgr.css');
        $this->addJavascript($this->commerce_multilang->getOption('jsUrl').'mgr/commerce_multilang.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            Commerce_MultiLang.config = '.$this->modx->toJSON($this->commerce_multilang->options).';
            Commerce_MultiLang.config.connector_url = "'.$this->commerce_multilang->getOption('connectorUrl').'";
        });
        </script>');
        
        parent::initialize();
    }
    public function getLanguageTopics() {
        return array('commerce_multilang:default');
    }
    public function checkPermissions() { return true;}
}