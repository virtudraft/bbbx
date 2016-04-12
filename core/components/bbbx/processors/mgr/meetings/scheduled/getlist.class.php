<?php

class MeetingsScheduledGetListProcessor extends modObjectGetListProcessor
{

    public $classKey       = 'bbbxMeetings';
    public $languageTopics = array('bbbx:default');
    public $objectType     = 'bbbx.MeetingsScheduledGetList';

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
        $ugs = $object->getMany('MeetingUsergroups');
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
        $users = $object->getMany('MeetingUsers');
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

        return $objectArray;
    }

}

return 'MeetingsScheduledGetListProcessor';
