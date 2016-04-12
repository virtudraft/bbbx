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
include_once MODX_CORE_PATH.'model/modx/processors/context/getlist.class.php';

class ContextsGetListProcessor extends modContextGetListProcessor
{

    public $permission = '';

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'key:LIKE'            => '%'.$query.'%',
                'OR:name:LIKE'        => '%'.$query.'%',
                'OR:description:LIKE' => '%'.$query.'%',
            ));
        }
        $exclude = $this->getProperty('exclude');
        if (!empty($exclude)) {
            $c->where(array(
                'key:NOT IN' => is_string($exclude) ? explode(',', $exclude) : $exclude,
            ));
        }

        return $c;
    }

}

return 'ContextsGetListProcessor';
