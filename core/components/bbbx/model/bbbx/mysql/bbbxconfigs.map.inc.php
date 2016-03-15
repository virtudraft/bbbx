<?php
$xpdo_meta_map['bbbxConfigs']= array (
  'package' => 'bbbx',
  'version' => '1.1',
  'table' => 'configs',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => NULL,
    'description' => NULL,
    'xml' => NULL,
    'applied_to' => NULL,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'xml' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'null' => true,
    ),
    'applied_to' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '30',
      'phptype' => 'string',
      'null' => true,
    ),
  ),
  'composites' => 
  array (
    'MeetingsConfigs' => 
    array (
      'class' => 'bbbxMeetingsConfigs',
      'local' => 'id',
      'foreign' => 'config_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
