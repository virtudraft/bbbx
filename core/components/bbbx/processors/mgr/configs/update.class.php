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
 * @subpackage processor
 */
class ConfigsUpdateProcessor extends modObjectUpdateProcessor
{

    public $object;
    public $classKey        = 'bbbxConfigs';
    public $primaryKeyField = 'id';
    public $permission      = '';
    public $languageTopics  = array('bbbx:cmp');
    public $objectType      = 'bbbx.ConfigsUpdate';
    private $default        = array();

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $name = $this->getProperty('name');
        if (empty($name)) {
            return $this->modx->lexicon('bbbx.config_err_ns_name');
        }
        $configs = $this->getProperty('configs');
        if (empty($configs)) {
            return $this->modx->lexicon('bbbx.config_err_ns_configs');
        }
        $this->unsetProperty('action');

        return parent::initialize();
    }

    /**
     * Override in your derivative class to do functionality before the fields are set on the object
     * @return boolean
     */
    public function beforeSet()
    {
        $props         = $this->getProperties();
        $this->default = json_decode($props['default'], true);
        unset($props['default']);
        $this->unsetProperty('default');
        /**
         * Cleaning first
         */
        $configs       = array();
        foreach ($props['configs'] as $k => $v) {
            if ($k === 'modules') {
                /**
                 *
                 */
                $checkChanges = array();
                $i            = 0;
                foreach ($v as $modName => $vals) {
                    $checkChanges = array();
                    foreach ($vals['@attributes'] as $x => $y) {
                        $y = trim($y);
                        if ($x === 'name') {
                            continue;
                        }
                        if (empty($y)) {
                            continue;
                        }
                        $checkChanges[$x] = $y;
                    }
                    if (!empty($checkChanges)) {
                        $innerChanges = array();
                        foreach ($vals['@attributes'] as $x => $y) {
                            $y = trim($y);
                            if ($x === 'name') {
                                continue;
                            }
                            if (empty($y)) {
                                continue;
                            }
                            $checkDefaultModConfs = $this->getModuleConfigs($vals['@attributes']['name']);

                            if (empty($checkDefaultModConfs)) {
                                continue;
                            }
                            if ($checkDefaultModConfs['@attributes'][$x] === $y) {
                                continue;
                            }
                            $innerChanges[$x] = $y;
                        }
                        if (!empty($innerChanges)) {
                            if (!isset($configs['modules'])) {
                                $configs['modules'] = array();
                            }
                            if (!isset($configs['modules']['module'])) {
                                $configs['modules']['module'] = array();
                            }
                            $vals['@attributes']              = $this->mergeValues($vals['@attributes'], $checkDefaultModConfs['@attributes']);
                            $configs['modules']['module'][$i] = $vals;
                        }
                    }
                    ++$i;
                }

                continue;
            }
            foreach ($v['@attributes'] as $x => $y) {
                $y = trim($y);
                if (empty($y)) {
                    continue;
                }
                if (isset($this->default[$k]['@attributes'][$x]) && $this->default[$k]['@attributes'][$x] === $y) {
                    continue;
                }
                $configs[$k]['@attributes'][$x] = $y;
            }
        }
        $this->unsetProperty('configs');
        if (empty($configs)) {
            return $this->modx->lexicon('bbbx.config_err_no_changes');
        }
        require_once $this->modx->bbbx->config['corePath'].'vendors/array2xml/Array2XML.php';
        $array2xml = Array2XML::createXML('config', $configs);
        $xml       = $array2xml->saveXML();
        $xml       = preg_replace('/>\s+</', '><', $xml);
        $xml       = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $xml);
        $this->setProperty('xml', $xml);

        return !$this->hasErrors();
    }

    private function getModuleConfigs($modName)
    {
        $count = count($this->default['modules']['module']);
        for ($i = 0; $i < $count; ++$i) {
            if ($modName === $this->default['modules']['module'][$i]['@attributes']['name']) {
                return $this->default['modules']['module'][$i];
            }
        }

        return;
    }

    private function mergeValues(array $array1, array $array2)
    {
        $output = array();
        foreach ($array1 as $k => $v) {
            if (empty($v)) {
                $output[$k] = $array2[$k];
            } else {
                $output[$k] = $v;
            }
        }

        return $output;
    }

}

return 'ConfigsUpdateProcessor';
