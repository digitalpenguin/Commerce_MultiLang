<?php
/**
 * @package commercemultilang
 */
$xpdo_meta_map['CommerceMultiLangProductData']= array (
  'package' => 'commercemultilang',
  'version' => '0.1',
  'table' => 'commercemultilang_product_data',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'product_id' => NULL,
    'alias' => '',
    'related_product_ids' => NULL,
  ),
  'fieldMeta' => 
  array (
    'product_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
    'alias' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '190',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'related_product_ids' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
  ),
  'aggregates' => 
  array (
    'Product' => 
    array (
      'class' => 'CommerceMultiLangProduct',
      'local' => 'product_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
