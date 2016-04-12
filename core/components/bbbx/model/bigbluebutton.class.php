<?php

/**
 * BBBx
 *
 * Copyright 2016 by goldsky <goldsky@virtudraft.com>
 *
 * This file is part of BBBx, a BigBlueButton and MODX integration add on.
 *
 * BBBx is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation version 3,
 *
 * BBBx is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * BBBx; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package bbbx
 * @subpackage abstract class
 */
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
    private $contentType = 'application/xml';

    public function __construct($bbbServerBaseUrl, $securitySalt)
    {
        $this->bbbServerBaseUrl = $bbbServerBaseUrl;
        $this->securitySalt     = $securitySalt;
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
        $this->resetQueries();
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
        $this->resetQueries();
        foreach ($params as $k => $v) {
            $this->setQuery($k, $v);
        }
        $this->resetMeta();
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
        $this->resetQueries();
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
        $this->resetQueries();
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
        $this->resetQueries();
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
        $this->resetQueries();
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
        $this->resetQueries();
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
        $this->resetQueries();
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
     * @return string
     */
    public function getDefaultConfigXMLUrl()
    {
        return $this->buildUrl('getDefaultConfigXML', $this->getHTTPQuery());
    }

    public function getDefaultConfigXML()
    {
        return $this->processXmlResponseArray($this->getDefaultConfigXMLUrl());
    }

    /**
     * @param $params
     * @return string
     */
    public function setConfigXMLUrl(array $params)
    {
        return $this->buildUrl('setConfigXML', $this->buildHTTPQuery($params));
    }

    public function setConfigXML(array $params)
    {
        $postFields = array_merge($params, array(
            'checksum' => sha1('setConfigXML'.$this->buildHTTPQuery($params).$this->securitySalt)
        ));
        return $this->processXmlResponseArray($this->setConfigXMLUrl($params), $postFields);
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
     * @return BigBlueButton
     */
    public function setQuery($key, $val = '')
    {
        $this->queries[$key] = $val;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return BigBlueButton
     */
    public function unsetQuery($key)
    {
        unset($this->queries[$key]);
        if (!isset($this->queries)) {
            $this->queries = array();
        }

        return $this;
    }

    /**
     * @return BigBlueButton
     */
    public function resetQueries()
    {
        $this->queries = array();

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
        $key              = preg_replace('/^meta_/', '', $key);
        $this->meta[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return BigBlueButton
     */
    public function unsetMeta($key)
    {
        unset($this->meta[$key]);
        if (!isset($this->meta)) {
            $this->meta = array();
        }

        return $this;
    }

    /**
     * @return BigBlueButton
     */
    public function resetMeta()
    {
        $this->meta = array();

        return $this;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param string $key
     * @param mixed  $val
     *
     * @return BigBlueButton
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

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
                $queries['meta_'.strtolower($k)] = $v;
            }
        }

        return $this->buildHTTPQuery($queries);
    }

    /**
     * @param $array
     *
     * @return string
     */
    public function buildHTTPQuery($array)
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
        return $this->bbbServerBaseUrl.'api/'.$method.'?'.$params.'&checksum='.sha1($method.$params.$this->securitySalt);
    }

    public function processXmlResponse($url, $postFields = '')
    {
        /*
          A private utility method used by other public methods to process XML responses.
         */
        if (extension_loaded('curl')) {
            $ch      = curl_init() or die(curl_error());
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
                    'Content-type: '.$this->contentType,
                    'Content-length: '.strlen($postFields),
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
