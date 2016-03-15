<?php

class ConfigsGetProcessor extends modObjectProcessor
{

    public $languageTopics      = array('bbbx:default');
    public $objectType          = 'bbbx.ConfigsGet';
    public $checkListPermission = false;
    public $currentIndex        = 0;

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process()
    {
        $this->object = $this->modx->bbbx->getDefaultConfigXML();

        $isError = $this->modx->bbbx->getError();
        if (!empty($isError)) {
            return $isError;
        }
        $this->object['default'] = json_encode($this->object);

        return $this->cleanup();
    }

    /**
     * Return the response
     * @return array
     */
    public function cleanup()
    {
        return $this->success('', $this->object);
    }

}

return 'ConfigsGetProcessor';
