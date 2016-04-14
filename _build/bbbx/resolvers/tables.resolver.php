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
 * Resolve creating db tables
 *
 * @package bbbx
 * @subpackage build
 */
if ($modx = & $object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            if ($modx->getDebug()) {
                $modx->log(modX::LOG_LEVEL_WARN, 'resolver xPDOTransport::ACTION_INSTALL');
            }
            $modelPath = $modx->getOption('core_path') . 'components/bbbx/model/';
            if ($modx->addPackage('bbbx', $modelPath, $modx->config[modX::OPT_TABLE_PREFIX].'bbbx_')) {
                if ($modx->getDebug()) {
                    $modx->log(modX::LOG_LEVEL_WARN, 'package was added in resolver xPDOTransport::ACTION_INSTALL');
                }
                $manager = $modx->getManager();
                $manager->createObjectContainer('bbbxConfigs');
                $manager->createObjectContainer('bbbxMeetings');
                $manager->createObjectContainer('bbbxMeetingsConfigs');
                $manager->createObjectContainer('bbbxMeetingsContexts');
                $manager->createObjectContainer('bbbxMeetingsUsergroups');
                $manager->createObjectContainer('bbbxMeetingsUsers');
            }
            break;
        case xPDOTransport::ACTION_UPGRADE:
            break;
        case xPDOTransport::ACTION_UNINSTALL:
            if ($modx->getDebug()) {
                $modx->log(modX::LOG_LEVEL_WARN, 'resolver xPDOTransport::ACTION_UNINSTALL');
                $modelPath = $modx->getOption('core_path') . 'components/bbbx/model/';
                if ($modx->addPackage('bbbx', $modelPath, $modx->config[modX::OPT_TABLE_PREFIX].'bbbx_')) {
                    $modx->log(modX::LOG_LEVEL_WARN, 'package was added in resolver xPDOTransport::ACTION_UNINSTALL');
                }
            }
            break;
    }
}

return true;