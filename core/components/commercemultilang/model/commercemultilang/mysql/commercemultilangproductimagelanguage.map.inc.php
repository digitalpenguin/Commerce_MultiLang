<?php
/**
 * @package commercemultilang
 */
$xpdo_meta_map['CommerceMultiLangProductImageLanguage']= array (
  'package' => 'commercemultilang',
  'version' => '1.1',
  'table' => 'commercemultilang_product_image_languages',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'product_image_id' => NULL,
    'lang_key' => NULL,
    'title' => NULL,
    'alt' => NULL,
    'image' => '',
    'description' => NULL,
  ),
  'fieldMeta' => 
  array (
    'product_image_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ),
    'lang_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '10',
      'phptype' => 'string',
      'null' => false,
    ),
    'title' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '190',
      'phptype' => 'string',
      'null' => true,
    ),
    'alt' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '190',
      'phptype' => 'string',
      'null' => true,
    ),
    'image' => 
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
  'indexes' => 
  array (
    'product_image_id' => 
    array (
      'alias' => 'product_image_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'product_image_id' => 
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
  ),
  'aggregates' => 
  array (
    'ProductImage' => 
    array (
      'class' => 'CommerceMultiLangProductImage',
      'local' => 'product_image_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
