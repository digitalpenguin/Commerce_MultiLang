<?php
/* @var modX $modx */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_UPGRADE:
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;

            $modelPath = $modx->getOption('core_path').'components/commerce_multilang/model/';
            $modx->addPackage('commerce_multilang', $modelPath);
            $manager = $modx->getManager();
            $manager->createObjectContainer('CMLProductLanguage');
            $manager->createObjectContainer('CMLProductImage');
            $manager->createObjectContainer('CMLProductImageLanguage');


            $modx->log(modX::LOG_LEVEL_INFO, 'Loading/updating available modules...');
            $corePath = $modx->getOption('commerce.core_path', null, $modx->getOption('core_path') . 'components/commerce/');
            $commerce = $modx->getService('commerce', 'Commerce', $corePath . 'model/commerce/' , ['isSetup' => true]);
            if ($commerce instanceof Commerce) {
                if(!$commerce->adapter->getCount('comModule',array('name'=>'MultiLang'))) {
                    $module = $commerce->adapter->newObject('comModule');
                    $module->set('class_key','comModule');
                    $module->set('enabled_in_test',0);
                    $module->set('enabled_in_live',0);
                    $module->set('name','MultiLang');
                    $module->set('author','Murray Wood - Digital Penguin');
                    $module->set('class_name', 'DigitalPenguin\Commerce_MultiLang\Modules\MultiLang');
                    $module->set('class_path','{core_path}components/commerce_multilang/src/Modules/MultiLang.php');
                    if($module->save()) {
                        $modx->log(modX::LOG_LEVEL_INFO, 'Module added to Commerce successfully.');
                    } else {
                        $modx->log(modX::LOG_LEVEL_ERROR, 'Unable to save new object to comModule');
                    }
                } else {
                    $modx->log(modX::LOG_LEVEL_INFO, 'Skipping loading of module. Already exists.');
                }
            }
            else {
                $modx->log(modX::LOG_LEVEL_ERROR, 'Could not load Commerce service to load module');
            }
            break;
    }
}
return true;
