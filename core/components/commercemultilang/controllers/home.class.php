<?php
require_once dirname(dirname(__FILE__)) . '/index.class.php';
/**
 * Loads the home page.
 *
 * @package commercemultilang
 * @subpackage controllers
 */
class CommerceMultiLangHomeManagerController extends CommerceMultiLangBaseManagerController {
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
    public function getPageTitle() { return $this->modx->lexicon('commercemultilang'); }
    public function loadCustomCssJs() {
    
    
        $this->addJavascript($this->commercemultilang->getOption('jsUrl').'mgr/extras/griddraganddrop.js');
        $this->addJavascript($this->commercemultilang->getOption('jsUrl').'mgr/widgets/combos.widget.js');
        $this->addJavascript($this->commercemultilang->getOption('jsUrl').'mgr/widgets/productupdatevariations.grid.js');
        $this->addJavascript($this->commercemultilang->getOption('jsUrl').'mgr/widgets/productupdate.window.js');
        $this->addJavascript($this->commercemultilang->getOption('jsUrl').'mgr/widgets/producttypevariations.grid.js');
        $this->addJavascript($this->commercemultilang->getOption('jsUrl').'mgr/widgets/producttypes.grid.js');
        $this->addJavascript($this->commercemultilang->getOption('jsUrl').'mgr/widgets/products.grid.js');
        $this->addJavascript($this->commercemultilang->getOption('jsUrl').'mgr/widgets/home.panel.js');
        $this->addLastJavascript($this->commercemultilang->getOption('jsUrl').'mgr/sections/home.js');
    
    }

    public function getTemplateFile() { return $this->commercemultilang->getOption('templatesPath').'home.tpl'; }
}