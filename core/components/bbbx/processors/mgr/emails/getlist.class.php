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
class EmailsGetListProcessor extends modObjectGetListProcessor
{
    public $classKey         = 'bbbxNotifyUsers';
    public $languageTopics   = array('bbbx:default');
    public $defaultSortField = 'email';
    public $objectType       = 'bbbx.EmailsGetList';

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query', '');
        if (!empty($query)) {
            $c->where(array(
                'email:LIKE' => $query.'%',
            ));
        }

        return $c;
    }

    public function beforeIteration(array $list)
    {
        if ($this->getProperty('combo', false)) {
            $list[] = array(
                'email' => '',
            );
        }

        return $list;
    }

    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns('bbbxNotifyUsers', 'bbbxNotifyUsers', '', array('id', 'email')));

        return $c;
    }

    /**
     * Prepare the row for iteration
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray('', false, true, true);
        return $objectArray;
    }

}

return 'EmailsGetListProcessor';
