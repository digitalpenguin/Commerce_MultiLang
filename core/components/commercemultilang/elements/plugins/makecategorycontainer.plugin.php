<?php
/**
 * Event: onBeforeDocFormSave
 * Checks to see if a resource being saved is below commercemultilang.category_root_id
 * If so, forces the resource to be a container.
 */

$contextSetting = $modx->getObject('modContextSetting',array(
    'context_key'   =>  $resource->get('context_key'),
    'key'           =>  'commercemultilang.category_root_id'
));
$storeId = $contextSetting->get('value');
$parentIds = $modx->getParentIds($id,100,array('context'=>$resource->get('context_key')));
if(in_array($storeId,$parentIds)) {
    $resource->set('isfolder',1);
}
//TODO: make exceptions for cart, checkout etc based on context settings.