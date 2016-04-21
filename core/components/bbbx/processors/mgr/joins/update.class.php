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
class JoinsUpdateProcessor extends modObjectUpdateProcessor
{

    public $object;
    public $classKey        = 'bbbxMeetingsJoins';
    public $primaryKeyField = 'id';
    public $permission      = '';
    public $languageTopics  = array('bbbx:cmp');
    public $objectType      = 'bbbx.JoinsUpdate';

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $this->unsetProperty('action');
        $meetingId = intval($this->getProperty('meeting_id'));
        if (empty($meetingId)) {
            return $this->modx->lexicon('bbbx.join_err_ns_meeting_id');
        }
        $classKey = $this->getProperty('classkey');
        if (empty($classKey)) {
            return $this->modx->lexicon('bbbx.join_err_ns_class_key');
        }
        $objectId = intval($this->getProperty('object_id'));
        if (empty($objectId)) {
            return $this->modx->lexicon('bbbx.join_err_ns_object_id');
        }

        return parent::initialize();
    }

}

return 'JoinsUpdateProcessor';
