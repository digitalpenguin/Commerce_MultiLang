<?php
/**
 * @package commerce_multilang
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/cmlproductlanguage.class.php');
class CMLProductLanguage_mysql extends CMLProductLanguage {}
?>