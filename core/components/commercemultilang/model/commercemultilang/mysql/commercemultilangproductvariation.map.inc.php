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
  ),
  'composites' => 
  array (
    'AssignedVariation' => 
    array (
      'class' => 'CommerceMultiLangAssignedVariation',
      'local' => 'id',
      'foreign' => 'variation_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'ProductType' => 
    array (
      'class' => 'CommerceMultiLangProductType',
      'local' => 'type_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
