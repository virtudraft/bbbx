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
 * @subpackage snippet
 */
$scriptProperties['meetingId']     = $modx->getOption('meetingId', $scriptProperties, '');
$scriptProperties['tplItem']       = $modx->getOption('tplItem', $scriptProperties, 'recording/item');
$scriptProperties['tplWrapper']    = $modx->getOption('tplWrapper', $scriptProperties, 'recording/wrapper');
$scriptProperties['phsPrefix']     = $modx->getOption('phsPrefix', $scriptProperties, 'bbbx.recording.');
$scriptProperties['itemSeparator'] = $modx->getOption('itemSeparator', $scriptProperties, "\n");

$defaultCorePath = $modx->getOption('core_path').'components/bbbx/';
$corePath        = $modx->getOption('bbbx.core_path', null, $defaultCorePath);
$bbbx            = $modx->getService('bbbx', 'BBBx', $corePath.'model/', $scriptProperties);

if (!($bbbx instanceof BBBx)) {
    return;
}

$recordings = $bbbx->getRecordings($scriptProperties['meetingId']);
if (!$recordings) {
    return;
}
//$toArray = 1; // debug
foreach ($recordings as $recording) {
    if ($recording['published'] !== 'true' || $recording['state'] !== 'published') {
        continue;
    }
    $phs = $bbbx->setPlaceholders($recording, $scriptProperties['phsPrefix']);
    if (!empty($toArray)) {
        $outputArray[] = $phs;
    } else {
        $outputArray[] = $bbbx->processElementTags($bbbx->parseTpl($scriptProperties['tplItem'], $phs));
    }
}
if (!empty($toArray)) {
    $wrapper = array(
        $scriptProperties['phsPrefix'] . 'items' => $outputArray
    );
    $output  = '<pre>' . print_r($wrapper, 1) . '</pre>';
} else {
    $wrapper = array(
        'items' => @implode($scriptProperties['itemSeparator'], $outputArray)
    );
    $phs     = $bbbx->setPlaceholders($wrapper, $scriptProperties['phsPrefix']);
    $output  = $bbbx->processElementTags($bbbx->parseTpl($scriptProperties['tplWrapper'], $phs));
}
if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder, $output);
    return;
}

return $output;

