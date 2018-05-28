<?php
/**
 * CommerceMultiLang build script
 * Based on the Commerce module build script by @markh of Modmore.
 *
 * @package commercemultilang
 * @subpackage build
 */

function getSnippetContent($filename) {
    $o = file_get_contents($filename);
    $o = str_replace('<?php','',$o);
    $o = str_replace('?>','',$o);
    $o = trim($o);
    return $o;
}

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* define version */
define('PKG_NAME', 'CommerceMultiLang');
define('PKG_NAMESPACE', 'commercemultilang');
define('PKG_VERSION', '0.2.1');
define('PKG_RELEASE', 'Alpha');

/* load modx */
require_once dirname(dirname(__FILE__)) . '/config.core.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx= new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');


echo '<pre>';
flush();
$targetDirectory = dirname(dirname(__FILE__)) . '/_packages/';


$root = dirname(dirname(__FILE__)).'/';
$sources= array (
    'root' => $root,
    'build' => $root .'_build/',
    'events' => $root . '_build/events/',
    'resolvers' => $root . '_build/resolvers/',
    'validators' => $root . '_build/validators/',
    'data' => $root . '_build/data/',
    'plugins' => $root.'core/components/'.PKG_NAMESPACE.'/elements/plugins/',
    'snippets' => $root.'core/components/'.PKG_NAMESPACE.'/elements/snippets/',
    'source_core' => $root.'core/components/'.PKG_NAMESPACE,
    'source_assets' => $root.'assets/components/'.PKG_NAMESPACE,
    'lexicon' => $root . 'core/components/'.PKG_NAMESPACE.'/lexicon/',
    'docs' => $root.'core/components/'.PKG_NAMESPACE.'/docs/',
    'model' => $root.'core/components/'.PKG_NAMESPACE.'/model/',
);
unset($root);

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->directory = $targetDirectory;
$builder->createPackage(PKG_NAMESPACE,PKG_VERSION,PKG_RELEASE);
$builder->registerNamespace(PKG_NAMESPACE,false,true,'{core_path}components/'.PKG_NAMESPACE.'/', '{assets_path}components/'.PKG_NAMESPACE.'/');

$modx->log(modX::LOG_LEVEL_INFO,'Packaged in namespace.'); flush();


/**
 * MENU & ACTION
 */
$menu = include $sources['data'].'transport.menu.php';
if (empty($menu)) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not package in menu.');
} else {
    $menuVehicle = $builder->createVehicle($menu, array(
        xPDOTransport::PRESERVE_KEYS => true,
        xPDOTransport::UPDATE_OBJECT => true,
        xPDOTransport::UNIQUE_KEY    => 'text'
    ));
    $builder->putVehicle($menuVehicle);
    $modx->log(modX::LOG_LEVEL_INFO, 'Packaged in Menu');
    unset($menuVehicle, $menu);
}

/**
 * SYSTEM SETTINGS
 */
$settings = include $sources['data'].'transport.settings.php';
$attributes= array(
    xPDOTransport::UNIQUE_KEY => 'key',
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
);
if (is_array($settings)) {
    foreach ($settings as $setting) {
        $vehicle = $builder->createVehicle($setting,$attributes);
        $builder->putVehicle($vehicle);
    }
    $modx->log(modX::LOG_LEVEL_INFO,'Packaged in '.count($settings).' system settings.'); flush();
    unset($settings,$setting,$attributes);
}

/**
 * CATEGORY
 */
$category = $modx->newObject('modCategory');
$category->set('id', 1);
$category->set('category', PKG_NAME);
$modx->log(modX::LOG_LEVEL_INFO, 'Packaged in category.');
flush();

/**
 * PLUGINS
 */
$plugins = include $sources['data'].'transport.plugins.php';
if (!is_array($plugins)) {
    $modx->log(modX::LOG_LEVEL_FATAL, 'Adding plugins failed.');
} else {
    $category->addMany($plugins);
    $attributes = array(
        xPDOTransport::UNIQUE_KEY                => 'name',
        xPDOTransport::PRESERVE_KEYS             => false,
        xPDOTransport::UPDATE_OBJECT             => true,
        xPDOTransport::RELATED_OBJECTS           => true,
        xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
            'PluginEvents' => array(
                xPDOTransport::PRESERVE_KEYS => true,
                xPDOTransport::UPDATE_OBJECT => false,
                xPDOTransport::UNIQUE_KEY    => array('pluginid', 'event'),
            ),
        ),
    );
    foreach ($plugins as $plugin) {
        $vehicle = $builder->createVehicle($plugin, $attributes);
        $builder->putVehicle($vehicle);
    }
    $modx->log(modX::LOG_LEVEL_INFO, 'Packaged in '.count($plugins).' plugins.');
    flush();
}
unset($plugins, $plugin, $attributes);

/**
 * SNIPPETS
 */
$snippets = include $sources['data'].'transport.snippets.php';
if (!is_array($snippets)) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not package in snippets.');
} else {
    $category->addMany($snippets);
    $modx->log(modX::LOG_LEVEL_INFO, 'Packaged in '.count($snippets).' snippets.');
}

/**
 * CHUNKS
 */
$chunks = include $sources['data'].'transport.chunks.php';
if (!is_array($chunks)) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not package in chunks.');
} else {
    $category->addMany($chunks);
    $modx->log(modX::LOG_LEVEL_INFO, 'Packaged in '.count($snippets).' chunks.');
}

/* create category vehicle */
$attr    = array(
    xPDOTransport::UNIQUE_KEY                => 'category',
    xPDOTransport::PRESERVE_KEYS             => false,
    xPDOTransport::UPDATE_OBJECT             => true,
    xPDOTransport::RELATED_OBJECTS           => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
        'Chunks' => array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY    => 'name',
        ),
        'Snippets' => array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY    => 'name',
        ),
        'Plugins' => array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY    => 'name',
        ),
    ),
);
$vehicle = $builder->createVehicle($category, $attr);

// Add the validator to check server requirements
$vehicle->validate('php', array('source' => $sources['validators'] . 'requirements.script.php'));
/**
 * ASSET FILES
 */
$vehicle->resolve('file',array(
    'source' => $sources['source_assets'],
    'target' => "return MODX_ASSETS_PATH . 'components/';",
));

/**
 * CORE FILES
 */
$vehicle->resolve('file',array(
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . 'components/';",
));

/**
 * ADD MODULE RESOLVER
 */
$vehicle->resolve('php', array(
    'source' => $sources['resolvers'].'registermodule.resolver.php',
));

$builder->putVehicle($vehicle);
$modx->log(modX::LOG_LEVEL_INFO,'Packaged in resolvers.'); flush();


/**
 * LICENSE, README AND CHANGELOG
 */
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),
));
$modx->log(modX::LOG_LEVEL_INFO,'Packaged in package attributes.'); flush();


$modx->log(modX::LOG_LEVEL_INFO,'Packing...'); flush();
$builder->pack();
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tend = $mtime;
$totalTime = ($tend - $tstart);
$totalTime = sprintf("%2.4f s", $totalTime);
$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

session_write_close();
exit();