<?php

class RecordingsPublishProcessor extends modObjectProcessor {
    public $languageTopics = array('bbbx:cmp');
    public $objectType = 'bbbx.RecordingsPublish';

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        $recordID = $this->getProperty('recordID', false);
        if (empty($recordID)) {
            return $this->modx->lexicon('bbbx.meeting_err_ns_recordID');
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
        $this->object = $this->modx->bbbx->publishRecordings($props['recordID'], $props['published']);
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
        $this->modx->logManagerAction($this->objectType . '_publish', 'bbbxRecordings', $this->getProperty('href'));
    }

}

return 'RecordingsPublishProcessor';
