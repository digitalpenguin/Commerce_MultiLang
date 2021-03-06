<?php
/**
 * @package commerce_multilang
 */
$xpdo_meta_map['CMLProduct']= array (
  'package' => 'commerce_multilang',
  'version' => '1.1',
  'extends' => 'comProduct',
  'inherit' => 'single',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
  ),
  'fieldMeta' => 
  array (
  ),
  'composites' => 
  array (
    'ProductLanguage' => 
    array (
      'class' => 'CMLProductLanguage',
      'local' => 'id',
      'foreign' => 'product_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'ProductImage' => 
    array (
      'class' => 'CMLProductImage',
      'local' => 'id',
      'foreign' => 'product_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
