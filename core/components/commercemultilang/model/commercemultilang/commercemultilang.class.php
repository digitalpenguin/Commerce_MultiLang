<?php
/**
 * The main CommerceMultiLang service class.
 *
 * @package commercemultilang
 */
class CommerceMultiLang {
    public $modx = null;
    public $commerce = null;
    public $namespace = 'commercemultilang';
    public $cache = null;
    public $options = array();


    public function __construct(modX &$modx, array $options = array()) {
        $this->modx =& $modx;
        $this->namespace = $this->getOption('namespace', $options, 'commercemultilang');

        $corePath = $this->getOption('core_path', $options, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/commercemultilang/');
        $assetsPath = $this->getOption('assets_path', $options, $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/commercemultilang/');
        $assetsUrl = $this->getOption('assets_url', $options, $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/commercemultilang/');

        /* loads some default paths for easier management */
        $this->options = array_merge(array(
            'namespace' => $this->namespace,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'templatesPath' => $corePath . 'templates/',
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'connectorUrl' => $assetsUrl . 'connector.php'
        ), $options);

        $this->commerce = $this->modx->getService('commerce','Commerce',MODX_CORE_PATH.'components/commerce/model/commerce/');
        if (!($this->commerce instanceof Commerce)) $this->modx->log(1,'Couldn\'t load commerce');
        $this->commerce->adapter->loadLexicon('commerce:default');

        $this->commerce->adapter->loadPackage('commercemultilang', $this->getOption('modelPath'));
        $this->commerce->adapter->loadLexicon('commercemultilang:default');
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = array(), $default = null) {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}");
            }
        }
        return $option;
    }

    /**
     * Return a list of products
     *
     * @param array $scriptProperties
     * @return string
     */
    public function getProductList(array $scriptProperties = array()) {
        $contentType = $this->commerce->adapter->getObject('modContentType',array(
            'mime_type' => 'text/html'
        ));
        $extension = $contentType->get('file_extensions');

        $c = $this->commerce->adapter->newQuery('CommerceMultiLangProduct');
        $c->leftJoin('CommerceMultiLangProductData', 'ProductData', 'CommerceMultiLangProduct.id=ProductData.product_id');
        $c->leftJoin('CommerceMultiLangProductLanguage', 'ProductLanguage', 'CommerceMultiLangProduct.id=ProductLanguage.product_id');
        $c->leftJoin('CommerceMultiLangProductImage', 'ProductImage', array(
            'CommerceMultiLangProduct.id=ProductImage.product_id',
            'ProductImage.main' => 1
        ));
        $c->leftJoin('CommerceMultiLangProductImageLanguage', 'ProductImageLanguage', array(
            'ProductImage.id=ProductImageLanguage.product_image_id',
            'ProductImageLanguage.lang_key' =>  $this->modx->getOption('cultureKey')
        ));

        //$c->leftJoin('modResource','Category','ProductLanguage.category=Category.id');
        $c->where(array(
            'CommerceMultiLangProduct.removed'  => 0,
            'ProductData.product_listing'       => 1,
            'ProductLanguage.lang_key'          => $this->commerce->adapter->getOption('cultureKey'),
            'ProductLanguage.category'          => $this->modx->resource->get('id'), // only show products on the correct resource
        ));
        $c->select('CommerceMultiLangProduct.*,ProductData.alias,ProductLanguage.*,ProductImageLanguage.image');
        //$c->prepare();
        //echo $c->toSQL();
        if ($c->prepare() && $c->stmt->execute()) {
            $products = $c->stmt->fetchAll(PDO::FETCH_ASSOC);

            $output = '';
            if($products) {
                foreach ($products as $product) {
                    //Add current document extension to product
                    $product['extension'] = $extension;

                    $currentId = $this->modx->resource->get('id');
                    $url = $this->modx->makeUrl($currentId);
                    $product['product_link'] = $url . $product['alias'] . $extension;
                    if($product['image']) {
                        $product['image'] = '/' . $product['image'];
                    } else {
                        $product['image'] = $this->commerce->adapter->getOption('commercemultilang.assets_url').'img/placeholder.jpg';
                    }
                    //$this->modx->log(1,print_r($product,true));
                    if ($scriptProperties['tpl']) {
                        $output .= $this->modx->getChunk($scriptProperties['tpl'], $product);
                    } else {
                        $output .= $this->modx->getChunk('product_preview_tpl', $product);
                    }
                }
                if ($scriptProperties['debug']) {
                    return '<pre>' . print_r($products, true) . '</pre>';
                }
                return $output;
            } else {
                // If nothing found
                return '<p>There are no products listed in this category yet.</p>';
            }

        }
        return '';
    }

