<?php

include_once MODX_CORE_PATH.'model/modx/processors/security/user/getlist.class.php';

class UsersGetListProcessor extends modUserGetListProcessor
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

return 'UsersGetListProcessor';
