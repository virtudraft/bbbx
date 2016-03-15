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
        $postFields = '';
        if (isset($props['preloadSlides']) &&
                !empty($props['preloadSlides']) &&
                isset($props['preloadSlidesSourceId']) &&
                !empty($props['preloadSlidesSourceId'])
                ) {
            $mediaSource = $this->modx->getObject('sources.modMediaSource', array('id' => $props['preloadSlidesSourceId']));
            if (!$mediaSource) {
                return 'the selected media source is unavailable';
            }
            $mediaSource->initialize();
            $bases = $mediaSource->getBases();
            if ($bases['urlIsRelative']) {
                $objectUrl = MODX_URL_SCHEME . MODX_HTTP_HOST . $mediaSource->getObjectUrl($props['preloadSlides']);
            } else {
                $objectUrl = $mediaSource->getObjectUrl($props['preloadSlides']);
            }
            $postFields = '<?xml version="1.0" encoding="UTF-8"?><modules><module name="presentation"><document url="' . $objectUrl . '"/></module></modules>';
        }
        $meta = array();
        if (isset($props['meta']) && !empty($props['meta'])) {
            $metaProp = array_map('trim', @explode(',', $props['meta']));
            foreach ($metaProp as $v) {
                list($key, $val) = @explode('=', $v);
                $meta[$key] = $val;
            }
        }
        unset($props['meta']);
        $this->object = $this->modx->bbbx->createMeeting($props, $meta, $postFields);
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
