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
class MeetingsNotificationsGetProcessor extends modObjectProcessor
{

    public $languageTopics = array('bbbx:default');
    public $objectType     = 'bbbx.MeetingsNotificationsGet';

    public function initialize()
    {
        $meetingID = $this->getProperty('meetingID', false);
        if (empty($meetingID)) {
            return $this->modx->lexicon('bbbx.meeting_err_ns_meetingID');
        }

        return true;
    }

    public function process()
    {
        $meetingID = $this->getProperty('meetingID', false);
        $limit     = intval($this->getProperty('limit'));
        $start     = intval($this->getProperty('start'));

        /* bbbxNotifyUsergroups */
        $c        = $this->modx->newQuery('bbbxNotifyUsergroups');
        $c->select(array(
            'bbbxNotifyUsergroups.*'
        ));
        $c->leftJoin('bbbxMeetings', 'Meetings', 'Meetings.id = bbbxNotifyUsergroups.meeting_id');
        $c->where("Meetings.meeting_id = '$meetingID'");
        $totalUgs = $this->modx->getCount('bbbxNotifyUsergroups', $c);

        $c->sortby('bbbxNotifyUsergroups.id', 'asc');
        if ($limit > 0) {
            $c->limit($limit, $start);
        }

        $results = $this->modx->getCollection('bbbxNotifyUsergroups', $c);
        $list    = array();
        foreach ($results as $object) {
            $objectArray = $this->prepareRow($object);
            if (!empty($objectArray) && is_array($objectArray)) {
                $list[] = $objectArray['usergroup_id'];
            }
        }
        $usergroups = @implode(',', $list);

        /* bbbxNotifyUsers */
        $c          = $this->modx->newQuery('bbbxNotifyUsers');
        $c->select(array(
            'bbbxNotifyUsers.*'
        ));
        $c->leftJoin('bbbxMeetings', 'Meetings', 'Meetings.id = bbbxNotifyUsers.meeting_id');
        $c->where("Meetings.meeting_id = '$meetingID'");
        $totalUsers = $this->modx->getCount('bbbxNotifyUsers', $c);

        $c->sortby('bbbxNotifyUsers.id', 'asc');
        if ($limit > 0) {
            $c->limit($limit, $start);
        }

        $results = $this->modx->getCollection('bbbxNotifyUsers', $c);
        $isSent  = false;
        $list    = array();
        foreach ($results as $object) {
            $objectArray = $this->prepareRow($object);
            if (!empty($objectArray) && is_array($objectArray)) {
                $list[] = $objectArray['user_id'];
                $isSent = $objectArray['is_sent'];
            }
        }
        $users = @implode(',', $list);

        /* emails */
        $c           = $this->modx->newQuery('bbbxNotifyUsers');
        $c->select(array(
            'bbbxNotifyUsers.*'
        ));
        $c->leftJoin('bbbxMeetings', 'Meetings', 'Meetings.id = bbbxNotifyUsers.meeting_id');
        $c->where("Meetings.meeting_id = '$meetingID'");
        $c->where(array(
            'user_id:=' => 0,
            'email:!=' => '',
        ));
        $totalEmails = $this->modx->getCount('bbbxNotifyUsers', $c);

        $c->sortby('bbbxNotifyUsers.id', 'asc');
        if ($limit > 0) {
            $c->limit($limit, $start);
        }

        $results = $this->modx->getCollection('bbbxNotifyUsers', $c);
        $list    = array();
        foreach ($results as $object) {
            $objectArray = $this->prepareRow($object);
            if (!empty($objectArray) && is_array($objectArray)) {
                $list[] = $objectArray['email'];
            }
        }
        $emails = @implode(',', $list);

        return $this->success('', array(
                    'usergroups'       => $usergroups,
                    'users'            => $users,
                    'emails'           => $emails,
                    'total_usergroups' => $totalUgs,
                    'total_users'      => $totalUsers,
                    'total_emails'     => $totalEmails,
                    'is_sent'          => $isSent
        ));
    }

    public function prepareRow(xPDOObject $object)
    {
        return $object->toArray();
    }

}

return 'MeetingsNotificationsGetProcessor';
