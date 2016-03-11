<?php

require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';
$corePath = $modx->getOption('bbbx.core_path', null, $modx->getOption('core_path') . 'components/bbbx/');
require_once $corePath . 'model/bbbx.class.php';
$modx->bbbx = new BBBx($modx);
$modx->lexicon->load('bbbx:default');
/* handle request */
$path = $modx->getOption('processorsPath', $modx->bbbx->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));
