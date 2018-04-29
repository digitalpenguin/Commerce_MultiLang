<?php
/**
 * @package commercemultilang
 */
$xpdo_meta_map['CommerceMultiLangProductVariation']= array (
  'package' => 'commercemultilang',
  'version' => '0.1',
  'table' => 'commercemultilang_product_variations',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'type_id' => NULL,
    'name' => '',
    'lang_key' => NULL,
    'value' => NULL,
  ),
  'fieldMeta' => 
  array (
    'type_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '190',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'lang_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '10',
      'phptype' => 'string',
      'null' => false,
      'index' => 'index',
    ),
    'value' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
  ),
  'aggregates' => 
  array (
    'ProductType' => 
    array (
      'class' => 'CommerceMultiLangProductType',
      'local' => 'product_type_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
