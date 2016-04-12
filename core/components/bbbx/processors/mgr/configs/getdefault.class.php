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
class ConfigsGetProcessor extends modObjectProcessor
{

    public $languageTopics      = array('bbbx:default');
    public $objectType          = 'bbbx.ConfigsGet';
    public $checkListPermission = false;
    public $currentIndex        = 0;

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        $this->object = $this->modx->bbbx->getDefaultConfigXML();

        $isError = $this->modx->bbbx->getError();
        if (!empty($isError)) {
            return $isError;
        }
        $this->object['default'] = json_encode($this->object);

        return $this->cleanup();
    }

    /**
     * Return the response
     * @return array
     */
    public function cleanup()
    {
        return $this->success('', $this->object);
    }

}

return 'ConfigsGetProcessor';
