<?php
/**
 * The main Commerce_MultiLang service class.
 *
 * @package commerce_multilang
 */
class Commerce_MultiLang {
    public $modx = null;
    public $commerce = null;
    public $namespace = 'commerce_multilang';
    public $cache = null;
    public $options = array();
    public $sortby = 'CMLProduct.id';
    public $sortdir = 'ASC';
    public $limit = '30';


    public function __construct(modX &$modx, array $options = array()) {
        $this->modx =& $modx;
        $this->namespace = $this->getOption('namespace', $options, 'commerce_multilang');

        $corePath = $this->getOption('core_path', $options, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/commerce_multilang/');
        $assetsPath = $this->getOption('assets_path', $options, $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/commerce_multilang/');
        $assetsUrl = $this->getOption('assets_url', $options, $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/commerce_multilang/');
        $baseImageUrl = $this->getOption('base_image_url',$options,'/');

        // Load some default paths
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
            'connectorUrl' => $assetsUrl . 'connector.php',
            'baseImageUrl'    =>  $baseImageUrl
        ), $options);

        $this->commerce = $this->modx->getService('commerce','Commerce',MODX_CORE_PATH.'components/commerce/model/commerce/');
        if (!($this->commerce instanceof Commerce)) $this->modx->log(MODX_LOG_LEVEL_ERROR,'Couldn\'t load Commerce service!');
        $this->commerce->adapter->loadLexicon('commerce:default');

        $this->commerce->adapter->loadPackage('commerce_multilang', $this->getOption('modelPath'));
        $this->commerce->adapter->loadLexicon('commerce_multilang:default');
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
     * Snippet Params:
     * - tpl : set a chunk name to use for the template
     * - debug : set to 1 to see data output
     * - limit : max number of items returned
     * - sortby : field to sort items by
     * - sortdir : ASC or DESC
     *
     * @param array $scriptProperties
     * @return string
     */
    public function getProductList(array $scriptProperties = array()) {
        // Check for sortdir param
        if(array_key_exists('sortdir',$scriptProperties)) {
            if($scriptProperties['sortdir']) {
                $this->sortdir = $scriptProperties['sortdir'];
            }
        }
        // Check for sortby param
        if(array_key_exists('sortby',$scriptProperties)) {
            if($scriptProperties['sortby']) {
                $this->sortby = $scriptProperties['sortby'];
            }
        }
        // Check for limit param
        if(array_key_exists('limit',$scriptProperties)) {
            if($scriptProperties['limit']) {
                $this->limit = $scriptProperties['limit'];
            }
        }

        $categoryIds = [];
        if ($scriptProperties['categories']) {
            $categoryIds = explode(',', $scriptProperties['categories']);
        } else {
            $categoryIds[] = $this->modx->resource->get('id');
        }

        $contentType = $this->commerce->adapter->getObject('modContentType',array(
            'mime_type' => 'text/html'
        ));
        if($contentType) {
            $extension = $contentType->get('file_extensions');
        }

        $c = $this->commerce->adapter->newQuery('CMLProduct');
        $c->leftJoin('CMLProductData', 'ProductData', 'CMLProduct.id=ProductData.product_id');
        $c->leftJoin('CMLProductLanguage', 'ProductLanguage', 'CMLProduct.id=ProductLanguage.product_id');
        $c->leftJoin('CMLProductImage', 'ProductImage', array(
            'CMLProduct.id=ProductImage.product_id',
            'ProductImage.main' => 1
        ));
        $c->leftJoin('CMLProductImageLanguage', 'ProductImageLanguage', array(
            'ProductImage.id=ProductImageLanguage.product_image_id',
            'ProductImageLanguage.lang_key' =>  $this->modx->getOption('cultureKey')
        ));
        //$c->leftJoin('modResource','Category','ProductLanguage.category=Category.id');

        if($c->sortby == 'RAND()') {
            $c->sortby('RAND()');
        } else {
            $c->sortby($this->sortby, $this->sortdir);
        }
        $c->limit($this->limit);
        $c->where(array(
            'CMLProduct.removed:='    => 0,
            'AND:ProductData.product_listing:='     => 1,
            'AND:ProductLanguage.lang_key:='        => $this->commerce->adapter->getOption('cultureKey'),
            'AND:ProductLanguage.category:IN'       => $categoryIds, // only show products on the correct resources
        ));
        $c->select('CMLProduct.*,
                    ProductData.alias,
                    ProductLanguage.*,
                    ProductImageLanguage.image');
        //$c->prepare();
        //echo $c->toSQL();
        if ($c->prepare() && $c->stmt->execute()) {
            $products = $c->stmt->fetchAll(PDO::FETCH_ASSOC);

            $output = '';
            if($products) {
                foreach ($products as $product) {
                    //Add current document extension to product
                    $product['extension'] = $extension;

                    $url = $this->modx->makeUrl($product['category']);
                    $product['product_link'] = $url .'/'. $product['alias'] . $extension;
                    if($product['image']) {
                        $uri = ltrim($this->options['baseImageUrl'], '/');
                        $product['image'] = '/' . $uri . $product['image'];
                    } else {
                        $product['image'] = $this->commerce->adapter->getOption('commerce_multilang.assets_url').'img/placeholder.jpg';
                    }

                    $this->modx->setPlaceholders($product,'cml.list.');
                    if ($scriptProperties['tpl']) {
                        $output .= $this->modx->getChunk($scriptProperties['tpl']);
                    } else {
                        $output .= $this->modx->getChunk('cml_product_preview');
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
        $c = $this->modx->newQuery('CMLProduct');
        $c->leftJoin('CMLProductData','ProductData','ProductData.product_id=CMLProduct.id');
        $c->leftJoin('CMLProductLanguage','ProductLanguage',array(
            'ProductLanguage.product_id=CMLProduct.id',
            'ProductLanguage.lang_key'=>$this->modx->getOption('cultureKey')
        ));
        $c->leftJoin('CMLProductImage','ProductImage',array(
            'ProductImage.product_id=CMLProduct.id',
            'ProductImage.main' =>  true
        ));
        $c->leftJoin('CMLProductImageLanguage','ProductImageLanguage',array(
            'ProductImageLanguage.product_image_id=ProductImage.id'
        ));
        $c->where(array('CMLProduct.id'=>$productId));
        $c->select(
            'CMLProduct.*,
                    ProductData.alias,
                    ProductLanguage.name,
                    ProductLanguage.description,
                    ProductLanguage.content,
                    ProductLanguage.category,
                    ProductImageLanguage.title,
                    ProductImageLanguage.alt,
                    ProductImageLanguage.image'
        );
        //$c->prepare();
        //$this->modx->log(1,$c->toSQL());

        if ($c->prepare() && $c->stmt->execute()) {
            $product = $c->stmt->fetch(PDO::FETCH_ASSOC);
            if ($product) {
                $variations = $this->getProductVariationFields($product['id'], $scriptProperties);
                if ($variations) {
                    $product['variations'] = $variations;
                }
                if ($product['image']) {
                    $uri = ltrim($this->options['baseImageUrl'], '/');
                    $product['image'] = '/' . $uri . $product['image'];
                    $product['image_nr'] = ltrim($product['image'], '/');
                } else {
                    $product['image'] = $this->commerce->adapter->getOption('commerce_multilang.assets_url') . 'img/placeholder.jpg';
                }

                // Set link to primary category as placeholder
                // TODO: Change the way this is handled when multiple categories are added.
                if($product['category']) {
                    $product['category_link'] = $this->modx->makeUrl($product['category']);
                }

                // Checks for file extension and sets placeholder
                $contentType = $this->modx->getObject('modContentType',array(
                    'mime_type' => 'text/html'
                ));
                if($contentType) {
                    $extension = $contentType->get('file_extensions');
                    if ($extension) {
                        $product['alias_ext'] = $extension;
                    }
                }
                $this->modx->setPlaceholders($product,'cml.');
                if ($scriptProperties['tpl']) {
                    $output = $this->modx->getChunk($scriptProperties['tpl']);
                } else {
                    $output = $this->modx->getChunk('cml_product_detail');
                }

            }
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
        // Gets primary product and children
        $c = $this->commerce->adapter->newQuery('CMLProduct');
        $c->leftJoin('CMLProductData','ProductData','ProductData.product_id=CMLProduct.id');
        $c->leftJoin('CMLProductLanguage','ProductLanguage',array(
            'ProductLanguage.product_id=CMLProduct.id',
            'ProductLanguage.lang_key'=>$this->modx->getOption('cultureKey')
        ));
        $c->select('CMLProduct.id,ProductData.type');
        $c->where([
            'CMLProduct.removed:!='       =>  1,
            [
                'AND:ProductData.parent:='              =>  $parentProductId,
                'OR:CMLProduct.id:='      =>  $parentProductId
            ]

        ]);
        //$c->prepare();
        //$this->modx->log(1,$c->toSQL());
        if ($c->prepare() && $c->stmt->execute()) {
            $productArray = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
            $productIds = [];
            // Collect the product ids into an array
            foreach($productArray as $product) {
                $productIds[] = $product['id'];
            }
            // Get assigned variations for current language if the product ID is in the array
            $v = $this->commerce->adapter->newQuery('CMLAssignedVariation');
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
                        $output .= $this->modx->getChunk('cml_variation',[
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
            if($new['value']) {
                $final[] = $new;
            }
            $idx++;
        }
        //$this->modx->log(1,'|'.print_r($final,true).'|');
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
        $c->select('modContext.key,modContext.name,modContext.rank,ContextSettings.key as setting_key,ContextSettings.value as lang_key');
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
                    'AND:key:='       =>  'commerce_multilang.product_detail_page'
                ));
                $v->select('modContextSetting.key,modContextSetting.value');
                if ($v->prepare() && $v->stmt->execute()) {
                    $setting = $v->stmt->fetch(PDO::FETCH_ASSOC);
                    $lang = array();
                    $lang['context_key'] = $context['key'];
                    $lang['lang_key'] = $context['lang_key'];
                    $lang['name'] = $context['name'];
                    $lang['viewport'] = $setting['value'];
                    $lang['context_rank'] = $context['rank'];
                    array_push($languages, $lang);
                }
            }
        }
        return $languages;
    }

}