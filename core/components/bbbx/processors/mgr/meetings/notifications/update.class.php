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
 * @subpackage processor
 */
class MeetingsNotificationsUpdateProcessor extends modProcessor
{

    public $languageTopics = array('bbbx:cmp');
    public $objectType     = 'bbbx.MeetingsNotificationsUpdate';

    public function initialize()
    {
        $meetingID = $this->getProperty('meeting_id', false);
        if (empty($meetingID)) {
            return $this->modx->lexicon('bbbx.meeting_err_ns_meetingID');
        }

        return true;
    }

    public function process()
    {
        $props   = $this->getProperties();
        $meeting = $this->modx->getObject('bbbxMeetings', array(
            'meeting_id' => $props['meeting_id']
        ));
        if (empty($meeting)) {
            return $this->failure($this->modx->lexicon('bbbx.meeting_err_ns'));
        }
        $meetingId = $meeting->get('id');

        /**
         * Usergroups
         */
        if (isset($props['usergroups']) && !empty($props['usergroups']) && is_array($props['usergroups'])) {
            // diff
            $exists = $this->modx->getCollection('bbbxNotifyUsergroups', array(
                'meeting_id' => $meetingId,
            ));
            if ($exists) {
                $diff = array();
                foreach ($exists as $exist) {
                    $ugId = $exist->get('usergroup_id');
                    if (in_array($ugId, $props['usergroups'])) {
                        continue;
                    }
                    $diff[] = $ugId;
                }
                if (!empty($diff)) {
                    $this->modx->removeCollection('bbbxNotifyUsergroups', array(
                        'meeting_id'      => $meetingId,
                        'usergroup_id:IN' => $diff,
                    ));
                }
            }
            // update
            foreach ($props['usergroups'] as $ugId) {
                $notify = $this->modx->getObject('bbbxNotifyUsergroups', array(
                    'meeting_id'   => $meetingId,
                    'usergroup_id' => $ugId,
                ));
                if (!$notify) {
                    $notify = $this->modx->newObject('bbbxNotifyUsergroups');
                    $notify->fromArray(array(
                        'meeting_id'   => $meetingId,
                        'usergroup_id' => $ugId,
                    ));
                    $notify->save();
                }
                if ($props['send_now']) {
                    $this->modx->bbbx->notifyUsergroup($meetingId, $ugId);
                }
            }
        }

        /**
         * Users
         */
        if (isset($props['users']) && !empty($props['users']) && is_array($props['users'])) {
            // diff
            $exists = $this->modx->getCollection('bbbxNotifyUsers', array(
                'meeting_id' => $meetingId,
            ));
            if ($exists) {
                $diff = array();
                foreach ($exists as $exist) {
                    $userId = $exist->get('user_id');
                    if (in_array($userId, $props['users'])) {
                        continue;
                    }
                    $diff[] = $userId;
                }
                if (!empty($diff)) {
                    $this->modx->removeCollection('bbbxNotifyUsers', array(
                        'meeting_id' => $meetingId,
                        'user_id:IN' => $diff,
                    ));
                }
            }
            // update
            foreach ($props['users'] as $userId) {
                $notify = $this->modx->getObject('bbbxNotifyUsers', array(
                    'meeting_id' => $meetingId,
                    'user_id'    => $userId,
                ));
                if (!$notify) {
                    $notify = $this->modx->newObject('bbbxNotifyUsers');
                    $notify->fromArray(array(
                        'meeting_id' => $meetingId,
                        'user_id'    => $userId,
                    ));
                    $notify->save();
                }
                if ($props['send_now']) {
                    $this->modx->bbbx->notifyUser($meetingId, $userId);
                }
            }
        }

        /**
         * Emails
         */
        if (isset($props['emails']) && !empty($props['emails']) && is_array($props['emails'])) {
            // diff
            $exists = $this->modx->getCollection('bbbxNotifyUsers', array(
                'meeting_id' => $meetingId,
                'user_id'    => 0,
            ));
            if ($exists) {
                $diff = array();
                foreach ($exists as $exist) {
                    $email = $exist->get('email');
                    if (in_array($email, $props['emails'])) {
                        continue;
                    }
                    $diff[] = $email;
                }
                if (!empty($diff)) {
                    $this->modx->removeCollection('bbbxNotifyUsers', array(
                        'meeting_id' => $meetingId,
                        'user_id'    => 0,
                        'email:IN'   => $diff,
                    ));
                }
            }
            // update
            foreach ($props['emails'] as $email) {
                $notify = $this->modx->getObject('bbbxNotifyUsers', array(
                    'meeting_id' => $meetingId,
                    'user_id'    => 0,
                    'email'      => $email,
                ));
                if (!$notify) {
                    $notify = $this->modx->newObject('bbbxNotifyUsers');
                    $notify->fromArray(array(
                        'meeting_id' => $meetingId,
                        'user_id'    => 0,
                        'email'      => $email,
                    ));
                    $notify->save();
                }
                if ($props['send_now']) {
                    $this->modx->bbbx->notifyUser($meetingId, 0, 0, $email);
                }
            }
        }

        return $this->success();
    }

}

return 'MeetingsNotificationsUpdateProcessor';
