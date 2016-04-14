<?php

/**
 * BBBx
 *
 * Copyright 2016 by goldsky <goldsky@virtudraft.com>
 *
 * This file is part of BBBx, a BigBlueButton and MODX integration add on.
 *
 * BBBx is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation version 3,
 *
 * BBBx is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * BBBx; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package bbbx
 * @subpackage build
 */
$settings['bbbx.core_path'] = $modx->newObject('modSystemSetting');
$settings['bbbx.core_path']->fromArray(array(
    'key'       => 'bbbx.core_path',
    'value'     => '{core_path}components/bbbx/',
    'xtype'     => 'textfield',
    'namespace' => 'bbbx',
    'area'      => 'URL',
        ), '', true, true);

$settings['bbbx.assets_url'] = $modx->newObject('modSystemSetting');
$settings['bbbx.assets_url']->fromArray(array(
    'key'       => 'bbbx.assets_url',
    'value'     => '{assets_url}components/bbbx/',
    'xtype'     => 'textfield',
    'namespace' => 'bbbx',
    'area'      => 'URL',
        ), '', true, true);

$settings['bbbx.server_url'] = $modx->newObject('modSystemSetting');
$settings['bbbx.server_url']->fromArray(array(
    'key'       => 'bbbx.server_url',
    'value'     => 'http://test-install.blindsidenetworks.com/bigbluebutton/',
    'xtype'     => 'textfield',
    'namespace' => 'bbbx',
    'area'      => 'system',
        ), '', true, true);

$settings['bbbx.shared_secret'] = $modx->newObject('modSystemSetting');
$settings['bbbx.shared_secret']->fromArray(array(
    'key'       => 'bbbx.shared_secret',
    'value'     => '8cd8ef52e8e101574e400365b55e11a6',
    'xtype'     => 'textfield',
    'namespace' => 'bbbx',
    'area'      => 'system',
        ), '', true, true);

$settings['bbbx.recording_default'] = $modx->newObject('modSystemSetting');
$settings['bbbx.recording_default']->fromArray(array(
    'key'       => 'bbbx.recording_default',
    'value'     => 1,
    'xtype'     => 'combo-boolean',
    'namespace' => 'bbbx',
    'area'      => 'Recording',
        ), '', true, true);

$settings['bbbx.recording_editable'] = $modx->newObject('modSystemSetting');
$settings['bbbx.recording_editable']->fromArray(array(
    'key'       => 'bbbx.recording_editable',
    'value'     => 1,
    'xtype'     => 'combo-boolean',
    'namespace' => 'bbbx',
    'area'      => 'Recording',
        ), '', true, true);

$settings['bbbx.recording_icons_enabled'] = $modx->newObject('modSystemSetting');
$settings['bbbx.recording_icons_enabled']->fromArray(array(
    'key'       => 'bbbx.recording_icons_enabled',
    'value'     => 1,
    'xtype'     => 'combo-boolean',
    'namespace' => 'bbbx',
    'area'      => 'Recording',
        ), '', true, true);

$settings['bbbx.recordingtagging_default'] = $modx->newObject('modSystemSetting');
$settings['bbbx.recordingtagging_default']->fromArray(array(
    'key'       => 'bbbx.recordingtagging_default',
    'value'     => 0,
    'xtype'     => 'combo-boolean',
    'namespace' => 'bbbx',
    'area'      => 'Recording',
        ), '', true, true);

$settings['bbbx.recordingtagging_editable'] = $modx->newObject('modSystemSetting');
$settings['bbbx.recordingtagging_editable']->fromArray(array(
    'key'       => 'bbbx.recordingtagging_editable',
    'value'     => 1,
    'xtype'     => 'combo-boolean',
    'namespace' => 'bbbx',
    'area'      => 'Recording',
        ), '', true, true);

$settings['bbbx.recordingready_enabled'] = $modx->newObject('modSystemSetting');
$settings['bbbx.recordingready_enabled']->fromArray(array(
    'key'       => 'bbbx.recordingready_enabled',
    'value'     => 0,
    'xtype'     => 'combo-boolean',
    'namespace' => 'bbbx',
    'area'      => 'Recording',
        ), '', true, true);

$settings['bbbx.recording_ui_html_default'] = $modx->newObject('modSystemSetting');
$settings['bbbx.recording_ui_html_default']->fromArray(array(
    'key'       => 'bbbx.recording_ui_html_default',
    'value'     => 0,
    'xtype'     => 'combo-boolean',
    'namespace' => 'bbbx',
    'area'      => 'Recording',
        ), '', true, true);

