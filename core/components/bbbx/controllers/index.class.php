<?php

require_once dirname(dirname(__FILE__)) . '/model/bbbx.class.php';

class BBBxIndexManagerController extends modExtraManagerController {

    /** @var BBBx $bbbx */
    public $bbbx;

    public function initialize() {
        $this->bbbx = new BBBx($this->modx);
        $this->addCss($this->bbbx->config['cssUrl'] . 'mgr.css');
        $this->addJavascript($this->bbbx->config['jsUrl'] . 'mgr/bbbx.js');
        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                BBBx.config = ' . $this->modx->toJSON($this->bbbx->config) . ';
            });
            </script>');
        return parent::initialize();
    }

    public function getLanguageTopics() {
        return array('bbbx:cmp');
    }

    public function checkPermissions() {
        return true;
    }

    public function process(array $scriptProperties = array()) {

    }

    public function getPageTitle() {
        return $this->modx->lexicon('bbbx');
    }

    public function loadCustomCssJs() {
//        $this->addJavascript($this->bbbx->config['jsUrl'] . 'mgr/widgets/window.meetinginfo.js');
        $this->addJavascript($this->bbbx->config['jsUrl'] . 'mgr/widgets/window.meeting.js');
        $this->addJavascript($this->bbbx->config['jsUrl'] . 'mgr/widgets/grid.scheduledmeetings.js');
        $this->addJavascript($this->bbbx->config['jsUrl'] . 'mgr/widgets/grid.runningmeetings.js');
        $this->addJavascript($this->bbbx->config['jsUrl'] . 'mgr/widgets/panel.home.js');
        $this->addLastJavascript($this->bbbx->config['jsUrl'] . 'mgr/sections/index.js');
    }

    public function getTemplateFile() {
        return $this->bbbx->config['templatesPath'] . 'home.tpl';
    }

}
