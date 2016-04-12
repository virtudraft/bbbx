<?php

class MeetingsScheduledGetListProcessor extends modObjectGetListProcessor
{

    public $classKey            = 'bbbxMeetings';
    public $languageTopics      = array('bbbx:default');
    public $objectType          = 'bbbx.MeetingsScheduledGetList';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $query = $this->getProperty('query','');
        if (!empty($query)) {
            $c->where(array(
                'name:LIKE' => '%'.$query.'%',
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
    public function prepareRow(xPDOObject $object) {
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
            $data = array();
            foreach ($ugs as $ug) {
                $data[] = $ug->get('usergroup_id');
            }
            $objectArray['usergroups'] = @implode(',', $data);
        }
        $users = $object->getMany('MeetingUsers');
        if ($users) {
            $data = array();
            foreach ($users as $user) {
                $data[] = $user->get('user_id');
            }
            $objectArray['users'] = @implode(',', $data);
        }

        return $objectArray;
    }
}

return 'MeetingsScheduledGetListProcessor';
