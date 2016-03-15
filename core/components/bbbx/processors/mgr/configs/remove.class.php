<?php

class ConfigsRemoveProcessor extends modObjectRemoveProcessor
{

    public $object;
    public $classKey        = 'bbbxConfigs';
    public $primaryKeyField = 'id';
    public $permission      = '';
    public $languageTopics  = array('bbbx:cmp');
    public $objectType      = 'bbbx.ConfigsRemove';

}

return 'ConfigsRemoveProcessor';