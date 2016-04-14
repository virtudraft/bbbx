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
$settings = array();

$setting    = $modx->newObject('modSystemSetting');
$setting->fromArray(array(
    'key'       => 'bbbx.core_path',
    'value'     => '{core_path}components/bbbx/',
    'xtype'     => 'textfield',
    'namespace' => 'bbbx',
    'area'      => 'URL',
        ), '', true, true);
$settings[] = $setting;

$setting    = $modx->newObject('modSystemSetting');
$setting->fromArray(array(
    'key'       => 'bbbx.assets_url',
    'value'     => '{assets_url}components/bbbx/',
    'xtype'     => 'textfield',
    'namespace' => 'bbbx',
    'area'      => 'URL',
        ), '', true, true);
$settings[] = $setting;

$setting    = $modx->newObject('modSystemSetting');
$setting->fromArray(array(
    'key'       => 'bbbx.server_url',
    'value'     => 'http://test-install.blindsidenetworks.com/bigbluebutton/',
    'xtype'     => 'textfield',
    'namespace' => 'bbbx',
    'area'      => 'system',
        ), '', true, true);
$settings[] = $setting;

$setting    = $modx->newObject('modSystemSetting');
$setting->fromArray(array(
    'key'       => 'bbbx.shared_secret',
    'value'     => '8cd8ef52e8e101574e400365b55e11a6',
    'xtype'     => 'textfield',
    'namespace' => 'bbbx',
    'area'      => 'system',
        ), '', true, true);
$settings[] = $setting;

$setting    = $modx->newObject('modSystemSetting');
$setting->fromArray(array(
    'key'       => 'bbbx.waitformoderator_ping_interval',
    'value'     => 10,
    'xtype'     => 'textfield',
    'namespace' => 'bbbx',
    'area'      => 'User',
        ), '', true, true);
$settings[] = $setting;

$setting    = $modx->newObject('modSystemSetting');
$setting->fromArray(array(
    'key'       => 'bbbx.waitformoderator_cache_ttl',
    'value'     => 60,
    'xtype'     => 'textfield',
    'namespace' => 'bbbx',
    'area'      => 'User',
        ), '', true, true);
$settings[] = $setting;

$setting    = $modx->newObject('modSystemSetting');
$setting->fromArray(array(
    'key'       => 'bbbx.moderator_default',
    'value'     => 'Administrator',
    'xtype'     => 'textfield',
    'namespace' => 'bbbx',
    'area'      => 'User',
        ), '', true, true);
$settings[] = $setting;

return $settings;
