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
$c->select(array(
    'bbbxMeetings.*'
));
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
// for getPage
$total = $modx->getCount('bbbxMeetings', $c);
$modx->setPlaceholder($scriptProperties['totalVar'], $total);
if (!empty($scriptProperties['limit']) || !empty($scriptProperties['offset'])) {
    $c->limit($scriptProperties['limit'], $scriptProperties['offset']);
}
$c->sortby($scriptProperties['sortBy'], $scriptProperties['sortDir']);
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
//$toArray = 1; // debug
foreach ($meetings as $meeting) {
    $meetingArray             = $meeting->toArray();
    // initiate meeting if it fits with the dates
    $isMeetingRunning         = $bbbx->initMeeting($meetingArray['meeting_id']);
    $meetingArray['join_url'] = '';
    if ($isMeetingRunning && !empty($ugs)) {
        if (in_array(1, $ugs)) {
            $meetingArray['join_url'] = $bbbx->getJoinMeetingURL($meetingArray['meeting_id'], $meetingArray['moderator_pw']);
        } else {
            $meetingUgs = $meeting->getMany('MeetingUsergroups');
            if ($meetingUgs) {
                $moderators = array();
                foreach ($meetingUgs as $meetingUg) {
                    $meetingUgArray = $meetingUg->toArray();
                    if ($meetingUgArray['enroll'] === 'moderator') {
                        $moderators[] = $meetingUgArray['usergroup_id'];
                    }
                }
                $isModerator = array_intersect($moderators, $ugs);
                if (!empty($isModerator)) {
                    $meetingArray['join_url'] = $bbbx->getJoinMeetingURL($meetingArray['meeting_id'], $meetingArray['moderator_pw']);
                } else {
                    $meetingArray['join_url'] = $bbbx->getJoinMeetingURL($meetingArray['meeting_id'], $meetingArray['attendee_pw']);
                }
            }
            $meetingUsers = $meeting->getMany('MeetingUsers');
            if ($meetingUsers) {
                $moderators = array();
                foreach ($meetingUsers as $meetingUser) {
                    $meetingUserArray = $meetingUser->toArray();
                    if ($meetingUserArray['enroll'] === 'moderator') {
                        $moderators[] = $meetingUserArray['user_id'];
                    }
                }
                $isModerator = array_intersect($moderators, $ugs);
                if (!empty($isModerator)) {
                    $meetingArray['join_url'] = $bbbx->getJoinMeetingURL($meetingArray['meeting_id'], $meetingArray['moderator_pw']);
                } else {
                    $meetingArray['join_url'] = $bbbx->getJoinMeetingURL($meetingArray['meeting_id'], $meetingArray['attendee_pw']);
                }
            }
            if (!$meetingUgs && !$meetingUsers) {
                $meetingArray['join_url'] = $bbbx->getJoinMeetingURL($meetingArray['meeting_id'], $meetingArray['attendee_pw']);
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

