<?php
/**
 * Event: onPageNotFound
 * Takes the request string and sets an alias GET param which is then passed to the resource viewport
 * A snippet can then use it to load data from the related product.
 */

// Grab xpdo instance with needed tables loaded
$commerceMultiLang = $modx->getService('commercemultilang', 'CommerceMultiLang', $modx->getOption('commercemultilang.core_path', null, $modx->getOption('core_path') . 'components/commercemultilang/') . 'model/commercemultilang/', $scriptProperties);
if (!($commerceMultiLang instanceof CommerceMultiLang))
    return '';
$xpdo = &$commerceMultiLang->modx;

// Grabs request
$path = $_REQUEST['q'];
$requestArray = explode('/', $path);
$requestArray = array_reverse($requestArray);
$alias = $requestArray[0];
// If the request ends in a slash, it can't be a product.
if($alias == '') {
    return;
};

// Gets id of the product resource viewport
$productDetailId = $modx->getOption('commercemultilang.product_detail_page');
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
    $c = $xpdo->newQuery('comProduct');
    $c->leftJoin('CommerceMultiLangProductData','ProductData','comProduct.id=ProductData.product_id');
    $c->leftJoin('CommerceMultiLangProductLanguage','ProductLanguage',array(
        'comProduct.id=ProductLanguage.product_id',
        'ProductLanguage.lang_key'  =>  $modx->getOption('cultureKey')
    ));
    $c->where(array('ProductData.alias'=>$alias));
    $c->select('comProduct.id,ProductData.alias,ProductLanguage.name,ProductLanguage.description');
    if ($c->prepare() && $c->stmt->execute()) {
        $product = $c->stmt->fetch(PDO::FETCH_ASSOC);

        $q = $xpdo->newQuery('CommerceMultiLangProductImage');
        $q->where(array('product_id'=>$product['id']));
        if ($q->prepare() && $q->stmt->execute()) {
            $product['images'] = $q->stmt->fetch(PDO::FETCH_ASSOC);
        }

        if($product) {
            $_GET['product'] = $product;
            $modx->sendForward($productDetailId);
        } else {
            return;
        }
    }


    // Used for experimental flat row table
    /*$tableName = $xpdo->getTableName('CommerceMultiLangFlatRow');
    $stmt = $xpdo->query('SHOW COLUMNS FROM ' . $xpdo->escape($tableName));
    if ($stmt) {
        $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($fields) {
            //$xpdo->log(1,print_r($fields,true));
        }
    }*/

} else {
    return;
}