    /**
     * Retrieves product information
     *
     * @param array $scriptProperties
     * @return bool|string
     */
    public function getProductDetail($scriptProperties = array()) {
        $output = '';
        if(!$productId = intval($_GET['product']['id'])) {
            return false;
        }
        $c = $this->modx->newQuery('CommerceMultiLangProduct');
        $c->leftJoin('CommerceMultiLangProductData','ProductData','ProductData.product_id=CommerceMultiLangProduct.id');
        $c->leftJoin('CommerceMultiLangProductLanguage','ProductLanguage',array(
            'ProductLanguage.product_id=CommerceMultiLangProduct.id',
            'ProductLanguage.lang_key'=>$this->modx->getOption('cultureKey')
        ));
        $c->leftJoin('CommerceMultiLangProductImage','ProductImage',array(
            'ProductImage.product_id=CommerceMultiLangProduct.id'
        ));
        $c->leftJoin('CommerceMultiLangProductImageLanguage','ProductImageLanguage',array(
            'ProductImageLanguage.product_image_id=ProductImage.id'
        ));
        $c->where(array('CommerceMultiLangProduct.id'=>$productId));
        $c->select(
            'CommerceMultiLangProduct.*,
                    ProductData.alias,
                    ProductLanguage.name,
                    ProductLanguage.description,
                    ProductImageLanguage.title,
                    ProductImageLanguage.alt,
                    ProductImageLanguage.image'
        );
        //$c->prepare();
        //$this->modx->log(1,$c->toSQL());

        if ($c->prepare() && $c->stmt->execute()) {
            $product = $c->stmt->fetch(PDO::FETCH_ASSOC);
            $variations = $this->getProductVariationFields($product['id'],$scriptProperties);
            //$this->modx->log(1,$variations);
            if($variations) {
                $product['variations'] = $variations;
            }
            if($product['image']) {
                $product['image'] = '/' . $product['image'];
            } else {
                $product['image'] = $this->commerce->adapter->getOption('commercemultilang.assets_url').'img/placeholder.jpg';

            }
            $output =  $this->modx->getChunk('product_detail_tpl',$product);

            //$this->modx->log(1,$output);
        }
        return $output;
    }


    /**
     * @param int $parentProductId
     * @param array $scriptProperties
     * @return string
     */
    public function getProductVariationFields($parentProductId,array $scriptProperties) {
        $output = '';
        $c = $this->commerce->adapter->newQuery('CommerceMultiLangProduct');
        $c->leftJoin('CommerceMultiLangProductData','ProductData','ProductData.product_id=CommerceMultiLangProduct.id');
        $c->leftJoin('CommerceMultiLangProductLanguage','ProductLanguage',array(
            'ProductLanguage.product_id=CommerceMultiLangProduct.id',
            'ProductLanguage.lang_key'=>$this->modx->getOption('cultureKey')
        ));
        $c->select('CommerceMultiLangProduct.id,ProductData.type');
        $c->where([
            'CommerceMultiLangProduct.removed:!='       =>  1,
            [
                'AND:ProductData.parent:='              =>  $parentProductId,
                'OR:CommerceMultiLangProduct.id:='      =>  $parentProductId
            ]

        ]);
        //$c->prepare();
        //$this->modx->log(1,$c->toSQL());
        if ($c->prepare() && $c->stmt->execute()) {
            $productArray = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
            $productIds = [];
            foreach($productArray as $product) {
                $productIds[] = $product['id'];
            }
            $v = $this->commerce->adapter->newQuery('CommerceMultiLangAssignedVariation');
            $v->select(['id','product_id','type_id','variation_id','name','lang_key','value']);

            $v->where([
                'product_id:IN'     =>  $productIds,
                'lang_key'          =>  $this->commerce->adapter->getOption('cultureKey'),
                'type_id'           =>  $productArray[0]['type']
            ]);
            //$v->prepare();
            //$this->modx->log(1,$v->toSQL());
            if ($v->prepare() && $v->stmt->execute()) {
                $variations = $v->stmt->fetchAll(PDO::FETCH_ASSOC);
                $variations = $this->mergeVariationsByProductId($variations,$scriptProperties);
                foreach($variations as $variation) {
                    if($scriptProperties['variationTpl']) {
                        $output .= $this->modx->getChunk($scriptProperties['variationTpl'],$variation);
                    } else {
                        $output .= $this->modx->getChunk('variation_row_tpl',[
                            'variation' =>  $variation['value'],
                            'variation_product_id'  => $variation['product_id']
                        ]);
                    }
                }
            }
        }
        return $output;
    }

