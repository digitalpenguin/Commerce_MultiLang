<?php
/**
 * @var modX $modx
 * @var array $scriptProperties
 */

use modmore\Commerce\Frontend\Checkout\Standard;
use modmore\Commerce\Frontend\Steps\Cart;

// Instantiate the Commerce class
$path = $modx->getOption('commerce.core_path', null, MODX_CORE_PATH . 'components/commerce/') . 'model/commerce/';
$params = ['mode' => $modx->getOption('commerce.mode')];
/** @var Commerce|null $commerce */
$commerce = $modx->getService('commerce', 'Commerce', $path, $params);
if (!($commerce instanceof Commerce)) {
    return '<p class="error">Oops! It is not possible to view your cart currently. We\'re sorry for the inconvenience. Please try again later.</p>';
}
$commerce->adapter->loadPackage('commercemultilang', $modx->getOption('commercemultilang.core_path').'model/');
$commerce->adapter->loadLexicon('commercemultilang:default');

if ($commerce->isDisabled()) {
    return $commerce->adapter->lexicon('commerce.mode.disabled.message');
}

$order = \comOrder::loadUserOrder($commerce);
// If we have a processing order, forget about it and get a new one
if ($order->getState() !== comOrder::STATE_CART) {
    $order->forgetOrderId();
    $order = comOrder::loadUserOrder($commerce);
}
$process = new Standard($commerce, $order);
$process->currentKey = 'cart';
$cartStep = new Cart($process, []);
$process->runStep($cartStep, array_merge($_GET, $_POST));

$response = $process->getResponse();

// If we requested the page via AJAX, we automatically return the JSON representation of the output.
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    $data = $response->toArray();
    $data['output'] = $commerce->adapter->parseMODXTags($data['output']);
    echo json_encode($data);
    @session_write_close();
    exit();
}

// Redirect? Off you go.
if ($redirect = $response->getRedirect()) {
    $response->sendRedirect();
}

// Register the default CSS if requested
if ($commerce->getBooleanOption('commerce.register_checkout_css')) {
    $modx->regClientCSS($commerce->config['assets_url'] . 'frontend/layout.simple.css');
    $modx->regClientCSS($commerce->config['assets_url'] . 'frontend/style.simple.css');
}

// Return the full markup output to the user
return $response->getOutput();