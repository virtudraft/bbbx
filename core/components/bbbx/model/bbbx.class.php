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
 * @subpackage main class
 */
class BBBx
{

    const VERSION = '1.0.0';
    const RELEASE = 'beta1';

    /**
     * modX object.
     *
     * @var object
     */
    public $modx;

    /**
     * $scriptProperties.
     *
     * @var array
     */
    public $config;

    /**
     * BigBlueButton object.
     *
     * @var object
     */
    public $server;

    /**
     * To hold error message.
     *
     * @var array
     */
    private $_error = array();

    /**
     * To hold output message.
     *
     * @var array
     */
    private $_output = array();

    /**
     * To hold placeholder array, flatten array with prefixable.
     *
     * @var array
     */
    private $_placeholders = array();

    /**
     * store the chunk's HTML to property to save memory of loop rendering.
     *
     * @var array
     */
    private $_chunks  = array();
    protected $url    = '';
    protected $secret = '';

    /**
     * constructor.
     *
     * @param modX  $modx
     * @param array $config parameters
     */
    public function __construct(modX $modx, $config = array())
    {
        $this->modx   = &$modx;
        $config       = is_array($config) ? $config : array();
        $basePath     = $this->modx->getOption('bbbx.core_path', $config, $this->modx->getOption('core_path').'components/bbbx/');
        $assetsUrl    = $this->modx->getOption('bbbx.assets_url', $config, $this->modx->getOption('assets_url').'components/bbbx/');
        $this->config = array_merge(array(
            'version'        => self::VERSION.'-'.self::RELEASE,
            'basePath'       => $basePath,
            'corePath'       => $basePath,
            'modelPath'      => $basePath.'model/',
            'processorsPath' => $basePath.'processors/',
            'chunksPath'     => $basePath.'elements/chunks/',
            'templatesPath'  => $basePath.'templates/',
            'jsUrl'          => $assetsUrl.'js/',
            'cssUrl'         => $assetsUrl.'css/',
            'assetsUrl'      => $assetsUrl,
            'connectorUrl'   => $assetsUrl.'conn/mgr.php',
            'phsPrefix'      => '',
                ), $config);

        $this->url    = $this->modx->getOption('bbbx.server_url', null, 'http://test-install.blindsidenetworks.com/bigbluebutton/');
        $this->secret = $this->modx->getOption('bbbx.shared_secret', null, '8cd8ef52e8e101574e400365b55e11a6');
        require_once dirname(__FILE__).'/bigbluebutton.class.php';
        $this->server = new BigBlueButton($this->url, $this->secret);
        $this->modx->lexicon->load('bbbx:default');
        $tablePrefix  = $this->modx->getOption('bbbx.table_prefix', null, $this->modx->config[modX::OPT_TABLE_PREFIX].'bbbx_');
        $this->modx->addPackage('bbbx', $this->config['modelPath'], $tablePrefix);

        $api = $this->server->getApiVersion();
//        $this->modx->log(modX::LOG_LEVEL_ERROR, '$api' . print_r($api, 1));
        if (!$api) {
            $err = 'Server is not running';
            $this->setError($err);
            $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);

            return false;
        }
    }

    /**
     * Set class configuration exclusively for multiple snippet calls.
     *
     * @param array $config snippet's parameters
     */
    public function setConfigs(array $config = array())
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * Define individual config for the class.
     *
     * @param string $key array's key
     * @param string $val array's value
     */
    public function setConfig($key, $val)
    {
        $this->config[$key] = $val;
    }

    /**
     * Set string error for boolean returned methods.
     */
    public function setError($msg)
    {
        $this->_error[] = $msg;
    }

    /**
     * Get string error for boolean returned methods.
     *
     * @param string $delimiter delimiter of the imploded output (default: "\n")
     *
     * @return string output
     */
    public function getError($delimiter = "\n")
    {
        if ($delimiter === '\n') {
            $delimiter = "\n";
        }

        return @implode($delimiter, $this->_error);
    }

    /**
     * Set string output for boolean returned methods.
     */
    public function setOutput($msg)
    {
        $this->_output[] = $msg;
    }

    /**
     * Get string output for boolean returned methods.
     *
     * @param string $delimiter delimiter of the imploded output (default: "\n")
     *
     * @return string output
     */
    public function getOutput($delimiter = "\n")
    {
        if ($delimiter === '\n') {
            $delimiter = "\n";
        }

        return @implode($delimiter, $this->_output);
    }

    /**
     * Set internal placeholder.
     *
     * @param string $key    key
     * @param string $value  value
     * @param string $prefix add prefix if it's required
     */
    public function setPlaceholder($key, $value, $prefix = '')
    {
        $prefix                            = !empty($prefix) ? $prefix : (isset($this->config['phsPrefix']) ? $this->config['phsPrefix'] : '');
        $this->_placeholders[$prefix.$key] = $this->trimString($value);
    }

    /**
     * Get an internal placeholder.
     *
     * @param string $key key
     *
     * @return string value
     */
    public function getPlaceholder($key)
    {
        return $this->_placeholders[$key];
    }

    /**
     * Set internal placeholders.
     *
     * @param array  $placeholders placeholders in an associative array
     * @param string $prefix       add prefix if it's required
     * @param bool   $merge        define whether the output will be merge to global properties or not
     * @param string $delimiter    define placeholder's delimiter
     *
     * @return mixed boolean|array of placeholders
     */
    public function setPlaceholders($placeholders, $prefix = '', $merge = true, $delimiter = '.')
    {
        if (empty($placeholders)) {
            return false;
        }
        $prefix       = !empty($prefix) ? $prefix : (isset($this->config['phsPrefix']) ? $this->config['phsPrefix'] : '');
        $placeholders = $this->trimArray($placeholders);
        $placeholders = $this->implodePhs($placeholders, rtrim($prefix, $delimiter));
        // enclosed private scope
        if ($merge) {
            $this->_placeholders = array_merge($this->_placeholders, $placeholders);
        }
        // return only for this scope
        return $placeholders;
    }

    /**
     * Get internal placeholders in an associative array.
     *
     * @return array
     */
    public function getPlaceholders()
    {
        return $this->_placeholders;
    }

    /**
     * Merge multi dimensional associative arrays with separator.
     *
     * @param array  $array     raw associative array
     * @param string $keyName   parent key of this array
     * @param string $separator separator between the merged keys
     * @param array  $holder    to hold temporary array results
     *
     * @return array one level array
     */
    public function implodePhs(array $array, $keyName = null, $separator = '.', array $holder = array())
    {
        $phs = !empty($holder) ? $holder : array();
        foreach ($array as $k => $v) {
            $key = !empty($keyName) ? $keyName.$separator.$k : $k;
            if (is_array($v)) {
                $phs = $this->implodePhs($v, $key, $separator, $phs);
            } else {
                $phs[$key] = $v;
            }
        }

        return $phs;
    }

    /**
     * Trim string value.
     *
     * @param string $string   source text
     * @param string $charlist defined characters to be trimmed
     *
     * @link http://php.net/manual/en/function.trim.php
     *
     * @return string trimmed text
     */
    public function trimString($string, $charlist = null)
    {
        if (empty($string) && !is_numeric($string)) {
            return '';
        }
        $string = htmlentities($string);
        // blame TinyMCE!
        $string = preg_replace('/(&Acirc;|&nbsp;)+/i', '', $string);
        $string = trim($string, $charlist);
        $string = trim(preg_replace('/\s+^(\r|\n|\r\n)/', ' ', $string));
        $string = html_entity_decode($string);

        return $string;
    }

    /**
     * Trim array values.
     *
     * @param array  $input    array contents
     * @param string $charlist [default: null] defined characters to be trimmed
     *
     * @link http://php.net/manual/en/function.trim.php
     *
     * @return array trimmed array
     */
    public function trimArray($input, $charlist = null)
    {
        if (is_array($input)) {
            $output = array_map(array($this, 'trimArray'), $input);
        } else {
            $output = $this->trimString($input, $charlist);
        }

        return $output;
    }

    /**
     * Parsing template.
     *
     * @param string $tpl @BINDINGs options
     * @param array  $phs placeholders
     *
     * @return string parsed output
     *
     * @link    http://forums.modx.com/thread/74071/help-with-getchunk-and-modx-speed-please?page=2#dis-post-413789
     */
    public function parseTpl($tpl, array $phs = array())
    {
        $output = '';

        if (isset($this->_chunks[$tpl]) && !empty($this->_chunks[$tpl])) {
            return $this->parseTplCode($this->_chunks[$tpl], $phs);
        }

        if (preg_match('/^(@CODE|@INLINE)/i', $tpl)) {
            $tplString           = preg_replace('/^(@CODE|@INLINE)/i', '', $tpl);
            // tricks @CODE: / @INLINE:
            $tplString           = ltrim($tplString, ':');
            $tplString           = trim($tplString);
            $this->_chunks[$tpl] = $tplString;
            $output              = $this->parseTplCode($tplString, $phs);
        } elseif (preg_match('/^@FILE/i', $tpl)) {
            $tplFile = preg_replace('/^@FILE/i', '', $tpl);
            // tricks @FILE:
            $tplFile = ltrim($tplFile, ':');
            $tplFile = trim($tplFile);
            $tplFile = $this->replacePropPhs($tplFile);
            try {
                $output = $this->parseTplFile($tplFile, $phs);
            } catch (Exception $e) {
                $err = $e->getMessage();
                $this->setError($err);
                $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);

                return false;
            }
        }
        // ignore @CHUNK / @CHUNK: / empty @BINDING
        else {
            $tplChunk = preg_replace('/^@CHUNK/i', '', $tpl);
            // tricks @CHUNK:
            $tplChunk = ltrim($tpl, ':');
            $tplChunk = trim($tpl);

            $chunk = $this->modx->getObject('modChunk', array('name' => $tplChunk), true);
            if (empty($chunk)) {
                // try to use @splittingred's fallback
                $f = $this->config['chunksPath'].strtolower($tplChunk).'.chunk.tpl';
                try {
                    $output = $this->parseTplFile($f, $phs);
                } catch (Exception $e) {
                    $err = 'Chunk: '.$tplChunk.' is not found, neither the file '.$e->getMessage();
                    $this->setError($err);
                    $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);

                    return false;
                }
            } else {
                //                $output = $this->modx->getChunk($tplChunk, $phs);
                /*
                 * @link    http://forums.modx.com/thread/74071/help-with-getchunk-and-modx-speed-please?page=4#dis-post-464137
                 */
                $chunk               = $this->modx->getParser()->getElement('modChunk', $tplChunk);
                $this->_chunks[$tpl] = $chunk->get('content');
                $chunk->setCacheable(false);
                $chunk->_processed   = false;
                $output              = $chunk->process($phs);
            }
        }

        return $output;
    }

    /**
     * Parsing inline template code.
     *
     * @param string $code HTML with tags
     * @param array  $phs  placeholders
     *
     * @return string parsed output
     */
    public function parseTplCode($code, array $phs = array())
    {
        $chunk             = $this->modx->newObject('modChunk');
        $chunk->setContent($code);
        $chunk->setCacheable(false);
        $phs               = $this->replacePropPhs($phs);
        $chunk->_processed = false;

        return $chunk->process($phs);
    }

    /**
     * Parsing file based template.
     *
     * @param string $file file path
     * @param array  $phs  placeholders
     *
     * @return string parsed output
     *
     * @throws Exception if file is not found
     */
    public function parseTplFile($file, array $phs = array())
    {
        if (!file_exists($file)) {
            throw new Exception('File: '.$file.' is not found.');
        }
        if (empty($this->_chunks[$file])) {
            $o                    = file_get_contents($file);
            $this->_chunks[$file] = $o;
        }
        $chunk = $this->modx->newObject('modChunk');

        // just to create a name for the modChunk object.
        $name = strtolower(basename($file));
        $name = rtrim($name, '.tpl');
        $name = rtrim($name, '.chunk');
        $chunk->set('name', $name);

        $chunk->setCacheable(false);
        $chunk->setContent($this->_chunks[$file]);
        $chunk->_processed = false;
        $output            = $chunk->process($phs);

        return $output;
    }

    /**
     * Parse template recursively for nesting items.
     *
     * @param string $tplItem    name of item template
     * @param string $tplWrapper name of wrapper template
     * @param array  $phs        placeholders
     * @param int    $docId      if required to make a link, add the document ID here
     * @param string $childKey   if required, define the keyname of the child's placeholder here
     *
     * @return string final output
     */
    public function parseRecursiveTpl($tplItem, $tplWrapper, $phs = array(), $docId = null, $childKey = 'children')
    {
        if (empty($phs)) {
            return;
        }

        $childrenOutput = array();
        foreach ($phs as $k => $v) {
            if (is_array($v) && !empty($v)) {
                $children = array();
                foreach ($v as $i => $j) {
                    if (is_array($j) && !empty($j)) {
                        if (!empty($docId)) {
                            array_walk($j, create_function('&$value,$key', '$value[\'docId\'] = '.$docId.';'));
                        }
                        $children[] = $this->parseRecursiveTpl($tplItem, $tplWrapper, $j, $docId);
                    }
                }
                $v[$childKey] = @implode('', $children);
            } else {
                $v[$childKey] = '';
            }

            /*
             * Start the parsing from here
             */
            if (!empty($docId)) {
                $v['docId'] = $docId;
            }
            $v                = $this->setPlaceholders($v, $this->config['phsPrefix']);
            $output           = $this->parseTpl($tplItem, $v);
            $childrenOutput[] = $this->processElementTags($output);
        }
        $childrenWrapper = array(
            $childKey.'.rows'  => @implode('', $childrenOutput),
            $childKey.'.count' => count($childrenOutput),
        );
        $childrenWrapper = $this->setPlaceholders($childrenWrapper, $this->config['phsPrefix']);

        $wrapperOutput = $this->parseTpl($tplWrapper, $childrenWrapper);

        return $wrapperOutput;
    }

    /**
     * If the chunk is called by AJAX processor, it needs to be parsed for the
     * other elements to work, like snippet and output filters.
     *
     * Example:
     * <pre><code>
     * <?php
     * $content = $myObject->parseTpl('tplName', $placeholders);
     * $content = $myObject->processElementTags($content);
     * </code></pre>
     *
     * @param string $content the chunk output
     * @param array  $options option for iteration
     *
     * @return string parsed content
     */
    public function processElementTags($content, array $options = array())
    {
        $maxIterations = intval($this->modx->getOption('parser_max_iterations', $options, 10));
        if (!$this->modx->parser) {
            $this->modx->getParser();
        }
        $this->modx->parser->processElementTags('', $content, true, false, '[[', ']]', array(), $maxIterations);
        $this->modx->parser->processElementTags('', $content, true, true, '[[', ']]', array(), $maxIterations);

        return $content;
    }

    /**
     * Replace the property's placeholders.
     *
     * @param string|array $subject Property
     *
     * @return array The replaced results
     */
    public function replacePropPhs($subject)
    {
        $pattern     = array(
            '/\{core_path\}/',
            '/\{base_path\}/',
            '/\{assets_url\}/',
            '/\{filemanager_path\}/',
            '/\[\[\+\+core_path\]\]/',
            '/\[\[\+\+base_path\]\]/',
        );
        $replacement = array(
            $this->modx->getOption('core_path'),
            $this->modx->getOption('base_path'),
            $this->modx->getOption('assets_url'),
            $this->modx->getOption('filemanager_path'),
            $this->modx->getOption('core_path'),
            $this->modx->getOption('base_path'),
        );
        if (is_array($subject)) {
            $parsedString = array();
            foreach ($subject as $k => $s) {
                if (is_array($s)) {
                    $s = $this->replacePropPhs($s);
                }
                $parsedString[$k] = preg_replace($pattern, $replacement, $s);
            }

            return $parsedString;
        } else {
            return preg_replace($pattern, $replacement, $subject);
        }
    }

    /**
     * Replacing MODX's getCount(), because it has bug on counting SQL with function.<br>
     * Retrieves a count of xPDOObjects by the specified xPDOCriteria.
     *
     * @param string $className Class of xPDOObject to count instances of.
     * @param mixed  $criteria  Any valid xPDOCriteria object or expression.
     *
     * @return int The number of instances found by the criteria.
     *
     * @see xPDO::getCount()
     * @link http://forums.modx.com/thread/88619/getcount-fails-if-the-query-has-aggregate-leaving-having-039-s-field-undefined The discussion for this
     */
    public function getQueryCount($className, $criteria = null)
    {
        $count = 0;
        if ($query = $this->modx->newQuery($className, $criteria)) {
            $expr = '*';
            if ($pk   = $this->modx->getPK($className)) {
                if (!is_array($pk)) {
                    $pk = array($pk);
                }
                $expr = $this->modx->getSelectColumns($className, 'alias', '', $pk);
            }
            $query->prepare();
            $sql  = $query->toSQL();
            $stmt = $this->modx->query("SELECT COUNT($expr) FROM ($sql) alias");
            if ($stmt) {
                $tstart = microtime(true);
                if ($stmt->execute()) {
                    $this->modx->queryTime += microtime(true) - $tstart;
                    ++$this->modx->executedQueries;
                    if ($results = $stmt->fetchAll(PDO::FETCH_COLUMN)) {
                        $count = reset($results);
                        $count = intval($count);
                    }
                } else {
                    $this->modx->queryTime += microtime(true) - $tstart;
                    ++$this->modx->executedQueries;
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '['.__CLASS__.'] Error '.$stmt->errorCode()." executing statement: \n".print_r($stmt->errorInfo(), true), '', __METHOD__, __FILE__, __LINE__);
                }
            }
        }

        return $count;
    }

    /**
     * Returns select statement for easy reading.
     *
     * @param xPDOQuery $query The query to print
     *
     * @return string The select statement
     *
     * @author Coroico <coroico@wangba.fr>
     */
    public function niceQuery(xPDOQuery $query = null)
    {
        $searched = array('SELECT', 'GROUP_CONCAT', 'LEFT JOIN', 'INNER JOIN', 'EXISTS', 'LIMIT', 'FROM',
            'WHERE', 'GROUP BY', 'HAVING', 'ORDER BY', 'OR', 'AND', 'IFNULL', 'ON', 'MATCH', 'AGAINST',
            'COUNT',);
        $replace  = array(" \r\nSELECT", " \r\nGROUP_CONCAT", " \r\nLEFT JOIN", " \r\nINNER JOIN", " \r\nEXISTS", " \r\nLIMIT", " \r\nFROM",
            " \r\nWHERE", " \r\nGROUP BY", " \r\nHAVING", ' ORDER BY', " \r\nOR", " \r\nAND", " \r\nIFNULL", " \r\nON", " \r\nMATCH", " \r\nAGAINST",
            " \r\nCOUNT",);
        $output   = '';
        if (isset($query)) {
            $query->prepare();
            $output = str_replace($searched, $replace, ' '.$query->toSQL());
        }

        return $output;
    }

    public function getMeetings($limit = 0, $start = 0)
    {
        $response = $this->server->getMeetings($limit, $start);
        if (empty($response)) { // The shared secret is wrong
            $err = 'Unable to connect to server';
            $this->setError($err);
            $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);

            return;
        }
        if (!isset($response['returncode']) || $response['returncode'] == 'FAILED') {
            $err = 'Unable to connect to server: '.$response['message'];
            $this->setError($err);

            return;
        }
        $meetings = $response['meetings'];
        if (empty($meetings)) {
            $err = 'No meetings available';
            $this->setError($err);

            return;
        }
        /*
         * If there is only 1 (one) meeting available, this returns associative
         * array of that meeting.
         * This below rewrites the array.
         */
        if (!isset($meetings['meeting'][0])) {
            $meetings['meeting'] = array($meetings['meeting']);
        }

        return $meetings['meeting'];
    }

    public function getJoinMeetingURL($meetingID, $password, $ctx = 'web')
    {
        if (empty($meetingID) || empty($password)) {
            $err = 'Unable to join to server: missing meetingID/password';
            $this->setError($err);
            $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);

            return;
        }
        if (!$this->modx->user->hasSessionContext('mgr') &&
                !$this->modx->user->isAuthenticated($ctx)
        ) {
            $err = 'Unable to join to server: unauthenticated user!';
            $this->setError($err);
            $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);

            return;
        }
        $profile     = $this->modx->user->getOne('Profile');
        $fullName    = $profile->get('fullname');
        $userName    = !empty($fullName) ? $fullName : $this->modx->user->get('username');
        $photo       = $profile->get('photo');
        $params      = array(
            'meetingID' => $meetingID,
            'fullName'  => $userName,
            'password'  => $password,
            'userID'    => $this->modx->user->get('id'),
            'avatarURL' => (!empty($photo) ? MODX_SITE_URL.$photo : ''),
        );
        $meetingInfo = $this->getMeetingInfo($meetingID);
        if ($meetingInfo) {
            $params['createTime'] = $meetingInfo['createTime'];
        }

        $c      = $this->modx->newQuery('bbbxConfigs');
        $c->leftJoin('bbbxMeetingsConfigs', 'MeetingsConfigs', 'MeetingsConfigs.config_id = bbbxConfigs.id');
        $c->select(array(
            'bbbxConfigs.id',
            'MeetingsConfigs.config_token',
        ));
        $c->where(array(
            'MeetingsConfigs.meeting_id' => $meetingID,
//            'bbbxConfigs.applied_to' => '' // @todo: moderator / attendee usergroup
        ));
        $config = $this->modx->getObject('bbbxConfigs', $c);
        if ($config) {
            $params['configToken'] = $config->get('config_token');
        }
        $response = $this->server->getJoinMeetingURL($params);
        if (empty($response) || (isset($response['returncode']) && $response['returncode'] == 'FAILED')) {
            $err = 'Unable to connect to server: '.$response['message'];
            $this->setError($err);

            return;
        }

        return $response;
    }

    public function initMeeting($meetingID)
    {
        $c       = $this->modx->newQuery('bbbxMeetings');
        $time    = time();
        $c->where(array(
            'meeting_id'    => $meetingID,
            'started_on:<=' => $time,
            'ended_on:>='   => $time,
        ));
        $meeting = $this->modx->getObject('bbbxMeetings', $c);
        if (!$meeting) {
            return false;
        }
        $meetingInfo = $this->getMeetingInfo($meetingID);
        if ($meetingInfo) {
            return $meetingInfo;
        }
        $meetingArray = $meeting->toArray();
        $duration     = 0;
        if (!empty($meetingArray['ended_on'])) {
            $duration = ($meetingArray['ended_on'] - $time) * 60;
        }
        $params = array(
            'name'                    => $meetingArray['name'],
            'meetingID'               => $meetingArray['meeting_id'],
            'attendeePW'              => $meetingArray['attendee_pw'],
            'moderatorPW'             => $meetingArray['moderator_pw'],
            'welcome'                 => $meetingArray['welcome'],
            'dialNumber'              => $meetingArray['dial_number'],
            'voiceBridge'             => $meetingArray['voice_bridge'],
            'webVoice'                => $meetingArray['web_voice'],
            'logoutURL'               => $meetingArray['logout_url'],
            'record'                  => (!empty($meetingArray['is_recorded']) ? 'true' : 'false'),
            'waitForModerator'        => (!empty($meetingArray['is_moderator_first']) ? 'true' : 'false'),
            'maxParticipants'         => $meetingArray['max_participants'],
            'duration'                => $duration,
            'moderatorOnlyMessage'    => $meetingArray['moderator_only_message'],
            'autoStartRecording'      => (!empty($meetingArray['auto_start_recording']) ? 'true' : 'false'),
            'allowStartStopRecording' => (!empty($meetingArray['allow_start_stop_recording']) ? 'true' : 'false'),
        );
        $meta   = array();
        if (isset($meetingArray['meta']) && !empty($meetingArray['meta'])) {
            $metaProp = array_map('trim', @explode(',', $meetingArray['meta']));
            foreach ($metaProp as $v) {
                list($key, $val) = @explode('=', $v);
                $meta[$key] = $val;
            }
        }
        $postFields = '';
        if (!empty($meetingArray['document_url'])) {
            $postFields .= '<modules><module name="presentation"><document url="'.$meetingArray['document_url'].'"/></module></modules>';
        }
        if (!empty($postFields)) {
            $postFields = '<?xml version="1.0" encoding="UTF-8"?>'.$postFields;
        }
        $postFields = trim($postFields);

        $response = $this->createMeeting($params, $meta, $postFields);
        if (empty($response) || (isset($response['returncode']) && $response['returncode'] == 'FAILED')) {
            $err = 'Unable to connect to server: '.$response['message'];
            $this->setError($err);

            return;
        }

        return true;
    }

    public function createMeeting($params, array $meta = array(), $postFields = '')
    {
        if (!isset($params['meetingID']) || empty($params['meetingID'])) {
            $params['meetingID'] = uniqid();
        }
        if (!isset($params['name']) || empty($params['name'])) {
            $params['name'] = 'BBBx-'.$params['meetingID'];
        }
        $meta     = array_merge($meta, array(
            'origin'               => 'MODX',
            'origin-url'           => MODX_SITE_URL,
            'origin-name'          => $this->modx->getOption('site_name'),
            'origin-extra'         => 'bbbx',
            'origin-extra-version' => $this->config['version'],
            'origin-extra-author'  => 'goldsky@virtudraft.com',
        ));
        $response = $this->server->createMeeting($params, $meta, $postFields);
        if (empty($response) || (isset($response['returncode']) && $response['returncode'] == 'FAILED')) {
            $err = 'Unable to connect to server: '.$response['message'];
            $this->setError($err);

            return;
        }

        return $response;
    }

    public function getEndMeetingURL($meetingID, $password, $ctx = 'web')
    {
        if (empty($meetingID) || empty($password)) {
            $err = 'Unable to join to server: missing meetingID/password';
            $this->setError($err);
            $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);

            return;
        }
        if (!$this->modx->user->hasSessionContext('mgr') &&
                !$this->modx->user->isAuthenticated($ctx)
        ) {
            $err = 'Unable to join to server: unauthenticated user!';
            $this->setError($err);
            $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);

            return;
        }

        return $this->server->getEndMeetingURL(array(
                    'meetingID' => $meetingID,
                    'password'  => $password,
        ));
    }

    public function endMeeting($meetingID, $password, $ctx = 'web')
    {
        if (empty($meetingID) || empty($password)) {
            $err = 'Unable to join to server: missing meetingID/password';
            $this->setError($err);
            $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);

            return;
        }
        if (!$this->modx->user->hasSessionContext('mgr') &&
                !$this->modx->user->isAuthenticated($ctx)
        ) {
            $err = 'Unable to join to server: unauthenticated user!';
            $this->setError($err);
            $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);

            return;
        }
        $response = $this->server->endMeeting(array(
            'meetingID' => $meetingID,
            'password'  => $password,
        ));
        if (empty($response) || (isset($response['returncode']) && $response['returncode'] == 'FAILED')) {
            $err = 'Unable to connect to server: '.$response['message'];
            $this->setError($err);

            return;
        }

        /*
         * Check
         */
        return !$this->isMeetingRunning($meetingID);
    }

    public function isMeetingRunning($meetingID)
    {
        $response = $this->server->isMeetingRunning($meetingID);
        if (empty($response) || (isset($response['returncode']) && $response['returncode'] == 'FAILED')) {
            $err = 'Unable to connect to server: '.$response['message'];
            $this->setError($err);

            return;
        }

        return (isset($response['running']) && $response['running'] !== 'false' ? true : false);
    }

    /**
     *
     * @param type $meetingID
     * @param type $moderatorPW
     * @return type
     */
    public function getMeetingInfo($meetingID, $moderatorPW = '')
    {
        $response = $this->server->getMeetingInfo(array(
            'meetingID'   => $meetingID,
            'moderatorPW' => $moderatorPW,
        ));
        if (empty($response) || (isset($response['returncode']) && $response['returncode'] == 'FAILED')) {
            $err = 'Unable to connect to server: '.$response['message'];
            $this->setError($err);

            return;
        }

        return $response;
    }

    public function getRecordings($meetingID = '', $limit = 0, $start = 0)
    {
        if (empty($meetingID)) {
            $params = array();
        } else {
            $params = array(
                'meetingID' => $meetingID,
            );
        }
        $response = $this->server->getRecordings($params);
        if (empty($response) || (isset($response['returncode']) && $response['returncode'] == 'FAILED')) {
            $err = 'Unable to connect to server: '.$response['message'];
            $this->setError($err);

            return;
        }
        $recordings = $response['recordings'];
        if (empty($recordings)) {
            return;
        }
        /*
         * If there is only 1 (one) recording available, this returns associative
         * array of that recording.
         * This below rewrites the array.
         */
        if (!isset($recordings['recording'][0])) {
            $recordings['recording'] = array($recordings['recording']);
        }

        return $recordings['recording'];
    }

    public function publishRecordings($recordID, $published, $ctx = 'web')
    {
        if (empty($recordID)) {
            $err = 'Unable to un/publish record(s): missing recordID';
            $this->setError($err);
            $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);

            return;
        }
        if (!$this->modx->user->hasSessionContext('mgr') &&
                !$this->modx->user->isAuthenticated($ctx)
        ) {
            $err = 'Unable to join to server: unauthenticated user!';
            $this->setError($err);
            $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);

            return;
        }

        $response = $this->server->publishRecordings(array(
            'recordID' => $recordID,
            'publish'  => $published,
        ));
        if (empty($response) || (isset($response['returncode']) && $response['returncode'] == 'FAILED')) {
            $err = 'Unable to connect to server: '.$response['message'];
            $this->setError($err);

            return;
        }

        return $response;
    }

    public function deleteRecordings($recordID, $ctx = 'web')
    {
        if (empty($recordID)) {
            $err = 'Unable to delete record(s): missing recordID';
            $this->setError($err);
            $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);

            return;
        }
        if (!$this->modx->user->hasSessionContext('mgr') &&
                !$this->modx->user->isAuthenticated($ctx)
        ) {
            $err = 'Unable to join to server: unauthenticated user!';
            $this->setError($err);
            $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);

            return;
        }

        $response = $this->server->deleteRecordings(array(
            'recordID' => $recordID,
        ));
        if (empty($response) || (isset($response['returncode']) && $response['returncode'] == 'FAILED')) {
            $err = 'Unable to connect to server: '.$response['message'];
            $this->setError($err);

            return;
        }

        return $response;
    }

    public function getDefaultConfigXML()
    {
        if (!$this->modx->user->hasSessionContext('mgr')
        ) {
            $err = 'Unable to join to server: unauthenticated user!';
            $this->setError($err);
            $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);

            return;
        }

        $response = $this->server->getDefaultConfigXML();
        if (empty($response) || (isset($response['returncode']) && $response['returncode'] == 'FAILED')) {
            $err = 'Unable to connect to server: '.$response['message'];
            $this->setError($err);

            return;
        }

        return $response;
    }

    public function setConfigXMLFromArray(array $input = array(), $rootName = 'root')
    {
        if (!$this->modx->user->hasSessionContext('mgr')
        ) {
            $err = 'Unable to join to server: unauthenticated user!';
            $this->setError($err);
            $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);

            return;
        }
        require_once dirname(dirname(__FILE__)).'vendors/array2xml/Array2XML.php';
        $xml = Array2XML::createXML($rootName, $input);

        return $xml->saveXML();
    }

    public function setConfigXML(array $params)
    {
        if (!$this->modx->user->hasSessionContext('mgr')
        ) {
            $err = 'Unable to join to server: unauthenticated user!';
            $this->setError($err);
            $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);

            return;
        }
        $this->server->setContentType('application/x-www-form-urlencoded');
        try {
            $response = $this->server->setConfigXML($params);
        } catch (Exception $e) {
            $err = '[setConfigXML] '.$e->getMessage();
            $this->setError($err);
            $this->modx->log(modX::LOG_LEVEL_ERROR, $err, '', __METHOD__, __FILE__, __LINE__);
            return;
        }
        if (empty($response) || (isset($response['returncode']) && $response['returncode'] == 'FAILED')) {
            $err = 'Unable to connect to server: '.$response['message'];
            $this->setError($err);

            return;
        }

        return $response;
    }

    /**
     * Check user permission to the meeting
     * @param int       $meetingId  BBB's meetingID, NOT the auto increment ID
     * @param string    $ctx        context key, default: web
     * @return boolean|string
     */
    public function getUserPermissionToMeeting($meetingId, $ctx = 'web')
    {
        if ($this->modx->user->hasSessionContext('mgr')) {
            return true;
        } else if (!empty($ctx) && !$this->modx->user->isAuthenticated($ctx)) {
            $err = 'Unable to join to server: unauthenticated user!';
            $this->setError($err);

            return;
        }
        $c       = $this->modx->newQuery('bbbxMeetings');
        $c->select(array(
            'bbbxMeetings.*'
        ));
        $c->leftJoin('bbbxMeetingsContexts', 'MeetingsContexts', 'MeetingsContexts.meeting_id = bbbxMeetings.id');
        $c->where(array(
            'bbbxMeetings.meeting_id'      => $meetingId,
        ));
        if (!empty($ctx)) {
            $c->where(array(
                'MeetingsContexts.context_key' => $ctx,
            ));
        }
        $meeting = $this->modx->getObject('bbbxMeetings', $c);
        if (!$meeting) {
            return false;
        }

        $ugs           = $this->modx->user->getUserGroups();
        $userId        = $this->modx->user->get('id');
        $isModerator   = array();
        $isViewer      = array();
        $meetingUgs    = $meeting->getMany('MeetingsUsergroups');
        $meetingsUsers = $meeting->getMany('MeetingsUsers');

        if (!$meetingUgs && !$meetingsUsers) {
            return 'moderator'; // no permission is applied
        }

        if ($meetingUgs) {
            $moderators = array();
            $viewers    = array();
            foreach ($meetingUgs as $meetingUg) {
                $meetingUgArray = $meetingUg->toArray();
                if ($meetingUgArray['enroll'] === 'moderator') {
                    $moderators[] = $meetingUgArray['usergroup_id'];
                } else if ($meetingUgArray['enroll'] === 'viewer') {
                    $viewers[] = $meetingUgArray['usergroup_id'];
                }
            }
            $isModerator = array_intersect($moderators, $ugs);
            if (!$isModerator) {
                $isViewer = array_intersect($viewers, $ugs);
            }
        }
        if (!$isModerator) {
            if ($meetingsUsers) {
                $moderators = array();
                $viewers    = array();
                foreach ($meetingsUsers as $meetingUser) {
                    $meetingUserArray = $meetingUser->toArray();
                    if ($meetingUserArray['enroll'] === 'moderator') {
                        $moderators[] = $meetingUserArray['user_id'];
                    } else if ($meetingUserArray['enroll'] === 'viewer') {
                        $viewers[] = $meetingUserArray['user_id'];
                    }
                }
                $isModerator = in_array($userId, $moderators);
                if (!$isModerator && !$isViewer) {
                    $isViewer = in_array($userId, $viewers);
                }
            }
        }
        if ($isModerator) {
            return 'moderator';
        } else if ($isViewer) {
            return 'viewer';
        } else if (!$meetingsUsers) {
            return 'viewer';
        }

        return false;
    }

}
