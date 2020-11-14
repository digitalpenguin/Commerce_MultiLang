<?php

$chunks = array();
$chunks[0] = $modx->newObject('modChunk');
$chunks[0]->fromArray(array(
    'id' => 0,
    'name' => 'cml_language_links',
    'description' => 'Displays links to translated products.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/languagelinks.chunk.tpl'),
),'',true,true);
//$properties = include $sources['data'].'properties/properties.getlanguagelinks.php';
//chunks[0]->setProperties($properties);
//unset($properties);

$chunks[1] = $modx->newObject('modChunk');
$chunks[1]->fromArray(array(
    'id' => 0,
    'name' => 'cml_product_preview',
    'description' => 'Returns a list of product previews formatted with a chunk.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/productpreview.chunk.tpl'),
),'',true,true);
//$properties = include $sources['data'].'properties/properties.getproductlist.php';
//chunks[1]->setProperties($properties);
//unset($properties);

$chunks[2] = $modx->newObject('modChunk');
$chunks[2]->fromArray(array(
    'id' => 0,
    'name' => 'cml_product_detail',
    'description' => 'Returns a data for the product detail page.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/productdetail.chunk.tpl'),
),'',true,true);
//$properties = include $sources['data'].'properties/properties.getproductlist.php';
//chunks[1]->setProperties($properties);
//unset($properties);

$chunks[3] = $modx->newObject('modChunk');
$chunks[3]->fromArray(array(
    'id' => 0,
    'name' => 'cml_variation_row',
    'description' => 'Chunk used for formatting rows with product variation output.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/variationrow.chunk.tpl'),
),'',true,true);
//$properties = include $sources['data'].'properties/properties.getproductlist.php';
//chunks[1]->setProperties($properties);
//unset($properties);

return $chunks;