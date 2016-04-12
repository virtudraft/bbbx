<?php
$xpdo_meta_map['bbbxMeetingContexts']= array (
  'package' => 'bbbx',
  'version' => '1.1',
  'table' => 'meeting_contexts',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'meeting_id' => NULL,
    'context_key' => NULL,
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
    'context_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
  'aggregates' => 
  array (
    'Meeting' => 
    array (
      'class' => 'bbbxMeetings',
      'local' => 'meeting_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
