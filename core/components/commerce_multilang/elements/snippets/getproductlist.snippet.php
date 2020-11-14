<?php
$commerceMultiLang = $modx->getService('commerce_multilang', 'MultiLang', $modx->getOption('commerce_multilang.core_path', null, $modx->getOption('core_path') . 'components/commerce_multilang/') . 'model/commerce_multilang/', $scriptProperties);
if (!($commerceMultiLang instanceof CommerceMultiLang))
    return '';

return $commerceMultiLang->getProductList($scriptProperties);