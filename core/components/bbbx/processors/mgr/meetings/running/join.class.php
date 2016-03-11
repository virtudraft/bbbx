<?php

class MeetingsJoinProcessor extends modObjectProcessor {
    public $languageTopics = array('bbbx:cmp');
    public $objectType = 'bbbx.MeetingsJoin';

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
        $href = $this->modx->bbbx->getJoinMeetingURL($props['meetingID'], $props['moderatorPW']);
        if (empty($href)) {
            return $this->failure($this->modx->lexicon($this->objectType . '_err_join'));
        }
        $this->object = array(
            'href' => $href
        );
        return $this->success('', $this->object);
    }

}

return 'MeetingsJoinProcessor';
