<?php
require_once dirname(dirname(__FILE__)) . '/index.class.php';
/**
 * Loads the home page.
 *
 * @package commerce_multilang
 * @subpackage controllers
 */
class Commerce_multilangHomeManagerController extends CommerceMultiLangBaseManagerController {
    public function loadRichTextEditor() {
        $useEditor = $this->modx->getOption('use_editor');
        $whichEditor = $this->modx->getOption('which_editor');
        if ($useEditor && !empty($whichEditor)) {
            // invoke the OnRichTextEditorInit event
            $onRichTextEditorInit = $this->modx->invokeEvent('OnRichTextEditorInit',array(
                'editor' => $whichEditor, // Not necessary for Redactor
                'elements' => array('foo'), // Not necessary for Redactor
            ));
            if (is_array($onRichTextEditorInit)) {
                $onRichTextEditorInit = implode('', $onRichTextEditorInit);
            }
            $this->setPlaceholder('onRichTextEditorInit', $onRichTextEditorInit);
        }
    }

    public function process(array $scriptProperties = array()) {
        $this->loadRichTextEditor();
    }
    public function getPageTitle() { return $this->modx->lexicon('commerce_multilang'); }
    public function loadCustomCssJs() {
    
    
        $this->addJavascript($this->commerce_multilang->getOption('jsUrl').'mgr/extras/griddraganddrop.js');
        $this->addJavascript($this->commerce_multilang->getOption('jsUrl').'mgr/widgets/combos.widget.js');
        $this->addJavascript($this->commerce_multilang->getOption('jsUrl').'mgr/widgets/products/productupdatevariations.grid.js');
        $this->addJavascript($this->commerce_multilang->getOption('jsUrl').'mgr/widgets/products/productupdate.window.js');
        $this->addJavascript($this->commerce_multilang->getOption('jsUrl').'mgr/widgets/variations/producttypevariations.grid.js');
        $this->addJavascript($this->commerce_multilang->getOption('jsUrl').'mgr/widgets/variations/producttypes.grid.js');
        $this->addJavascript($this->commerce_multilang->getOption('jsUrl').'mgr/widgets/products/products.grid.js');
        $this->addJavascript($this->commerce_multilang->getOption('jsUrl').'mgr/widgets/home.panel.js');
        $this->addLastJavascript($this->commerce_multilang->getOption('jsUrl').'mgr/sections/home.js');
    
    }

    public function getTemplateFile() { return $this->commerce_multilang->getOption('templatesPath').'home.tpl'; }
}