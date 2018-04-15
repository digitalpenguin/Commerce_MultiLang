<?php
require_once dirname(dirname(__FILE__)) . '/index.class.php';
/**
 * Loads the home page.
 *
 * @package commercemultilang
 * @subpackage controllers
 */
class CommerceMultiLangHomeManagerController extends CommerceMultiLangBaseManagerController {
    public function process(array $scriptProperties = array()) {

    }
    public function getPageTitle() { return $this->modx->lexicon('commercemultilang'); }
    public function loadCustomCssJs() {
    
    
        $this->addJavascript($this->commercemultilang->getOption('jsUrl').'mgr/extras/griddraganddrop.js');
        $this->addJavascript($this->commercemultilang->getOption('jsUrl').'mgr/widgets/combos.widget.js');
        $this->addJavascript($this->commercemultilang->getOption('jsUrl').'mgr/widgets/productupdate.window.js');
        $this->addJavascript($this->commercemultilang->getOption('jsUrl').'mgr/widgets/products.grid.js');
        $this->addJavascript($this->commercemultilang->getOption('jsUrl').'mgr/widgets/home.panel.js');
        $this->addLastJavascript($this->commercemultilang->getOption('jsUrl').'mgr/sections/home.js');
    
    }

    public function getTemplateFile() { return $this->commercemultilang->getOption('templatesPath').'home.tpl'; }
}