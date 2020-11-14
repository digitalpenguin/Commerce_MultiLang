<?php
/**
 * Event: onModxInit
 */

$cml = $modx->getService('commerce_multilang', 'CommerceMultiLang', $modx->getOption('commerce_multilang.core_path', null, $modx->getOption('core_path') . 'components/commerce_multilang/') . 'model/commerce_multilang/', $scriptProperties);
if (!($cml instanceof CommerceMultiLang))
    return '';
$xpdo = &$cml->modx;
if (!($xpdo instanceof modX))
    return '';

$fields = null;
$class = 'CommerceMultiLangFlatRow';
$tableName = $xpdo->getTableName($class);
$fieldsStmt = $xpdo->query('SHOW COLUMNS FROM ' . $xpdo->escape($tableName));


if ($fieldsStmt) {
    $fields = $fieldsStmt->fetchAll(PDO::FETCH_ASSOC);
    $xpdo->loadClass($class);

    if (is_array($fields)) {
        foreach($fields as $field) {
            //$xpdo->log(1,print_r($field,true));
            $fieldname = isset($field['Field']) ? $field['Field'] : '';

            if (!isset($xpdo->map[$class]['fieldMeta'][$fieldname])) {
                $xpdo->map[$class]['fields'][$fieldname] = 0;
                $xpdo->map[$class]['fieldMeta'][$fieldname] = array(
                    'dbtype' => 'text',
                    'phptype' => 'string',
                    'null' => false,
                    'default' => '',
                );
                //$xpdo->log(1,print_r($xpdo->map[$class],true));
                //$xpdo->log(1,print_r($xpdo->map[$class]['fields'],true));
                //$xpdo->log(1,print_r($xpdo->map[$class]['fieldMeta'],true));
            }
        }
    }
}