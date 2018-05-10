<?php
/*
echo 'The requested alias is: '.$_GET['product']['alias'].'<br>';
echo 'The requested product name is: '.$_GET['product']['name'].'<br>';
echo 'The requested product id is: '.$_GET['product']['id'].'<br>';
var_dump($_GET['product']['images']);
return;
*/

$commerceMultiLang = $modx->getService('commercemultilang', 'CommerceMultiLang', $modx->getOption('commercemultilang.core_path', null, $modx->getOption('core_path') . 'components/commercemultilang/') . 'model/commercemultilang/', $scriptProperties);
if (!($commerceMultiLang instanceof CommerceMultiLang))
    return;

return $commerceMultiLang->getProductDetail($scriptProperties);