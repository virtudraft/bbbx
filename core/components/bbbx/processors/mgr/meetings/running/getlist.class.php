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
class MeetingsRunningGetListProcessor extends modObjectProcessor
{

    public $languageTopics      = array('bbbx:default');
    public $objectType          = 'bbbx.MeetingsRunningGetList';
    public $checkListPermission = false;
    public $currentIndex        = 0;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 20,
            'sort'  => $this->defaultSortField,
            'dir'   => $this->defaultSortDirection,
            'combo' => false,
            'query' => '',
        ));

        return parent::initialize();
    }

    public function process()
    {
        $data = $this->getData();
        $isError  = $this->modx->bbbx->getError();
        if (!empty($isError)) {
            return $this->failure($isError);
        }
        $list = $this->iterate($data);
        return $this->outputArray($list, $data['total']);
    }

    /**
     * Iterate across the data
     *
     * @param array $data
     * @return array
     */
    public function iterate(array $data)
    {
        $list               = array();
        $list               = $this->beforeIteration($list);
        $this->currentIndex = 0;
        foreach ($data['results'] as $object) {
            $objectArray = $this->prepareRow($object);
            if (!empty($objectArray) && is_array($objectArray)) {
                $list[] = $objectArray;
                $this->currentIndex++;
            }
        }
        $list = $this->afterIteration($list);
        return $list;
    }

    /**
     * Can be used to insert a row before iteration
     * @param array $list
     * @return array
     */
    public function beforeIteration(array $list)
    {
        return $list;
    }

    /**
     * Can be used to insert a row after iteration
     * @param array $list
     * @return array
     */
    public function afterIteration(array $list)
    {
        return $list;
    }

    /**
     * Get the data of the query
     * @return array
     */
    public function getData()
    {
        $data  = array();
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));

        $meetings = $this->modx->bbbx->getMeetings($limit, $start);
        if (empty($meetings)) {
            $meetings = array();
        }

        $data['total']   = count($meetings);
        $data['results'] = $meetings;

        return $data;
    }

    /**
     * Prepare the row for iteration
     * @param $array
     * @return array
     */
    public function prepareRow($array)
    {
        $array['joinURL'] = $this->modx->bbbx->getJoinMeetingURL($array['meetingID'], $array['moderatorPW']);

        return $array;
    }

}

return 'MeetingsRunningGetListProcessor';
