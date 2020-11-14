<?php

namespace DigitalPenguin\Commerce_MultiLang\Modules;
use modmore\Commerce\Admin\Generator;
use modmore\Commerce\Events\Admin\TopNavMenu as TopNavMenuEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class MultiLang extends \modmore\Commerce\Modules\BaseModule {

    public function getName() {
        return 'MultiLang';
    }

    public function getAuthor() {
        return 'Murray Wood - Digital Penguin';
    }

    public function getDescription() {
        return 'MultiLingual Products and Variations with UI for Commerce.';
    }

    public function initialize(EventDispatcher $dispatcher) {
        $root = dirname(dirname(__DIR__)).'/model/';
        if(!$this->adapter->loadPackage('commerce_multilang',$root)) {
            $this->adapter->log(1, 'Unable to load the MultiLang package.');
        }
        $dispatcher->addListener(Generator::COLLECT_MENU_EVENT, array($this, 'alterProductsTab'));
    }

    public function alterProductsTab(TopNavMenuEvent $event) {
        $items = $event->getItems();

        if (array_key_exists('products', $items)) {
            $items['products']['link'] = '?namespace=commerce_multilang&a=home';
            // Added in Commerce 0.11.0-pl - Allows a nav tab to ignore Commerce JS and link to another page.
            $items['products']['class'] = 'commerce-ignore-jsnav';
        }

        $event->setItems($items);
    }
}