$settings['bbbx.recording_ui_html_editable'] = $modx->newObject('modSystemSetting');
$settings['bbbx.recording_ui_html_editable']->fromArray(array(
    'key'       => 'bbbx.recording_ui_html_editable',
    'value'     => 0,
    'xtype'     => 'combo-boolean',
    'namespace' => 'bbbx',
    'area'      => 'Recording',
        ), '', true, true);

$settings['bbbx.waitformoderator_default'] = $modx->newObject('modSystemSetting');
$settings['bbbx.waitformoderator_default']->fromArray(array(
    'key'       => 'bbbx.waitformoderator_default',
    'value'     => 0,
    'xtype'     => 'combo-boolean',
    'namespace' => 'bbbx',
    'area'      => 'User',
        ), '', true, true);

$settings['bbbx.waitformoderator_editable'] = $modx->newObject('modSystemSetting');
$settings['bbbx.waitformoderator_editable']->fromArray(array(
    'key'       => 'bbbx.waitformoderator_editable',
    'value'     => 1,
    'xtype'     => 'combo-boolean',
    'namespace' => 'bbbx',
    'area'      => 'User',
        ), '', true, true);

$settings['bbbx.waitformoderator_ping_interval'] = $modx->newObject('modSystemSetting');
$settings['bbbx.waitformoderator_ping_interval']->fromArray(array(
    'key'       => 'bbbx.waitformoderator_ping_interval',
    'value'     => 10,
    'xtype'     => 'textfield',
    'namespace' => 'bbbx',
    'area'      => 'User',
        ), '', true, true);

$settings['bbbx.waitformoderator_cache_ttl'] = $modx->newObject('modSystemSetting');
$settings['bbbx.waitformoderator_cache_ttl']->fromArray(array(
    'key'       => 'bbbx.waitformoderator_cache_ttl',
    'value'     => 60,
    'xtype'     => 'textfield',
    'namespace' => 'bbbx',
    'area'      => 'User',
        ), '', true, true);

$settings['bbbx.userlimit_default'] = $modx->newObject('modSystemSetting');
$settings['bbbx.userlimit_default']->fromArray(array(
    'key'       => 'bbbx.userlimit_default',
    'value'     => 0,
    'xtype'     => 'textfield',
    'namespace' => 'bbbx',
    'area'      => 'User',
        ), '', true, true);

$settings['bbbx.userlimit_editable'] = $modx->newObject('modSystemSetting');
$settings['bbbx.userlimit_editable']->fromArray(array(
    'key'       => 'bbbx.userlimit_editable',
    'value'     => 0,
    'xtype'     => 'combo-boolean',
    'namespace' => 'bbbx',
    'area'      => 'User',
        ), '', true, true);

$settings['bbbx.moderator_default'] = $modx->newObject('modSystemSetting');
$settings['bbbx.moderator_default']->fromArray(array(
    'key'       => 'bbbx.moderator_default',
    'value'     => 'Administrator',
    'xtype'     => 'textfield',
    'namespace' => 'bbbx',
    'area'      => 'User',
        ), '', true, true);

$settings['bbbx.voicebridge_editable'] = $modx->newObject('modSystemSetting');
$settings['bbbx.voicebridge_editable']->fromArray(array(
    'key'       => 'bbbx.voicebridge_editable',
    'value'     => 0,
    'xtype'     => 'combo-boolean',
    'namespace' => 'bbbx',
    'area'      => 'Voice Bridge',
        ), '', true, true);

$settings['bbbx.preuploadpresentation_enabled'] = $modx->newObject('modSystemSetting');
$settings['bbbx.preuploadpresentation_enabled']->fromArray(array(
    'key'       => 'bbbx.preuploadpresentation_enabled',
    'value'     => 1,
    'xtype'     => 'combo-boolean',
    'namespace' => 'bbbx',
    'area'      => 'file',
        ), '', true, true);

$settings['bbbx.sendnotifications_enabled'] = $modx->newObject('modSystemSetting');
$settings['bbbx.sendnotifications_enabled']->fromArray(array(
    'key'       => 'bbbx.sendnotifications_enabled',
    'value'     => 1,
    'xtype'     => 'combo-boolean',
    'namespace' => 'bbbx',
    'area'      => 'system',
        ), '', true, true);

return $settings;
