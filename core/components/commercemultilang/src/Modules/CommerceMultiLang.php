<?php

namespace DigitalPenguin\CommerceMultiLang\Modules;
use modmore\Commerce\Admin\Generator;
use modmore\Commerce\Events\Admin\TopNavMenu as TopNavMenuEvent;
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
        $root = dirname(dirname(__DIR__)).'/model/';
        if(!$this->adapter->loadPackage('commercemultilang',$root)) {
            $this->adapter->log(1, 'Unable to load the CommerceMultiLang package.');
        }
        //$dispatcher->addListener(Generator::COLLECT_MENU_EVENT, array($this, 'alterProductsTab'));
    }

    public function alterProductsTab(TopNavMenuEvent $event) {
        $items = $event->getItems();

        if (array_key_exists('products', $items)) {
            $items['products']['link'] = '?namespace=commercemultilang&a=home';
        }

        $event->setItems($items);
    }
}