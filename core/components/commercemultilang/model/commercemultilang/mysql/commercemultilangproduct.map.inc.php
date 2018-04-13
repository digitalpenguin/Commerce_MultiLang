<?php
/**
 * @package commercemultilang
 */
$xpdo_meta_map['CommerceMultiLangProduct']= array (
  'package' => 'commercemultilang',
  'version' => '0.1',
  'table' => 'commercemultilang_products',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'position' => NULL,
  ),
  'fieldMeta' => 
  array (
    'position' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ),
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
  ),
);
