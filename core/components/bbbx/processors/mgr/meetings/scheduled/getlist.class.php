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
class MeetingsScheduledGetListProcessor extends modObjectGetListProcessor
{

    public $defaultSortField     = 'id';
    public $defaultSortDirection = 'DESC';
    public $classKey             = 'bbbxMeetings';
    public $languageTopics       = array('bbbx:default');
    public $objectType           = 'bbbx.MeetingsScheduledGetList';

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query', '');
        if (!empty($query)) {
            $c->where(array(
                'name:LIKE'           => '%'.$query.'%',
                'OR:description:LIKE' => '%'.$query.'%',
            ));
        }

        return $c;
    }

    /**
     * Prepare the row for iteration
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        if (!empty($objectArray['started_on'])) {
            $objectArray['started_date'] = date('m/d/Y', $objectArray['started_on']);
            $objectArray['started_time'] = date('H:i', $objectArray['started_on']);
        }
        if (!empty($objectArray['ended_on'])) {
            $objectArray['ended_date'] = date('m/d/Y', $objectArray['ended_on']);
            $objectArray['ended_time'] = date('H:i', $objectArray['ended_on']);
        }
        $ctxs = $object->getMany('MeetingsContexts');
        if ($ctxs) {
            $data = array();
            foreach ($ctxs as $ctx) {
                $data[] = $ctx->get('context_key');
            }
            $objectArray['context_key'] = @implode(',', $data);
        }
        $ugs = $object->getMany('MeetingsUsergroups');
        if ($ugs) {
            $moderator = array();
            $viewer    = array();
            foreach ($ugs as $ug) {
                $ugArray = $ug->toArray();
                if ($ugArray['enroll'] === 'moderator') {
                    $moderator[] = $ugArray['usergroup_id'];
                } else {
                    $viewer[] = $ugArray['usergroup_id'];
                }
            }
            $objectArray['moderator_usergroups'] = @implode(',', $moderator);
            $objectArray['viewer_usergroups']    = @implode(',', $viewer);
        }
        $users = $object->getMany('MeetingsUsers');
        if ($users) {
            $moderator = array();
            $viewer    = array();
            foreach ($users as $user) {
                $userArray = $user->toArray();
                if ($userArray['enroll'] === 'moderator') {
                    $moderator[] = $userArray['user_id'];
                } else {
                    $viewer[] = $userArray['user_id'];
                }
            }
            $objectArray['moderator_users'] = @implode(',', $moderator);
            $objectArray['viewer_users']    = @implode(',', $viewer);
        }
        $objectArray['is_created'] = $this->modx->bbbx->getMeetingInfo($objectArray['meeting_id']);
        $objectArray['can_create'] = false;
        if (!$objectArray['is_created']) {
            $c       = $this->modx->newQuery('bbbxMeetings');
            $time    = time();
            $c->where(array(
                'meeting_id'    => $objectArray['meeting_id'],
                'started_on:<=' => $time,
                'ended_on:>='   => $time,
            ));
            $meeting = $this->modx->getObject('bbbxMeetings', $c);
            if ($meeting) {
                $objectArray['can_create'] = true;
            }
        }

        $objectArray['is_running'] = $this->modx->bbbx->isMeetingRunning($objectArray['meeting_id']);
        if ($objectArray['is_running']) {
            $objectArray['joinURL'] = $this->modx->bbbx->getJoinMeetingURL($objectArray['meeting_id'], $objectArray['moderator_pw']);
        }
        $objectArray['recordings'] = $this->modx->bbbx->getRecordings($objectArray['meeting_id']);

        return $objectArray;
    }

}

return 'MeetingsScheduledGetListProcessor';
