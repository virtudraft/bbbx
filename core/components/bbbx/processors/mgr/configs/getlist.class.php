<?php

class ConfigsGetListProcessor extends modObjectGetListProcessor
{

    public $classKey             = 'bbbxConfigs';
    public $languageTopics       = array('bbbx:cmp');
    public $defaultSortField     = 'id';
    public $defaultSortDirection = 'ASC';
    public $objectType           = 'bbbx.configs';

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query', false);
        if ($query) {
            $c->where(array(
                'name:LIKE'           => "%$query%",
                'OR:description:LIKE' => "%$query%",
            ));
        }
        return $c;
    }

    public function afterIteration(array $list)
    {
        $combo = $this->getProperty('combo', false);
        if ($combo) {
            array_unshift($list, array(
                'id'   => '',
                'name' => '&nbsp;'
            ));
        }
        return $list;
    }

}

return 'ConfigsGetListProcessor';
