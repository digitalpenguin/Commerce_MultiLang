<?php
/**
 * Event: onPageNotFound
 * Takes the request string and sets an alias GET param which is then passed to the resource viewport
 * A snippet can then use it to load data from the related product.
 */

// Grab xpdo instance with needed tables loaded
$commerceMultiLang = $modx->getService('commerce_multilang', 'Commerce_MultiLang', $modx->getOption('commerce_multilang.core_path', null, $modx->getOption('core_path') . 'components/commerce_multilang/') . 'model/commerce_multilang/', $scriptProperties);
if (!($commerceMultiLang instanceof Commerce_MultiLang))
    return '';
$xpdo = &$commerceMultiLang->modx;

$contextKey = $modx->context->get('key');
$errorUrl = $modx->makeUrl($modx->getOption('error_page'),$contextKey);

// Grabs request
$path = $_REQUEST['q'];
$requestArray = explode('/', $path);
$requestArray = array_reverse($requestArray);
$alias = $requestArray[0];

// If the request ends in a slash, it can't be a product.
if($alias == '') {
    $modx->sendRedirect($errorUrl);
};


// Gets id of the product resource viewport
$productDetailId = $modx->getOption('commerce_multilang.product_detail_page');
if($productDetailId) {

    // Grabs the extension type for documents then strips it from the alias
    $contentType = $modx->getObject('modContentType',array(
        'mime_type' => 'text/html'
    ));
    if(!$contentType) return;
    $extension = $contentType->get('file_extensions');
    if ($extension) {
        $alias = str_replace($extension, '', $alias);
    }

    //Comment this out if using experimental flat rows
    $c = $xpdo->newQuery('CMLProduct');
    $c->leftJoin('CMLProductData','ProductData','CMLProduct.id=ProductData.product_id');
    $c->leftJoin('CMLProductLanguage','ProductLanguage',array(
        'CMLProduct.id=ProductLanguage.product_id',
        'ProductLanguage.lang_key'  =>  $modx->getOption('cultureKey')
    ));
    $c->where(array('ProductData.alias'=>$alias));
    $c->select('CMLProduct.id,ProductData.alias,ProductLanguage.name,ProductLanguage.description');
    if ($c->prepare() && $c->stmt->execute()) {
        $product = $c->stmt->fetch(PDO::FETCH_ASSOC);
        if($product) {
            $_GET['product'] = $product;
            $modx->sendForward($productDetailId);
        } else {
            $modx->sendRedirect($errorUrl);
        }
    }

} else {
    $modx->sendRedirect($errorUrl);
}