<?php

include_once MODX_CORE_PATH.'model/modx/processors/context/getlist.class.php';

class ContextsGetListProcessor extends modContextGetListProcessor
{

    public $permission = '';

    public function beforeIteration(array $list) {
        if ($this->getProperty('combo',false)) {
            $list[] = array(
                'key' => '',
                'name' => '',
                'description' => '',
                'parent' => 0,
            );
        }

        return $list;
    }

}

return 'ContextsGetListProcessor';
