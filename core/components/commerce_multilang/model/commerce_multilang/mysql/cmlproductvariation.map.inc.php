<?php
/**
 * @package commerce_multilang
 */
$xpdo_meta_map['CMLProductVariation']= array (
  'package' => 'commerce_multilang',
  'version' => '1.1',
  'table' => 'commerce_multilang_product_variations',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'type_id' => NULL,
    'name' => '',
    'display_name' => '',
    'description' => NULL,
    'position' => NULL,
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
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '190',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'display_name' => 
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
    'type_id' => 
    array (
      'alias' => 'type_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'type_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'name' => 
    array (
      'alias' => 'name',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'name' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
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
    'AssignedVariation' => 
    array (
      'class' => 'CMLAssignedVariation',
      'local' => 'id',
      'foreign' => 'variation_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'VariationLanguage' => 
    array (
      'class' => 'CMLProductVariationLanguage',
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
      'class' => 'CMLProductType',
      'local' => 'type_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
