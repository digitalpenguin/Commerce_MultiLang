<?php
require_once dirname(__FILE__) . '/model/commerce_multilang/commerce_multilang.class.php';
/**
 * @package commerce_multilang
 */

abstract class CommerceMultiLangBaseManagerController extends modExtraManagerController {
    /** @var CommerceMultiLang $commercemultilang */
    public $commercemultilang;
    public function initialize() {
        $this->commercemultilang = new CommerceMultiLang($this->modx);

        $this->addCss($this->commercemultilang->getOption('cssUrl').'mgr.css');
        $this->addJavascript($this->commercemultilang->getOption('jsUrl').'mgr/commerce_multilang.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            CommerceMultiLang.config = '.$this->modx->toJSON($this->commercemultilang->options).';
            CommerceMultiLang.config.connector_url = "'.$this->commercemultilang->getOption('connectorUrl').'";
        });
        </script>');
        
        parent::initialize();
    }
    public function getLanguageTopics() {
        return array('commerce_multilang:default');
    }
    public function checkPermissions() { return true;}
}