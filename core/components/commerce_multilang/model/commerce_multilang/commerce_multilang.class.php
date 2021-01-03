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
     * Returns array of active context keys with associated languages
     * @return array
     */
    public function getLanguages() {
        $languages = [];
        $c = $this->commerce->adapter->newQuery('modContext');
        $c->leftJoin('modContextSetting','ContextSettings','modContext.key=ContextSettings.context_key');
        $c->select('modContext.key,modContext.name,modContext.rank,ContextSettings.key as setting_key,ContextSettings.value as lang_key');
        $c->where([
            'modContext.key:!=' => 'mgr',
            'AND:ContextSettings.key:=' => 'cultureKey'
        ]);
        $c->sortby('modContext.rank','ASC');
        if ($c->prepare() && $c->stmt->execute()) {
            $contexts = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($contexts as $context) {
                $v = $this->commerce->adapter->newQuery('modContextSetting');
                $v->where([
                    'context_key:='   =>  $context['key']
                ]);
                $v->select('modContextSetting.key,modContextSetting.value');
                if ($v->prepare() && $v->stmt->execute()) {
                    $setting = $v->stmt->fetch(PDO::FETCH_ASSOC);
                    $lang = [];
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

    /**
     * Returns an array of lang_key values that are not the default language
     *
     * @return array
     */
    public function getLanguageKeys() {
        $languages = $this->getLanguages();
        $langKeys = [];
        foreach($languages as $language) {
            $langKeys[] = $language['lang_key'];
        }
        // Remove default lang as that's already covered by the base product.
        if (($key = array_search($this->modx->getOption('commerce_multilang.default_lang'), $langKeys)) !== false) {
            unset($langKeys[$key]);
        }

        return $langKeys;
    }
}