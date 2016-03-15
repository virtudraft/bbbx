<?php

class ConfigsGetListProcessor extends modObjectGetListProcessor
{

    public $classKey             = 'bbbxConfigs';
    public $languageTopics       = array('bbbx:cmp');
    public $defaultSortField     = 'id';
    public $defaultSortDirection = 'ASC';
    public $objectType           = 'bbbx.configs';

}

return 'ConfigsGetListProcessor';
