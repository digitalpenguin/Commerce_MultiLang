<?php
$menu = $modx->newObject('modMenu');
$menu->fromArray(array(
    'text' => 'commerce_multilang',
    'parent' => 'components',
    'action' => 'home',
    'description' => 'commerce_multilang.desc',
    'icon' => 'images/icons/plugin.gif',
    'menuindex' => 0,
    'params' => '',
    'handler' => '',
    'pemission' => '',
    'namespace' => 'commerce_multilang',
), '', true, true);
return $menu;
