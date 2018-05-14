<?php
$menu = $modx->newObject('modMenu');
$menu->fromArray(array(
    'text' => 'commercemultilang',
    'parent' => 'components',
    'action' => 'home',
    'description' => 'commercemultilang.desc',
    'icon' => 'images/icons/plugin.gif',
    'menuindex' => 0,
    'params' => '',
    'handler' => '',
    'pemission' => '',
    'namespace' => 'commercemultilang',
), '', true, true);
return $menu;
