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
class ConfigsGetListProcessor extends modObjectGetListProcessor
{

    public $classKey             = 'bbbxConfigs';
    public $languageTopics       = array('bbbx:cmp');
    public $defaultSortField     = 'id';
    public $defaultSortDirection = 'ASC';
    public $objectType           = 'bbbx.configs';

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query', false);
        if ($query) {
            $c->where(array(
                'name:LIKE'           => "%$query%",
                'OR:description:LIKE' => "%$query%",
            ));
        }
        return $c;
    }

    public function afterIteration(array $list)
    {
        $combo = $this->getProperty('combo', false);
        if ($combo) {
            array_unshift($list, array(
                'id'   => '',
                'name' => '&nbsp;'
            ));
        }
        return $list;
    }

}

return 'ConfigsGetListProcessor';
