<?php
namespace ThirdParty\CommerceMultiLang\Modules;
use modmore\Commerce\Modules\BaseModule;
use Symfony\Component\EventDispatcher\EventDispatcher;
require_once MODX_CORE_PATH.'components/commerce/vendor/autoload.php';

class CommerceMultiLang extends BaseModule {
    public function getName() {
        $this->adapter->loadLexicon('commercemultilang:default');
        return $this->adapter->lexicon('commercemultilang');
    }

    public function getAuthor() {
        return 'Murray Wood';
    }

    public function getDescription() {
        return $this->adapter->lexicon('commercemultilang.description');
    }

    public function initialize(EventDispatcher $dispatcher) {
        // Load our lexicon
        //$this->adapter->log(1,'Test');
        //$this->adapter->loadLexicon('commercemultilang:default');
        // Add the xPDO package, so Commerce can detect the derivative classes
        //$root = dirname(dirname(__DIR__));
        //$root = '/home/vagrant/code/commerce/public_html/packages/commercemultilang/core/components/commercemultilang';
        //$path = $root . '/model/';
        //$this->adapter->loadPackage('commercemultilang', $path);
        // Add template path to twig
        ///** @var ChainLoader $loader */
        //$root = dirname(dirname(__DIR__));
        //$loader = $this->commerce->twig->getLoader();
        //$loader->addLoader(new FilesystemLoader($root . '/templates/'));
    }

    public function getModuleConfiguration(\comModule $module) {
        $fields = [];
//        $fields[] = new DescriptionField($this->commerce, [
//            'description' => $this->adapter->lexicon('commerce_projectname.module_description'),
//        ]);
        return $fields;
    }
}