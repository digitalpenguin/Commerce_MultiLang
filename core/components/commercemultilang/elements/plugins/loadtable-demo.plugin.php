<?php


/*
<object class="productdbFlatRow" table="productdb_flat_rows" extends="xPDOSimpleObject">
    <field key="product_id" dbtype="int" precision="10" attributes="unsigned" phptype="integer" null="false" index="index"/>
    <aggregate alias="Product" class="productdbRow" local="product_id" foreign="id" cardinality="one" owner="local" />
</object>

Event: onModxInit
 */

$migx = $modx->getService('migx', 'Migx', $modx->getOption('migx.core_path', null, $modx->getOption('core_path') . 'components/migx/') . 'model/migx/', $scriptProperties);
if (!($migx instanceof Migx))
    return '';
$properties = array();
$properties['packageName'] = 'productdb';
$xpdo = $migx->getXpdoInstanceAndAddPackage($properties);

$fields = null;
$class = 'productdbFlatRow';
$tableName = $xpdo->getTableName($class);

$fieldsStmt = $xpdo->query('SHOW COLUMNS FROM ' . $xpdo->escape($tableName));
if ($fieldsStmt) {

    $fields = $fieldsStmt->fetchAll(PDO::FETCH_ASSOC);

    $xpdo->loadClass($class);

    if (is_array($fields)) {
        foreach($fields as $field) {
            $fieldname = isset($field['Field']) ? $field['Field'] : '';

            if (!isset($xpdo->map[$class]['fieldMeta'][$fieldname])) {
                $xpdo->map[$class]['fields'][$fieldname] = 0;
                $xpdo->map[$class]['fieldMeta'][$fieldname] = array(
                    'dbtype' => 'text',
                    'phptype' => 'string',
                    'null' => false,
                    'default' => '',
                );
            }
        }
    }
}