<?php
$xpdo_meta_map['bbbxMeetings']= array (
  'package' => 'bbbx',
  'version' => '1.1',
  'table' => 'meetings',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => NULL,
    'meeting_id' => NULL,
    'description' => NULL,
    'attendee_pw' => NULL,
    'moderator_pw' => NULL,
    'welcome' => NULL,
    'dial_number' => NULL,
    'voice_bridge' => NULL,
    'web_voice' => NULL,
    'logout_url' => NULL,
    'is_moderator_first' => 0,
    'max_participants' => 0,
    'started_on' => NULL,
    'ended_on' => NULL,
    'is_forced_to_end' => 0,
    'is_canceled' => 0,
    'is_recorded' => 0,
    'duration' => 0,
    'meta' => NULL,
    'moderator_only_message' => NULL,
    'auto_start_recording' => 0,
    'allow_start_stop_recording' => 0,
    'document_url' => NULL,
    'created_on' => NULL,
    'created_by' => NULL,
    'edited_on' => NULL,
    'edited_by' => NULL,
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
    'meeting_id' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ),
    'description' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'null' => true,
    ),
    'attendee_pw' => 
    array (
      'dbtype' => 'varbinary',
      'precision' => '255',
      'phptype' => 'binary',
      'null' => true,
    ),
    'moderator_pw' => 
    array (
      'dbtype' => 'varbinary',
      'precision' => '255',
      'phptype' => 'binary',
      'null' => true,
    ),
    'welcome' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'dial_number' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ),
    'voice_bridge' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
    ),
    'web_voice' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ),
    'logout_url' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'is_moderator_first' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'max_participants' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'started_on' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
    ),
    'ended_on' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
    ),
    'is_forced_to_end' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'is_canceled' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'is_recorded' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'duration' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'meta' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'null' => true,
    ),
    'moderator_only_message' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'auto_start_recording' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'allow_start_stop_recording' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'document_url' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'created_on' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ),
    'created_by' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ),
    'edited_on' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
    ),
    'edited_by' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
    ),
  ),
  'composites' => 
  array (
    'MeetingsConfigs' => 
    array (
      'class' => 'bbbxMeetingsConfigs',
      'local' => 'meeting_id',
      'foreign' => 'meeting_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'MeetingsContexts' => 
    array (
      'class' => 'bbbxMeetingsContexts',
      'local' => 'id',
      'foreign' => 'meeting_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'MeetingsUsers' => 
    array (
      'class' => 'bbbxMeetingsUsers',
      'local' => 'id',
      'foreign' => 'meeting_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'MeetingsUsergroups' => 
    array (
      'class' => 'bbbxMeetingsUsergroups',
      'local' => 'id',
      'foreign' => 'meeting_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'MeetingsJoins' => 
    array (
      'class' => 'bbbxMeetingsJoins',
      'local' => 'id',
      'foreign' => 'meeting_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'NotifyUsergroups' => 
    array (
      'class' => 'bbbxNotifyUsergroups',
      'local' => 'id',
      'foreign' => 'meeting_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'NotifyUsers' => 
    array (
      'class' => 'bbbxNotifyUsers',
      'local' => 'id',
      'foreign' => 'meeting_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
