<?php

namespace ThirdParty\CommerceMultiLang\Modules;
use Symfony\Component\EventDispatcher\EventDispatcher;

class CommerceMultiLang extends \modmore\Commerce\Modules\BaseModule {

    public function getName() {
        return 'CommerceMultiLang';
    }

    public function getAuthor() {
        return 'Murray Wood - Digital Penguin';
    }

    public function getDescription() {
        return 'MultiLingual Products and Variations with UI for Commerce.';
    }

    public function initialize(EventDispatcher $dispatcher) {
        $root = dirname(dirname(__DIR__));
        //$this->adapter->log(1,$root.'/model/');
        $this->adapter->loadPackage('CommerceMultiLang',$root.'/model/');
    }


}