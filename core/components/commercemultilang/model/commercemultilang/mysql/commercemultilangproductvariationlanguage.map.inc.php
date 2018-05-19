<?php
/**
 * @package commercemultilang
 */
$xpdo_meta_map['CommerceMultiLangProductVariationLanguage']= array (
  'package' => 'commercemultilang',
  'version' => '1.1',
  'table' => 'commercemultilang_product_variation_languages',
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
      'class' => 'CommerceMultiLangProductVariation',
      'local' => 'variation_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