    /**
     * Merge array of different variation values together by common product_id
     * @param array $variations
     * @param array $scriptProperties
     * @return array
     */
    public function mergeVariationsByProductId(array $variations,array $scriptProperties) {
        // Duplicate the array of variations to compare with each other.
        //$this->modx->log(1,print_r($variations,true));
        $duplicateArr = $variations;
        $nameKey = '';
        $idx = 0;
        $final = [];
        foreach($variations as $variation) {
            // Set the initial name on first iteration
            if($idx === 0) {
                $nameKey = $variation['name'];
            }
            // If another name comes is first, it's a repeat so skip it.
            if($nameKey!=$variation['name']) {
                continue;
            }
            $new = [];
            //$this->modx->log(1,print_r($variation,true));
            $new['product_id']    =   $variation['product_id'];
            $new['variation_id']  =   $variation['variation_id'];

            // Only show variation names if it's requested in the variationNames parameter.
            if($scriptProperties['variationNames']) {
                $new['value'] = ucfirst($variation['name']) . ': ' . $variation['value'];
            } else {
                $new['value'] = $variation['value'];
            }
            $new['lang_key']      =   $variation['lang_key'];

            foreach($duplicateArr as $arr) {
                if(($variation['product_id'] === $arr['product_id']) && ($variation['variation_id'] != $arr['variation_id'])) {
                    $new['variation_id']  .=   ', '.$arr['variation_id'];
                    // Only show variation names if it's requested in the variationNames parameter.
                    if($scriptProperties['variationNames']) {
                        $new['value'] .= ', ' . ucfirst($arr['name']) . ': ' . $arr['value'];
                    } else {
                        $new['value'] .= ', ' . $arr['value'];
                    }
                    //$this->modx->log(1,print_r($new,true));
                }
            }
            $final[] = $new;
            $idx++;
        }
        //$this->modx->log(1,print_r($final,true));
        return $final;
    }

    /**
     * Returns array of active context keys with associated languages
     * @return array
     */
    public function getLanguages() {
        $languages = array();
        $c = $this->commerce->adapter->newQuery('modContext');
        $c->leftJoin('modContextSetting','ContextSettings','modContext.key=ContextSettings.context_key');
        $c->select('modContext.key,modContext.name,ContextSettings.key as setting_key,ContextSettings.value as lang_key');
        $c->where(array(
            'modContext.key:!=' => 'mgr',
            'AND:ContextSettings.key:=' => 'cultureKey'
        ));
        if ($c->prepare() && $c->stmt->execute()) {
            $contexts = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($contexts as $context) {
                $v = $this->commerce->adapter->newQuery('modContextSetting');
                $v->where(array(
                    'context_key:='   =>  $context['key'],
                    'AND:key:='       =>  'commercemultilang.product_detail_page'
                ));
                $v->select('modContextSetting.key,modContextSetting.value');
                if ($v->prepare() && $v->stmt->execute()) {
                    $setting = $v->stmt->fetch(PDO::FETCH_ASSOC);
                    $lang = array();
                    $lang['context_key'] = $context['key'];
                    $lang['lang_key'] = $context['lang_key'];
                    $lang['name'] = $context['name'];
                    $lang['viewport'] = $setting['value'];
                    array_push($languages, $lang);
                }
            }
        }
        return $languages;
    }

}