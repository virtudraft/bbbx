<?php
$xpdo_meta_map['bbbxMeetingsConfigs']= array (
  'package' => 'bbbx',
  'version' => '1.1',
  'table' => 'meetings_configs',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'meeting_id' => NULL,
    'config_id' => NULL,
    'config_token' => NULL,
  ),
  'fieldMeta' => 
  array (
    'meeting_id' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'config_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
    'config_token' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => true,
    ),
  ),
  'aggregates' => 
  array (
    'Meeting' => 
    array (
      'class' => 'bbbxMeetings',
      'local' => 'meeting_id',
      'foreign' => 'meeting_id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Config' => 
    array (
      'class' => 'bbbxConfigs',
      'local' => 'config_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
