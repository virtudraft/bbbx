<?php

include_once MODX_CORE_PATH.'model/modx/processors/security/group/getlist.class.php';

class UsergroupsGetListProcessor extends modUserGroupGetListProcessor
{

    public $permission = '';

    public function beforeIteration(array $list) {
        if ($this->getProperty('combo',false)) {
            $list[] = array(
                'id' => '',
                'name' => '',
                'description' => '',
                'parent' => 0,
            );
        }
        return $list;
    }

}

return 'UsergroupsGetListProcessor';
