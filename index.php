<?php

namespace App;

define('PRODUCTION_MODE', false);

if (PRODUCTION_MODE) {
    header("Cache-control: public");
    header("Expires: " . gmdate("D, d M Y H:i:s", time() + 7 * 60 * 60 * 24) . " GMT");
} else {
    header("Expires: " . gmdate("D, d M Y H:i:s", time()) . " GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate");
}

define('DSP', DIRECTORY_SEPARATOR);
define('APP_ROOT', __DIR__.DSP);
define('CONFIG_PATH', APP_ROOT.DSP.'Config'.DSP);
define('CLASSES_PATH', APP_ROOT.DSP.'Classes'.DSP);
define('MODELS_PATH', APP_ROOT.DSP.'Models'.DSP);
define('HOOKS_PATH', APP_ROOT.DSP.'Hooks'.DSP);
define('TEMPLATE_PATH', 'Views/');

error_reporting(E_ALL);

ini_set('session.gc_maxlifetime', 2592000);
ini_set('session.cookie_lifetime', 2592000);
ini_set('session.save_path', APP_ROOT.'Sessions');
ini_set('max_input_vars', 10000);
ini_set('memory_limit', '256M');

//ini_set('memory_limit', '-1');

session_start();

include('bootstrap.php');

use Classes\Application;
use Classes\Security;
use Classes\Url;

$config = array(
    'database'   => include(CONFIG_PATH.'database.php'),
    'routes'     => include(CONFIG_PATH.'routes.php'),
    'controller' => 'Controllers\\',
    'salt' => 'Борода не делает козла раввином'
);

if (!isset($_SESSION['lang'])) $_SESSION['lang'] = 'en';
if (!isset($_SESSION['current'])) $_SESSION['current'] = 0;
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        "admin" => 0
    ];
}

if ((!isset($_SESSION['current']) || !$_SESSION['current']) && ($_SERVER['REQUEST_URI'] === "/add") ) {
    Url::Redirect("/login");
    exit(0);
}

$GET = Security::XSS($_GET);
$POST = Security::XSS($_POST);
$REQUEST = Security::XSS($_REQUEST);

try {
    $app = new Application($config);
    $app->Run(
        array(
            'preprocess' => array('Hooks/preprocess.php'),
            'postprocess' => array('Hooks/postprocess.php')
        )
    );
} catch (\Exception $e) {
    echo "General failure! This is fucking pizdec!<br>";
    echo $e->getMessage();
}
