<?php
/**
 * @package commercemultilang
 */
$xpdo_meta_map['CommerceMultiLangProductType']= array (
  'package' => 'commercemultilang',
  'version' => '0.1',
  'table' => 'commercemultilang_product_types',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'name' => '',
    'description' => NULL,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '190',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
  ),
  'composites' => 
  array (
    'ProductVariation' => 
    array (
      'local' => 'id',
      'class' => 'CommerceMultiLangProductVariation',
      'foreign' => 'type_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
