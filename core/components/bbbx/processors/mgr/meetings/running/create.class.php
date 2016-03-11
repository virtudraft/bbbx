<?php

class MeetingsCreateProcessor extends modObjectProcessor {
    public $languageTopics = array('bbbx:cmp');
    public $objectType = 'bbbx.MeetingsCreate';

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        $name = $this->getProperty('name', false);
        if (empty($name)) {
            return $this->modx->lexicon('bbbx.meeting_err_ns_name');
        }
        $this->unsetProperty('action');

        return true;
    }

    /**
     * Process the Object create processor
     * {@inheritDoc}
     * @return mixed
     */
    public function process() {
        $props = $this->getProperties();
        $this->object = $this->modx->bbbx->createMeeting($props);
        if (empty($this->object)) {
            return $this->failure($this->modx->lexicon($this->objectType . '_err_save'));
        }
        $this->logManagerAction();
        return $this->cleanup();
    }

    /**
     * Return the success message
     * @return array
     */
    public function cleanup() {
        return $this->success('', $this->object);
    }

    /**
     * Log the removal manager action
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction($this->objectType . '_create', 'bbbxMeetings', $this->getProperty('meetingID'));
    }

}

return 'MeetingsCreateProcessor';
