<?php
/**
 * @package commercemultilang
 */
$xpdo_meta_map['CommerceMultiLangProductType']= array (
  'package' => 'commercemultilang',
  'version' => '1.1',
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
    'position' => NULL,
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
    'position' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ),
  ),
  'indexes' => 
  array (
    'position' => 
    array (
      'alias' => 'position',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'position' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
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
