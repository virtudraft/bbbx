<?php

class RecordingsGetListProcessor extends modObjectProcessor {

    public $languageTopics = array('bbbx:default');
    public $objectType = 'bbbx.RecordingsGetList';
    public $checkListPermission = false;
    public $currentIndex = 0;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 20,
            'sort' => $this->defaultSortField,
            'dir' => $this->defaultSortDirection,
            'combo' => false,
            'query' => '',
        ));

        return parent::initialize();
    }

    public function process() {
        $data = $this->getData();
        $list = $this->iterate($data);
        return $this->outputArray($list, $data['total']);
    }

    /**
     * Iterate across the data
     *
     * @param array $data
     * @return array
     */
    public function iterate(array $data) {
        $list = array();
        $list = $this->beforeIteration($list);
        $this->currentIndex = 0;
        foreach ($data['results'] as $object) {
            $objectArray = $this->prepareRow($object);
            if (!empty($objectArray) && is_array($objectArray)) {
                $list[] = $objectArray;
                $this->currentIndex++;
            }
        }
        $list = $this->afterIteration($list);
        return $list;
    }

    /**
     * Can be used to insert a row before iteration
     * @param array $list
     * @return array
     */
    public function beforeIteration(array $list) {
        return $list;
    }

    /**
     * Can be used to insert a row after iteration
     * @param array $list
     * @return array
     */
    public function afterIteration(array $list) {
        return $list;
    }

    /**
     * Get the data of the query
     * @return array
     */
    public function getData() {
        $data = array();
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));

        $records = $this->modx->bbbx->getRecordings('', $limit, $start);
        $isError = $this->modx->bbbx->getError();
        if (!empty($isError)) {
            return $isError;
        }
        if (empty($records)) {
            $records = array();
        }

        $data['total'] = count($records);
        $data['results'] = $records;

        return $data;
    }

    /**
     * Prepare the row for iteration
     * @param $array
     * @return array
     */
    public function prepareRow($array) {
        $array['name'] = $this->toString($array['name']);
        if (isset($array['metadata']) &&
                !empty($array['metadata']) &&
                is_array($array['metadata'])
        ) {
            $metadata = '<table>';
            foreach ($array['metadata'] as $k => $v) {
                $metadata .= '<tr><td>' . $k . '</td><td class="bbbx-td-colon">:</td><td class="bbbx-td-value">' . $this->toString($v) . '</td></tr>';
            }
            $metadata .= '</table>';
            $array['metadata'] = $metadata;
        } else {
            $array['metadata'] = '';
        }

        if (isset($array['playback']) &&
                !empty($array['playback']) &&
                isset($array['playback']['format']) &&
                !empty($array['playback']['format']) &&
                isset($array['playback']['format']['url']) &&
                !empty($array['playback']['format']['url'])
        ) {
            $array['playbackURL'] = $array['playback']['format']['url'];
        } else {
            $array['playbackURL'] = '';
        }

        return $array;
    }

    private function toString($text)
    {
        if (is_string($text)) {
            return $text;
        } elseif (is_array($text)) {
            $o = '';
            foreach ($text as $k => $v) {
                $o .= $k . ': ' . $this->toString($v) . "<br>\n";
            }
            return $o;
        } elseif (is_object($text)) {
            return $this->toString(json_decode(json_encode($text), true));
        }
        return;
    }

}

return 'RecordingsGetListProcessor';
