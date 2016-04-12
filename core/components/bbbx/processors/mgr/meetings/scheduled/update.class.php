<?php

class ScheduledMeetingsUpdateProcessor extends modObjectUpdateProcessor
{

    public $classKey       = 'bbbxMeetings';
    public $languageTopics = array('bbbx:cmp');
    public $objectType     = 'bbbx.ScheduledMeetingsUpdate';

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

        return parent::initialize();
    }

    /**
     * Process the Object create processor
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        $props = $this->getProperties();
        $objId = $this->object->get($this->primaryKeyField);
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
            // delete diffs
            $c     = $this->modx->newQuery('bbbxMeetingUsergroups');
            $c->where(array(
                'meeting_id:='        => $objId,
                'usergroup_id:NOT IN' => $props['moderator_usergroups'],
                'enroll:='            => 'moderator',
            ));
            $diffs = $this->modx->getCollection('bbbxMeetingUsergroups', $c);
            if ($diffs) {
                foreach ($diffs as $diff) {
                    $diff->remove();
                }
            }
            $many = array();
            foreach ($props['moderator_usergroups'] as $id) {
                $params     = array(
                    'meeting_id'   => $objId,
                    'usergroup_id' => $id,
                    'enroll'       => 'moderator',
                );
                $meetingUgs = $this->modx->getObject('bbbxMeetingUsergroups', $params);
                if (!empty($meetingUgs)) {
                    continue;
                }
                $meetingUgs = $this->modx->newObject('bbbxMeetingUsergroups');
                $meetingUgs->fromArray($params);
                $many[]     = $meetingUgs;
            }
            $this->object->addMany($many);
        }
        if (!empty($props['viewer_usergroups'])) {
            // delete diffs
            $c     = $this->modx->newQuery('bbbxMeetingUsergroups');
            $c->where(array(
                'meeting_id:='        => $objId,
                'usergroup_id:NOT IN' => $props['viewer_usergroups'],
                'enroll:='            => 'viewer',
            ));
            $diffs = $this->modx->getCollection('bbbxMeetingUsergroups', $c);
            if ($diffs) {
                foreach ($diffs as $diff) {
                    $diff->remove();
                }
            }
            $many = array();
            foreach ($props['viewer_usergroups'] as $id) {
                $params     = array(
                    'meeting_id'   => $objId,
                    'usergroup_id' => $id,
                    'enroll'       => 'viewer',
                );
                $meetingUgs = $this->modx->getObject('bbbxMeetingUsergroups', $params);
                if (!empty($meetingUgs)) {
                    continue;
                }
                $meetingUgs = $this->modx->newObject('bbbxMeetingUsergroups');
                $meetingUgs->fromArray($params);
                $many[]     = $meetingUgs;
            }
            $this->object->addMany($many);
        }
        if (!empty($props['moderator_users'])) {
            // delete diffs
            $c     = $this->modx->newQuery('bbbxMeetingUsers');
            $c->where(array(
                'meeting_id:='   => $objId,
                'user_id:NOT IN' => $props['moderator_users'],
                'enroll:='       => 'moderator',
            ));
            $diffs = $this->modx->getCollection('bbbxMeetingUsers', $c);
            if ($diffs) {
                foreach ($diffs as $diff) {
                    $diff->remove();
                }
            }
            $many = array();
            foreach ($props['moderator_users'] as $id) {
                $params       = array(
                    'meeting_id' => $objId,
                    'user_id'    => $id,
                    'enroll'     => 'moderator',
                );
                $meetingUsers = $this->modx->getObject('bbbxMeetingUsers', $params);
                if (!empty($meetingUsers)) {
                    continue;
                }
                $meetingUsers = $this->modx->newObject('bbbxMeetingUsers');
                $meetingUsers->fromArray($params);
                $many[]       = $meetingUsers;
            }
            $this->object->addMany($many);
        }
        if (!empty($props['viewer_users'])) {
            // delete diffs
            $c     = $this->modx->newQuery('bbbxMeetingUsers');
            $c->where(array(
                'meeting_id:='   => $objId,
                'user_id:NOT IN' => $props['viewer_users'],
                'enroll:='       => 'viewer',
            ));
            $diffs = $this->modx->getCollection('bbbxMeetingUsers', $c);
            if ($diffs) {
                foreach ($diffs as $diff) {
                    $diff->remove();
                }
            }
            $many = array();
            foreach ($props['viewer_users'] as $id) {
                $params       = array(
                    'meeting_id' => $objId,
                    'user_id'    => $id,
                    'enroll'     => 'viewer',
                );
                $meetingUsers = $this->modx->getObject('bbbxMeetingUsers', $params);
                if (!empty($meetingUsers)) {
                    continue;
                }
                $meetingUsers = $this->modx->newObject('bbbxMeetingUsers');
                $meetingUsers->fromArray($params);
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
        $this->modx->logManagerAction($this->objectType.'_update', $this->classKey, $this->object->get($this->primaryKeyField));
    }

}

return 'ScheduledMeetingsUpdateProcessor';