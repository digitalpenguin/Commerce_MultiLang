<?php

// Small redirect plugin on page not found

$path = $_REQUEST['q'];
$prodpage = explode('/', $path);
$prodpage = array_reverse($prodpage);
$productsAliasArray = explode(',', $modx->getOption('productdb_product-alias'));
$productDetailId = $modx->getOption('productdb_product-detail-id',null,false);

if (!in_array($prodpage[2],$productsAliasArray)){
    return;
} elseif(stripos($prodpage[0],'.html') == true && $productDetailId != false) {
    $_GET['alias'] = str_replace('.html','',$prodpage[0]);
    $modx->sendForward($productDetailId);
    return;
} else {
    return;
}