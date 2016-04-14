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
$action = $modx->newObject('modAction');
$action->fromArray(array(
    'id'          => 1,
    'namespace'   => 'bbbx',
    'parent'      => 0,
    'controller'  => 'index',
    'haslayout'   => true,
    'lang_topics' => 'bbbx:default',
    'assets'      => '',
        ), '', true, true);

$menu = $modx->newObject('modMenu');
$menu->fromArray(array(
    'text'        => 'bbbx',
    'parent'      => 'components',
    'description' => 'bbbx_desc',
    'icon'        => 'images/icons/plugin.gif',
    'menuindex'   => 0,
    'params'      => '',
    'handler'     => '',
        ), '', true, true);
$menu->addOne($action);
unset($menus);

return $menu;
