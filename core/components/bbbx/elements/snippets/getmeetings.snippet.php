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

$scriptProperties['allDates']      = $modx->getOption('allDates', $scriptProperties);
$scriptProperties['tplItem']       = $modx->getOption('tplItem', $scriptProperties, 'meeting/item');
$scriptProperties['tplWrapper']    = $modx->getOption('tplWrapper', $scriptProperties, 'meeting/wrapper');
$scriptProperties['phsPrefix']     = $modx->getOption('phsPrefix', $scriptProperties, 'bbbx.meeting.');
$scriptProperties['itemSeparator'] = $modx->getOption('itemSeparator', $scriptProperties, "\n");

$defaultCorePath = $modx->getOption('core_path').'components/bbbx/';
$corePath        = $modx->getOption('bbbx.core_path', null, $defaultCorePath);
$bbbx            = $modx->getService('bbbx', 'BBBx', $corePath.'model/', $scriptProperties);

if (!($bbbx instanceof BBBx)) {
    return;
}

$c = $modx->newQuery('bbbxMeetings');
$c->leftJoin('bbbxMeetingContexts', 'MeetingContexts', 'MeetingContexts.meeting_id = bbbxMeetings.id');
$c->where(array(
    'MeetingContexts.context_key' => $modx->context->get('key')
));
if (empty($scriptProperties['allDates'])) {
    $time = time();
    $c->where(array(
        'started_on:<=' => $time,
        'ended_on:>='   => $time,
    ));
}
$meetings = $modx->getCollection('bbbxMeetings', $c);
if (!$meetings) {
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
foreach ($meetings as $meeting) {
    $meetingArray             = $meeting->toArray();
    // initiate meeting if it fits with the dates
    $isMeetingRunning         = $bbbx->initMeeting($meetingArray['meeting_id']);
    $meetingArray['join_url'] = '';
    if ($isMeetingRunning && !empty($ugs)) {
        if (in_array(1, $ugs)) {
            $meetingArray['join_url'] = $bbbx->getJoinMeetingURL($meetingArray['meeting_id'], $meetingArray['moderator_pw']);
        } else {
            $meetingUgs = $meeting->getMany('bbbxMeetingUsergroups');
            if ($meetingUgs) {
                foreach ($meetingUgs as $meetingUg) {
                    $meetingUgArray = $meetingUg->toArray();
                    if (!in_array($meetingUgArray['usergroup_id'], $ugs)) {
                        continue;
                    }
                    if ($meetingUgArray['enroll'] === 'moderator') {
                        $meetingArray['join_url'] = $bbbx->getJoinMeetingURL($meetingArray['meeting_id'], $meetingArray['moderator_pw']);
                    } else {
                        if (!empty($meetingArray['join_url'])) {
                            continue;
                        }
                        $meetingArray['join_url'] = $bbbx->getJoinMeetingURL($meetingArray['meeting_id'], $meetingArray['attendee_pw']);
                    }
                }
            }
        }
    }
    $phs           = $bbbx->setPlaceholders($meetingArray, $scriptProperties['phsPrefix']);
    $outputArray[] = $bbbx->processElementTags($bbbx->parseTpl($scriptProperties['tplItem'], $phs));
}
$wrapper = array(
    'items' => @implode($scriptProperties['itemSeparator'], $outputArray)
);
$phs     = $bbbx->setPlaceholders($wrapper, $scriptProperties['phsPrefix']);
$output  = $bbbx->processElementTags($bbbx->parseTpl($scriptProperties['tplWrapper'], $phs));
if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder, $output);
    return;
}

return $output;

