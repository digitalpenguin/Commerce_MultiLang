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

    /**
     * Return a list of products
     *
     * @param array $scriptProperties
     * @return string
     */
    public function getProductList(array $scriptProperties = array()) {
        $contentType = $this->modx->getObject('modContentType',array(
            'mime_type' => 'text/html'
        ));
        $extension = $contentType->get('file_extensions');

        $c = $this->commerce->modx->newQuery('CommerceMultiLangProduct');
        $c->leftJoin('CommerceMultiLangProductData', 'ProductData', 'CommerceMultiLangProduct.id=ProductData.product_id');
        $c->leftJoin('CommerceMultiLangProductLanguage', 'ProductLanguage', 'CommerceMultiLangProduct.id=ProductLanguage.product_id');
        $c->innerJoin('CommerceMultiLangProductImage', 'ProductImage', array(
            'CommerceMultiLangProduct.id=ProductImage.product_id',
            'ProductImage.main' => 1
        ));
        $c->innerJoin('CommerceMultiLangProductImageLanguage', 'ProductImageLanguage', array(
            'ProductImage.id=ProductImageLanguage.product_image_id',
            'ProductImageLanguage.lang_key' =>  $this->modx->getOption('cultureKey')
        ));

        //$c->leftJoin('modResource','Category','ProductLanguage.category=Category.id');
        $c->where(array(
            'CommerceMultiLangProduct.removed'  => 0,
            'ProductData.product_listing'       => 1,
            'ProductLanguage.lang_key'          => $this->modx->getOption('cultureKey'),
            'ProductLanguage.category'          => $this->modx->resource->get('id'), // only show products on the correct resource
        ));
        $c->select('CommerceMultiLangProduct.id,ProductData.alias,ProductLanguage.*,ProductImageLanguage.image');
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
                    $product['image'] = '/' . $product['image'];
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


    public function getProductDetail() {
        return '';
    }

    /**
     * Returns array of active context keys with associated languages
     * @return array
     */
    public function getLanguages() {
        $languages = array();
        $c = $this->modx->newQuery('modContext');
        $c->leftJoin('modContextSetting','ContextSettings','modContext.key=ContextSettings.context_key');
        $c->select('modContext.key,modContext.name,ContextSettings.key as setting_key,ContextSettings.value as lang_key');
        $c->where(array(
            'modContext.key:!=' => 'mgr',
            'AND:ContextSettings.key:=' => 'cultureKey'
        ));
        if ($c->prepare() && $c->stmt->execute()) {
            $contexts = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($contexts as $context) {
                $v = $this->modx->newQuery('modContextSetting');
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