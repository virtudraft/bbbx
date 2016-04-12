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
 * @subpackage controller
 */

require_once dirname(dirname(__FILE__)).'/model/bbbx.class.php';

class BBBxIndexManagerController extends modExtraManagerController
{

    /** @var BBBx $bbbx */
    public $bbbx;

    public function initialize()
    {
        $this->bbbx = new BBBx($this->modx);
        $this->addCss($this->bbbx->config['cssUrl'].'mgr.css');
        $this->addJavascript($this->bbbx->config['jsUrl'].'mgr/bbbx.js');
        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                BBBx.config = '.$this->modx->toJSON($this->bbbx->config).';
            });
            </script>');
        return parent::initialize();
    }

    public function getLanguageTopics()
    {
        return array('bbbx:cmp');
    }

    public function checkPermissions()
    {
        return true;
    }

    public function process(array $scriptProperties = array())
    {

    }

    public function getPageTitle()
    {
        return $this->modx->lexicon('bbbx');
    }

    public function loadCustomCssJs()
    {
        $this->addJavascript($this->bbbx->config['jsUrl'].'mgr/widgets/combo.contextkey.js');
        $this->addJavascript($this->bbbx->config['jsUrl'].'mgr/widgets/combo.user.js');
        $this->addJavascript($this->bbbx->config['jsUrl'].'mgr/widgets/combo.usergroup.js');
        $this->addJavascript($this->bbbx->config['jsUrl'].'mgr/widgets/combo.config.js');
        $this->addJavascript($this->bbbx->config['jsUrl'].'mgr/widgets/window.runningmeeting.js');
        $this->addJavascript($this->bbbx->config['jsUrl'].'mgr/widgets/window.scheduledmeeting.js');
        $this->addJavascript($this->bbbx->config['jsUrl'].'mgr/widgets/grid.configs.js');
        $this->addJavascript($this->bbbx->config['jsUrl'].'mgr/widgets/grid.scheduledmeetings.js');
        $this->addJavascript($this->bbbx->config['jsUrl'].'mgr/widgets/grid.runningmeetings.js');
        $this->addJavascript($this->bbbx->config['jsUrl'].'mgr/widgets/grid.recordings.js');
        $this->addJavascript($this->bbbx->config['jsUrl'].'mgr/widgets/panel.configs.js');
        $this->addJavascript($this->bbbx->config['jsUrl'].'mgr/widgets/panel.home.js');
        $this->addLastJavascript($this->bbbx->config['jsUrl'].'mgr/sections/index.js');
    }

    public function getTemplateFile()
    {
        return $this->bbbx->config['templatesPath'].'home.tpl';
    }

}
