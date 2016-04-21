<?php
$xpdo_meta_map['bbbxMeetingsJoins']= array (
  'package' => 'bbbx',
  'version' => '1.1',
  'table' => 'meetings_joins',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'meeting_id' => NULL,
    'classkey' => NULL,
    'object_id' => 0,
  ),
  'fieldMeta' => 
  array (
    'meeting_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
    'classkey' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'object_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
  'aggregates' => 
  array (
    'Meetings' => 
    array (
      'class' => 'bbbxMeetings',
      'local' => 'meeting_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
