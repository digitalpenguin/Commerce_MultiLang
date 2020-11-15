<?php
/**
 * MultiLang Connector
 *
 * @package commerce_multilang
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('commerce_multilang.core_path', null, $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/commerce_multilang/');
$commerce_multilang = $modx->getService(
    'commerce_multilang',
    'Commerce_MultiLang',
    $corePath . 'model/commerce_multilang/',
    array(
        'core_path' => $corePath
    )
);

/* handle request */
$modx->request->handleRequest(
    array(
        'processors_path' => $commerce_multilang->getOption('processorsPath', null, $corePath . 'processors/'),
        'location' => '',
    )
);