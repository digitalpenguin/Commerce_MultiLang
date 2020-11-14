<?php
/**
 * @package commerce_multilang
 */
$xpdo_meta_map['CMLProductVariationLanguage']= array (
  'package' => 'commerce_multilang',
  'version' => '1.1',
  'table' => 'commerce_multilang_product_variation_languages',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'variation_id' => NULL,
    'display_name' => '',
  ),
  'fieldMeta' => 
  array (
    'variation_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ),
    'display_name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '190',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
  'indexes' => 
  array (
    'variation_id' => 
    array (
      'alias' => 'variation_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'variation_id' => 
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
    'ProductVariation' => 
    array (
      'class' => 'CMLProductVariation',
      'local' => 'variation_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
