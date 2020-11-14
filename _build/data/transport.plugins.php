<?php

$plugins = array();
$plugins[0] = $modx->newObject('modPlugin');
$plugins[0]->set('id',1);
$plugins[0]->set('name','cml_redirect_to_product');
$plugins[0]->set('description','Redirects to a multi-lingual product.');
$plugins[0]->set('plugincode', getSnippetContent($sources['plugins'] . 'redirecttoproduct.plugin.php'));
$plugins[0]->set('category', 0);
$events[0] = $modx->newObject('modPluginEvent');
$events[0]->fromArray(array(
    'event' => 'onPageNotFound',
    'priority' => 0,
    'propertyset' => 0,
),'',true,true);
if (is_array($events) && !empty($events)) {
    $plugins[0]->addMany($events);
    $modx->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($events).' onPageNotFound event for redirectToProduct plugin for MultiLang.'); flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find onPageNotFound event for redirectToProduct plugin!');
}
unset($events);

$plugins[1] = $modx->newObject('modPlugin');
$plugins[1]->set('id',1);
$plugins[1]->set('name','makeCategoryContainer');
$plugins[1]->set('description','Makes a resource a container if a descendant of the category root.');
$plugins[1]->set('plugincode', getSnippetContent($sources['plugins'] . 'makecategorycontainer.plugin.php'));
$plugins[1]->set('category', 0);
$events[0] = $modx->newObject('modPluginEvent');
$events[0]->fromArray(array(
    'event' => 'onBeforeDocFormSave',
    'priority' => 0,
    'propertyset' => 0,
),'',true,true);
if (is_array($events) && !empty($events)) {
    $plugins[1]->addMany($events);
    $modx->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($events).' onBeforeDocFormSave event for makeCategoryContainer plugin for MultiLang.'); flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find onBeforeDocFormSave event for makeCategoryContainer plugin!');
}

unset($events);
return $plugins;