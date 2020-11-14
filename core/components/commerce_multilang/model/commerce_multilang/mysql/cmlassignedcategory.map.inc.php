<?php
/**
 * @package commerce_multilang
 */
$xpdo_meta_map['CMLAssignedCategory']= array (
  'package' => 'commerce_multilang',
  'version' => '1.1',
  'table' => 'commerce_multilang_assigned_product_categories',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'category_id' => NULL,
    'product_id' => NULL,
    'name' => '',
    'lang_key' => NULL,
    'position' => NULL,
  ),
  'fieldMeta' => 
  array (
    'category_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ),
    'product_id' => 
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
    'lang_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '10',
      'phptype' => 'string',
      'null' => false,
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
    'product_id' => 
    array (
      'alias' => 'product_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'product_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'lang_key' => 
    array (
      'alias' => 'lang_key',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'lang_key' => 
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
  'aggregates' => 
  array (
    'Category' => 
    array (
      'class' => 'modResource',
      'local' => 'category_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Product' => 
    array (
      'class' => 'CMLProduct',
      'local' => 'product_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
