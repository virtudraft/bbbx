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
$scriptProperties['limit']         = $modx->getOption('limit', $scriptProperties);
$scriptProperties['offset']        = $modx->getOption('offset', $scriptProperties);
$scriptProperties['totalVar']      = $modx->getOption('totalVar', $scriptProperties, 'total');
$scriptProperties['sortBy']        = $modx->getOption('sortBy', $scriptProperties, 'id');
$scriptProperties['sortDir']       = $modx->getOption('sortDir', $scriptProperties, 'desc');
$scriptProperties['allDates']      = $modx->getOption('allDates', $scriptProperties);
$scriptProperties['where']         = $modx->getOption('where', $scriptProperties);
$scriptProperties['tplItem']       = $modx->getOption('tplItem', $scriptProperties, 'meeting/item');
$scriptProperties['tplWrapper']    = $modx->getOption('tplWrapper', $scriptProperties, 'meeting/wrapper');
$scriptProperties['phsPrefix']     = $modx->getOption('phsPrefix', $scriptProperties, 'bbbx.meeting.');
$scriptProperties['itemSeparator'] = $modx->getOption('itemSeparator', $scriptProperties, "\n");
$scriptProperties['contextKey']    = $modx->getOption('contextKey', $scriptProperties, $modx->context->get('key'));

$defaultCorePath = $modx->getOption('core_path').'components/bbbx/';
$corePath        = $modx->getOption('bbbx.core_path', null, $defaultCorePath);
$bbbx            = $modx->getService('bbbx', 'BBBx', $corePath.'model/', $scriptProperties);

if (!($bbbx instanceof BBBx)) {
    return;
}

$c = $modx->newQuery('bbbxMeetings');
$c->select(array(
    'bbbxMeetings.*'
));
$c->leftJoin('bbbxMeetingsContexts', 'MeetingsContexts', 'MeetingsContexts.meeting_id = bbbxMeetings.id');
if (!empty($scriptProperties['contextKey'])) {
    $c->where(array(
        'MeetingsContexts.context_key' => $scriptProperties['contextKey']
    ));
}
if (empty($scriptProperties['allDates'])) {
    $time = time();
    $c->where(array(
        'started_on:<=' => $time,
        'ended_on:>='   => $time,
    ));
}
if (!empty($scriptProperties['where'])) {
    $c->where(json_decode($scriptProperties['where'], true));
}
// for getPage
$total = $modx->getCount('bbbxMeetings', $c);
$modx->setPlaceholder($scriptProperties['totalVar'], $total);
if (!empty($scriptProperties['limit']) || !empty($scriptProperties['offset'])) {
    $c->limit($scriptProperties['limit'], $scriptProperties['offset']);
}
$c->sortby($scriptProperties['sortBy'], $scriptProperties['sortDir']);
$meetings = $modx->getCollection('bbbxMeetings', $c);
if (!$meetings) {
    $modx->log(modX::LOG_LEVEL_ERROR, __LINE__.': [bbbx.getMeetings] Unable to get meetings');
    return;
}
$outputArray     = array();
$isAuthenticated = $modx->user->isAuthenticated('mgr');
if (!$isAuthenticated) {
    $isAuthenticated = $modx->user->isAuthenticated($modx->context->get('key'));
}
$ugs = array();
if ($isAuthenticated) {
    $ugs = $modx->user->getUserGroups();
}
//$toArray = 1; // debug
foreach ($meetings as $meeting) {
    $meetingArray = $meeting->toArray();
    $permission   = $bbbx->getUserPermissionToMeeting($meetingArray['meeting_id'], $scriptProperties['contextKey']);
    // initiate meeting if it fits with the dates
    $isRunning    = $bbbx->initMeeting($meetingArray['meeting_id']);

    $meetingArray['is_running'] = '';
    $meetingArray['join_url']   = '';
    if ($isRunning) {
        $meetingArray['is_running'] = 1;
        if (!empty($ugs)) {
            if (in_array(1, $ugs)) {
                $meetingArray['join_url'] = $bbbx->getJoinMeetingURL($meetingArray['meeting_id'], $meetingArray['moderator_pw']);
            } else {
                if ($permission === 'moderator') {
                    $meetingArray['join_url'] = $bbbx->getJoinMeetingURL($meetingArray['meeting_id'], $meetingArray['moderator_pw']);
                } else if ($permission === 'viewer') {
                    $meetingArray['join_url'] = $bbbx->getJoinMeetingURL($meetingArray['meeting_id'], $meetingArray['attendee_pw']);
                }
            }
        }
    }
    $phs = $bbbx->setPlaceholders($meetingArray, $scriptProperties['phsPrefix']);
    if (!empty($toArray)) {
        $outputArray[] = $phs;
    } else {
        $outputArray[] = $bbbx->processElementTags($bbbx->parseTpl($scriptProperties['tplItem'], $phs));
    }
}
if (!empty($toArray)) {
    $wrapper = array(
        $scriptProperties['phsPrefix'].'items' => $outputArray
    );
    $output  = '<pre>'.print_r($wrapper, 1).'</pre>';
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

