<?php
/* @var modX $modx */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_UPGRADE:
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;

            $modelPath = $modx->getOption('core_path').'components/commercemultilang/model/';
            $modx->addPackage('commercemultilang', $modelPath);
            $manager = $modx->getManager();
            $manager->createObjectContainer('CommerceMultiLangProductData');
            $manager->createObjectContainer('CommerceMultiLangProductLanguage');
            $manager->createObjectContainer('CommerceMultiLangProductImage');
            $manager->createObjectContainer('CommerceMultiLangProductImageLanguage');
            $manager->createObjectContainer('CommerceMultiLangProductType');
            $manager->createObjectContainer('CommerceMultiLangProductVariation');
            $manager->createObjectContainer('CommerceMultiLangProductVariationLanguage');
            $manager->createObjectContainer('CommerceMultiLangAssignedVariation');
            $manager->createObjectContainer('CommerceMultiLangAssignedCategory');


        $modx->log(modX::LOG_LEVEL_INFO, 'Loading/updating available modules...');
            $corePath = $modx->getOption('commerce.core_path', null, $modx->getOption('core_path') . 'components/commerce/');
            $commerce = $modx->getService('commerce', 'Commerce', $corePath . 'model/commerce/' , ['isSetup' => true]);
            if ($commerce instanceof Commerce) {
                if(!$commerce->adapter->getCount('comModule',array('name'=>'CommerceMultiLang'))) {
                    $module = $commerce->adapter->newObject('comModule');
                    $module->set('class_key','comModule');
                    $module->set('enabled_in_test',0);
                    $module->set('enabled_in_live',0);
                    $module->set('name','CommerceMultiLang');
                    $module->set('author','Murray Wood - Digital Penguin');
                    $module->set('class_name','DigitalPenguin\CommerceMultiLang\Modules\CommerceMultiLang');
                    $module->set('class_path','{core_path}components/commercemultilang/src/Modules/CommerceMultiLang.php');
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
