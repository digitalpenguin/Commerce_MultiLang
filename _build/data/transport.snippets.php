<?php

$snippets = array();
$snippets[0] = $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => 'cml.getLanguageLinks',
    'description' => 'Displays links to translated products.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/getlanguagelinks.snippet.php'),
),'',true,true);
//$properties = include $sources['data'].'properties/properties.getlanguagelinks.php';
//$snippets[0]->setProperties($properties);
//unset($properties);

$snippets[1] = $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 0,
    'name' => 'cml.getProductList',
    'description' => 'Returns a list of product previews formatted with a chunk.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/getproductlist.snippet.php'),
),'',true,true);
//$properties = include $sources['data'].'properties/properties.getproductlist.php';
//$snippets[1]->setProperties($properties);
//unset($properties);

$snippets[2] = $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
    'id' => 0,
    'name' => 'cml.productDetail',
    'description' => 'Returns data for the product detail page.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/productdetail.snippet.php'),
),'',true,true);
//$properties = include $sources['data'].'properties/properties.getproductlist.php';
//$snippets[1]->setProperties($properties);
//unset($properties);

return $snippets;