<?php

/*
 * use a aftersave hook that adds the missing colums based on field find in migxdb cmp
 */

$pkgman = $modx->migx->loadPackageManager();
$pkgman->xpdo2 = &$xpdo;
$pkgman->manager = $xpdo->getManager();
$modfields = array();

$class = 'productdbFlatRow';
$xpdo->loadClass($class);

if (is_array($fields)) {
    foreach($fields as $field) {
        $fieldname = strtolower(str_replace(' ','_',$field));

        $xpdo->map[$class]['fields'][$fieldname] = 0;
        $xpdo->map[$class]['fieldMeta'][$fieldname] = array(
            'dbtype' => 'text',
            'phptype' => 'string',
            'null' => false,
            'default' => '',
        );
    }
}

$pkgman->addMissingFields($class, $modfields);