<?php
/**
 * @package commerce_multilang
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/cmlproductdata.class.php');
class CMLProductData_mysql extends CMLProductData {}
?>