<?php

class BigBlueButton
{

    /**
     * @var string
     */
    private $securitySalt;

    /**
     * @var string
     */
    private $bbbServerBaseUrl;

    /**
     * @var array
     */
    private $queries = array();

    /**
     * @var array
     */
    private $meta = array();

    public function __construct($bbbServerBaseUrl, $securitySalt)
    {
        $this->bbbServerBaseUrl = $bbbServerBaseUrl;
        $this->securitySalt = $securitySalt;
    }

    public function getApiVersion()
    {
        return $this->processXmlResponseArray($this->buildUrl());
    }

    public function getMeetings($limit = 0, $start = 0)
    {
        return $this->processXmlResponseArray($this->buildUrl(__FUNCTION__));
    }

    public function getJoinMeetingURL(array $params)
    {
        foreach ($params as $k => $v) {
            $this->setQuery($k, $v);
        }
        return $this->buildUrl('join', $this->getHTTPQuery());
    }

    /**
     * @param $params
     * @param array $meta
     *
     * @return string
     */
    public function getCreateMeetingUrl(array $params, array $meta = array())
    {
        foreach ($params as $k => $v) {
            $this->setQuery($k, $v);
        }
        foreach ($meta as $k => $v) {
            $this->setMeta($k, $v);
        }
        return $this->buildUrl('create', $this->getHTTPQuery());
    }

    public function createMeeting(array $params, array $meta = array(), $postFields = '')
    {
        return $this->processXmlResponseArray($this->getCreateMeetingURL($params, $meta), $postFields);
    }

    /**
     * @param $params
     *
     * @return string
     */
    public function getEndMeetingURL(array $params)
    {
        foreach ($params as $k => $v) {
            $this->setQuery($k, $v);
        }
        return $this->buildUrl('end', $this->getHTTPQuery());
    }

    /**
     * @param $params
     *
     * @return array
     */
    public function endMeeting(array $params)
    {
        return $this->processXmlResponseArray($this->getEndMeetingURL($params));
    }

    /**
     * @param $meetingID
     * @return string
     */
    public function getIsMeetingRunningUrl($meetingID)
    {
        $this->setQuery('meetingID', $meetingID);
        return $this->buildUrl('isMeetingRunning', $this->getHTTPQuery());
    }

    /**
     * @param $meetingID
     * @throws \Exception
     */
    public function isMeetingRunning($meetingID)
    {
        return $this->processXmlResponseArray($this->getIsMeetingRunningUrl($meetingID));
    }

    /**
     * @param $params
     * @return string
     */
    public function getMeetingInfoUrl(array $params)
    {
        foreach ($params as $k => $v) {
            $this->setQuery($k, $v);
        }
        return $this->buildUrl('getMeetingInfo', $this->getHTTPQuery());
    }

    /**
     * @param $params
     * @return GetMeetingInfoResponse
     */
    public function getMeetingInfo(array $params)
    {
        return $this->processXmlResponseArray($this->getMeetingInfoUrl($params));
    }

    /* __________________ BBB RECORDING METHODS _________________ */
    /* The methods in the following section support the following categories of the BBB API:
    -- getRecordings
    -- publishRecordings
    -- deleteRecordings
    */

    /**
     * @param $params
     * @return string
     */
    public function getRecordingsUrl(array $params)
    {
        foreach ($params as $k => $v) {
            $this->setQuery($k, $v);
        }
        return $this->buildUrl('getRecordings', $this->getHTTPQuery());
    }

    public function getRecordings(array $params)
    {
        return $this->processXmlResponseArray($this->getRecordingsUrl($params));
    }

    /**
     * @param $params
     * @return string
     */
    public function getPublishRecordingsUrl(array $params)
    {
        foreach ($params as $k => $v) {
            $this->setQuery($k, $v);
        }
        return $this->buildUrl('publishRecordings', $this->getHTTPQuery());
    }

    public function publishRecordings(array $params)
    {
        return $this->processXmlResponseArray($this->getPublishRecordingsUrl($params));
    }

    /**
     * @param $params
     * @return string
     */
    public function getDeleteRecordingsUrl(array $params)
    {
        foreach ($params as $k => $v) {
            $this->setQuery($k, $v);
        }
        return $this->buildUrl('deleteRecordings', $this->getHTTPQuery());
    }

    public function deleteRecordings(array $params)
    {
        return $this->processXmlResponseArray($this->getDeleteRecordingsUrl($params));
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getQuery($key)
    {
        return $this->queries[$key];
    }

    /**
     * @param string $key
     * @param mixed  $val
     *
     * @return CreateMeetingParameters
     */
    public function setQuery($key, $val = '')
    {
        $this->queries[$key] = $val;

        return $this;
    }

    /**
     * @return string
     */
    public function getMeta($key)
    {
        return $this->meta[$key];
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return CreateMeetingParameters
     */
    public function setMeta($key, $value)
    {
        /**
         * Remove prefix to assure the standard
         */
        $key = preg_replace('/^meta_/', '', $key);
        $this->meta[$key] = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getHTTPQuery()
    {
        $queries = $this->queries;
        if (!empty($this->meta)) {
            foreach ($this->meta as $k => $v) {
                /**
                 * Append prefix to apply the standard
                 */
                $queries['meta_' . strtolower($k)] = $v;
            }
        }

        return $this->buildHTTPQuery($queries);
    }

    /**
     * @param $array
     *
     * @return string
     */
    protected function buildHTTPQuery($array)
    {
        return http_build_query(array_filter($array));
    }

    /**
     * Builds an API method URL and generates its checksum.
     *
     * @param string $method
     * @param string $params
     *
     * @return string
     */
    public function buildUrl($method = '', $params = '')
    {
        return $this->bbbServerBaseUrl . 'api/' . $method . '?' . $params . '&checksum=' . sha1($method . $params . $this->securitySalt);
    }

    public function processXmlResponse($url, $postFields = '')
    {
        /*
          A private utility method used by other public methods to process XML responses.
         */
        if (extension_loaded('curl')) {
            $ch = curl_init() or die(curl_error());
            $timeout = 10;
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            if (!empty($postFields)) {
                if (is_array($postFields)) {
                    $postFields = http_build_query($postFields);
                }
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-type: application/xml',
                    'Content-length: ' . strlen($postFields),
                ]);
            }
            $data = curl_exec($ch);
            curl_close($ch);

            if ($data) {
                return new SimpleXMLElement($data);
            } else {
                return false;
            }
        }
        if (!empty($postFields)) {
            throw new \Exception('Set xml, but curl does not installed.');
        }

        return simplexml_load_file($url);
    }

    public function processXmlResponseArray($url, $postFields = '')
    {
        $xml = $this->processXmlResponse($url, $postFields);

        return json_decode(json_encode($xml), true);
    }
}
