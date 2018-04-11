<?php
/**
 * CommerceMultiLang Connector
 *
 * @package commercemultilang
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('commercemultilang.core_path', null, $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/commercemultilang/');
$commercemultilang = $modx->getService(
    'commercemultilang',
    'CommerceMultiLang',
    $corePath . 'model/commercemultilang/',
    array(
        'core_path' => $corePath
    )
);

/* handle request */
$modx->request->handleRequest(
    array(
        'processors_path' => $commercemultilang->getOption('processorsPath', null, $corePath . 'processors/'),
        'location' => '',
    )
);