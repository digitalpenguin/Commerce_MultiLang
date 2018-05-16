<?php
/**
 * @package commercemultilang
 */
$xpdo_meta_map['CommerceMultiLangProduct']= array (
  'package' => 'commercemultilang',
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
      'class' => 'CommerceMultiLangProductLanguage',
      'local' => 'id',
      'foreign' => 'product_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'ProductImage' => 
    array (
      'class' => 'CommerceMultiLangProductImage',
      'local' => 'id',
      'foreign' => 'product_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'ProductData' => 
    array (
      'local' => 'id',
      'class' => 'CommerceMultiLangProductData',
      'foreign' => 'product_id',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
    'AssignedVariation' => 
    array (
      'class' => 'CommerceMultiLangAssignedVariation',
      'local' => 'id',
      'foreign' => 'variation_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
