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

        //$url = $this->commerce->adapter->makeResourceUrl(1);
        //$this->modx->log(1,$url);
        $this->modx->lexicon->load('commerce:default');
        $this->modx->addPackage('commercemultilang', $this->getOption('modelPath'));
        $this->modx->lexicon->load('commercemultilang:default');
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

    public function getProductList(array $scriptProperties = array()) {
        $c = $this->commerce->modx->newQuery('comProduct');
        $c->leftJoin('CommerceMultiLangProductData', 'ProductData', 'comProduct.id=ProductData.product_id');
        $c->leftJoin('CommerceMultiLangProductLanguage', 'ProductLanguage', 'comProduct.id=ProductLanguage.product_id');
        $c->where(array(
            'comProduct.removed'    =>  0,
            'ProductLanguage.lang_key'  =>  $this->modx->getOption('cultureKey')
        ));
        $c->select('comProduct.id,ProductData.alias,ProductLanguage.name,ProductLanguage.description');
        if ($c->prepare() && $c->stmt->execute()) {
            $products = $c->stmt->fetchAll(PDO::FETCH_ASSOC);

            $output = '';
            foreach ($products as $product) {
                // Grab images related to the product
                $q = $this->modx->newQuery('CommerceMultiLangProductImage');
                $q->where(array('product_id' => $product['id']));
                $q->select('CommerceMultiLangProductImage.*');
                if ($q->prepare() && $q->stmt->execute()) {
                    $product['images'] = $q->stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                //$this->modx->log(1,print_r($product,true));
                if($scriptProperties['tpl']) {
                    $output .= $this->modx->getChunk($scriptProperties['tpl'],$product);
                } else {
                    $output .= $this->modx->getChunk('product_preview_tpl',$product);
                }

            }
            if($scriptProperties['debug']) {
                return '<pre>'.print_r($products, true).'</pre>';
            }
            return $output;

        }
        return '';
    }
}