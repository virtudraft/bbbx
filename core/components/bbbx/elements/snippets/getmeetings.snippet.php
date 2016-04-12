<?php

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

