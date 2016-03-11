<?php

class MeetingEndProcessor extends modObjectProcessor {
    public $languageTopics = array('bbbx:cmp');
    public $objectType = 'bbbx.MeetingEnd';

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        $meetingID = $this->getProperty('meetingID', false);
        if (empty($meetingID)) {
            return $this->modx->lexicon('bbbx.meeting_err_ns_meetingID');
        }
        $moderatorPW = $this->getProperty('moderatorPW', false);
        if (empty($moderatorPW)) {
            return $this->modx->lexicon('bbbx.meeting_err_ns_moderatorPW');
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
        $this->object = $this->modx->bbbx->endMeeting($props['meetingID'], $props['moderatorPW']);
        if (empty($this->object)) {
            return $this->failure($this->modx->lexicon($this->objectType . '_err_end'));
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
        $this->modx->logManagerAction($this->objectType . '_end', 'bbbxMeetings', $this->getProperty('href'));
    }

}

return 'MeetingEndProcessor';
