<?php

namespace DigitalPenguin\Commerce_MultiLang\Modules;
use Symfony\Component\EventDispatcher\EventDispatcher;

class MultiLang extends \modmore\Commerce\Modules\BaseModule {

    public function getName() {
        return 'MultiLang';
    }

    public function getAuthor() {
        return 'Murray Wood - Digital Penguin';
    }

    public function getDescription() {
        return 'Multilingual Products for Commerce.';
    }

    public function initialize(EventDispatcher $dispatcher) {
        // Load our lexicon
        $this->adapter->loadLexicon('commerce_multilang:default');

        // Add template path to twig
        //$root = dirname(__DIR__, 2);
        //$this->commerce->view()->addTemplatesPath($root . '/templates/');

        // Load Model
        $modelDir = dirname(__DIR__, 2).'/model/';
        if(!$this->adapter->loadPackage('commerce_multilang',$modelDir)) {
            $this->adapter->log(MODX_LOG_LEVEL_ERROR, 'Unable to load the MultiLang package.');
        }
    }

}