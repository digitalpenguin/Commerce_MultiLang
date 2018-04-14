<?php
/**
 * @package commercemultilang
 */
$xpdo_meta_map['CommerceMultiLangProduct']= array (
  'package' => 'commercemultilang',
  'version' => '0.1',
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
    'Flat Row' => 
    array (
      'class' => 'CommerceMultiLangFlatRow',
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
  ),
);
