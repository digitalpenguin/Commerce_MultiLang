<?php
$commerceMultiLang = $modx->getService('commerce_multilang', 'Commerce_MultiLang', $modx->getOption('commerce_multilang.core_path', null, $modx->getOption('core_path') . 'components/commerce_multilang/') . 'model/commerce_multilang/', $scriptProperties);
if (!($commerceMultiLang instanceof Commerce_MultiLang))
    return;
return $commerceMultiLang->getProductDetail($scriptProperties);