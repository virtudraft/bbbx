<?php

class ScheduledMeetingsCreateProcessor extends modObjectCreateProcessor
{

    public $classKey       = 'bbbxMeetings';
    public $languageTopics = array('bbbx:cmp');
    public $objectType     = 'bbbx.ScheduledMeetingsCreate';

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $name = $this->getProperty('name', false);
        if (empty($name)) {
            return $this->modx->lexicon('bbbx.meeting_err_ns_name');
        }
        $this->unsetProperty('action');
        $this->object = $this->modx->newObject($this->classKey);

        return true;
    }

    /**
     * Process the Object create processor
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        $props = $this->getProperties();
        if (empty($props['meeting_id'])) {
            $props['meeting_id'] = uniqid();
        }
        if (!empty($props['started_date']) && !empty($props['started_time'])) {
            $date                = DateTime::createFromFormat('m/d/Y H:i', $props['started_date'].' '.$props['started_time']);
            $props['started_on'] = $date->format('U');
        }
        if (!empty($props['ended_date']) && !empty($props['ended_time'])) {
            $date              = DateTime::createFromFormat('m/d/Y H:i', $props['ended_date'].' '.$props['ended_time']);
            $props['ended_on'] = $date->format('U');
        }
        $props['created_on'] = time();
        $props['created_by'] = $this->modx->user->get('id');

        if (isset($props['preloadSlides']) &&
                !empty($props['preloadSlides']) &&
                isset($props['preloadSlidesSourceId']) &&
                !empty($props['preloadSlidesSourceId'])
        ) {
            $mediaSource = $this->modx->getObject('sources.modMediaSource', array('id' => $props['preloadSlidesSourceId']));
            if (!$mediaSource) {
                return $this->failure('the selected media source is unavailable');
            }
            $mediaSource->initialize();
            $bases = $mediaSource->getBases();
            if ($bases['urlIsRelative']) {
                $objectUrl = MODX_URL_SCHEME.MODX_HTTP_HOST.$mediaSource->getObjectUrl($props['preloadSlides']);
            } else {
                $objectUrl = $mediaSource->getObjectUrl($props['preloadSlides']);
            }
            $props['document_url'] = $objectUrl;
        }
        if (empty($props['context_key'])) {
            $props['context_key'] = 'web';
        } else {
            $props['context_key'] = @implode(',', $props['context_key']);
        }

        if (!empty($props['moderator_usergroups'])) {
            $many = array();
            foreach ($props['moderator_usergroups'] as $id) {
                $meetingUgs = $this->modx->newObject('bbbxMeetingUsergroups');
                $meetingUgs->set('usergroup_id', $id);
                $meetingUgs->set('enroll', 'moderator');
                $many[]     = $meetingUgs;
            }
            $this->object->addMany($many);
        }
        if (!empty($props['viewer_usergroups'])) {
            $many = array();
            foreach ($props['viewer_usergroups'] as $id) {
                $meetingUgs = $this->modx->newObject('bbbxMeetingUsergroups');
                $meetingUgs->set('usergroup_id', $id);
                $meetingUgs->set('enroll', 'viewer');
                $many[]     = $meetingUgs;
            }
            $this->object->addMany($many);
        }
        if (!empty($props['moderator_users'])) {
            $many = array();
            foreach ($props['moderator_users'] as $id) {
                $meetingUsers = $this->modx->newObject('bbbxMeetingUsers');
                $meetingUsers->set('user_id', $id);
                $meetingUsers->set('enroll', 'moderator');
                $many[]       = $meetingUsers;
            }
            $this->object->addMany($many);
        }
        if (!empty($props['viewer_users'])) {
            $many = array();
            foreach ($props['viewer_users'] as $id) {
                $meetingUsers = $this->modx->newObject('bbbxMeetingUsers');
                $meetingUsers->set('user_id', $id);
                $meetingUsers->set('enroll', 'viewer');
                $many[]       = $meetingUsers;
            }
            $this->object->addMany($many);
        }

        $this->object->fromArray($props);
        if ($this->object->save() === false) {
            return $this->failure($this->modx->lexicon($this->objectType.'_err_save'));
        }
        $configId = $this->getProperty('config');
        $msg      = '';
        if (!empty($configId)) {
            $config = $this->modx->getObject('bbbxConfigs', $configId);
            if ($config) {
                $params        = array(
                    'meeting_id' => $props['meeting_id'],
                    'config_id'  => $configId,
                );
                $meetingConfig = $this->modx->getObject('bbbxMeetingsConfigs', $params);
                if (!$meetingConfig) {
                    $xml          = $config->get('xml');
                    $setConfigXML = $this->modx->bbbx->setConfigXML(array(
                        'meetingID' => $props['meeting_id'],
                        'configXML' => $xml,
                    ));
                    $isError      = $this->modx->bbbx->getError();
                    if ($isError) {
                        $msg = $isError;
                    } else if ($setConfigXML && isset($setConfigXML['token']) && !empty($setConfigXML['token'])) {
                        $meetingConfig          = $this->modx->newObject('bbbxMeetingsConfigs');
                        $params['config_token'] = $setConfigXML['token'];
                        $meetingConfig->fromArray($params);
                        $meetingConfig->save();
                    }
                }
            }
        }
        $this->logManagerAction();

        return $this->cleanup($msg);
    }

    /**
     * Return the success message
     * @return array
     */
    public function cleanup($msg = '')
    {
        return $this->success($msg, $this->object);
    }

    /**
     * Log the removal manager action
     * @return void
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction($this->objectType.'_create', $this->classKey, $this->object->get($this->primaryKeyField));
    }

}

return 'ScheduledMeetingsCreateProcessor';