<?php

class ConfigsGetProcessor extends modObjectGetProcessor
{

    public $object;
    public $classKey        = 'bbbxConfigs';
    public $primaryKeyField = 'id';
    public $permission      = '';
    public $languageTopics  = array('bbbx:cmp');
    public $objectType      = 'bbbx.ConfigsGet';

    /**
     * Return the response
     * @return array
     */
    public function cleanup()
    {
        $objectArray = $this->object->toArray();
        $xmlArray = json_decode(json_encode(new SimpleXMLElement($objectArray['xml'])), true);
        unset($objectArray['xml']);
        $objectArray = array_merge($objectArray, $xmlArray);

        return $this->success('', $objectArray);
    }

}

return 'ConfigsGetProcessor';
