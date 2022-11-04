<?php

require_once('configuration.php');


ini_set("display_errors", 0);
ini_set("track_errors", 0);
ini_set('display_startup_errors', 0);
ini_set("html_errors", 0);
error_reporting(E_ALL);

@ini_set('max_execution_time', 0);

// get all Application values
$sh = array();

// database settings
$sqlConnect = $sh['sqlConnect'] = mysqli_connect($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name, $sql_db_port);

// Handling Server Errors
$ServerErrors = array();
if (mysqli_connect_errno()) {
    $ServerErrors[] = "Failed to connect to MySQL: " . mysqli_connect_error();
}
if (!function_exists('curl_init')) {
    $ServerErrors[] = "PHP CURL is NOT installed on your web server !";
}
if (!extension_loaded('gd') && !function_exists('gd_info')) {
    $ServerErrors[] = "PHP GD library is NOT installed on your web server !";
}
if (!extension_loaded('zip')) {
    $ServerErrors[] = "ZipArchive extension is NOT installed on your web server !";
}


$query = mysqli_query($sqlConnect, "SET NAMES utf8mb4");
if (isset($ServerErrors) && !empty($ServerErrors)) {
    foreach ($ServerErrors as $Error) {
        echo "<h3>" . $Error . "</h3>";
    }
    die();
}



$baned_ips = Sh_GetBanned('user');

if (in_array($_SERVER["REMOTE_ADDR"], $baned_ips)) {
    exit();
}

$config =  Sh_GetConfig();
$messageDb = Sh_GetNotifyMessages();
$db = new MysqliDb($sqlConnect);

$all_langs = Sh_LangsNamesFromDB();


foreach ($all_langs as $key => $value) {
    $insert = false;
    if (!in_array($value, array_keys($config))) {
        $db->insert(T_CONFIG,array('name' => $value, 'value' => 1));
        $insert = true;
    }
}
if ($insert == true) {
    $config = Sh_GetConfig();
}

if( ISSET( $_GET['theme'] ) && in_array($_GET['theme'], ['default', 'surethemes', 'betasouk_one'])){
    $_SESSION['theme'] = $_GET['theme'];
}

// Config Url
$config['theme_url'] = $site_url . '/themes/' . $config['theme'];

// Config Theme Assets Url
$config['theme_assets_url'] = $site_url . '/themes/' . $config['theme'] .'/assets';

$config['site_url']  = $site_url;
$sh['site_url']      = $site_url;

// database name
$sh['dbname'] = $sql_db_name;

// config values
$sh['config']  = $config;

// database messages
$sh['message'] = $messageDb;

// custom code settings
$ccode  = Sh_CustomCode('g');
$ccode  = (is_array($ccode))  ? $ccode    : array();
$sh['config']['header_cc'] = (!empty($ccode[0])) ? $ccode[0] : '';
$sh['config']['footer_cc'] = (!empty($ccode[1])) ? $ccode[1] : '';
$sh['config']['styles_cc'] = (!empty($ccode[2])) ? $ccode[2] : '';

// No Image Available
$sh['config']['no_image_available'] = $sh['config']['site_url'].'/upload/photos/no-image.png';


// Script version
$sh['script_version']  = $sh['config']['version'];
$http_header = 'http://';
if (!empty($_SERVER['HTTPS'])) {
    $http_header = 'https://';
}
$sh['actual_link'] = $http_header . $_SERVER['HTTP_HOST'] . urlencode($_SERVER['REQUEST_URI']);
// Define Cache variable
$cache = new Cache();

if (!is_dir('cache')) {
    $cache->Sh_OpenCacheDir();
}

// Get language
if (empty($_SESSION['lang'])) {
    $_SESSION['lang'] = $sh['config']['defualtLang'];
}
$sh['language'] = $_SESSION['lang'];

$langs = Sh_LangsNamesFromDB();

$sh['lang'] = Sh_LangsFromDB($sh['language']);

// LoggediN checker
$sh['loggedin'] = false;

if (Sh_IsLogged() == true) {
    $session_id         = (!empty($_SESSION['user_id'])) ? $_SESSION['user_id'] : $_COOKIE['user_id'];
    $sh['user_session'] = Sh_GetUserFromSessionID($session_id);
    $sh['user'] = Sh_UserData($sh['user_session']);
    if (!empty($sh['user']['language'])) {
        if (in_array($sh['user']['language'], $langs)) {
            $_SESSION['lang'] = $sh['user']['language'];
        }
    }
    if ($sh['user']['user_id'] < 0 || empty($sh['user']['user_id']) || !is_numeric($sh['user']['user_id']) || Sh_UserActive($sh['user']['username']) === false) {
        header("Location: " . Sh_Link("logout"));
    }
    $sh['loggedin'] = true;
}

if (!empty($_GET['c_id']) && !empty($_GET['user_id'])) {
    $application = 'windows';
    if (!empty($_GET['application'])) {
        if ($_GET['application'] == 'phone') {
            $application = Sh_Secure($_GET['application']);
        }
    }
    $c_id             = Sh_Secure($_GET['c_id']);
    $user_id          = Sh_Secure($_GET['user_id']);
    $check_if_session = Sh_CheckUserSessionID($user_id, $c_id, $application);
    if ($check_if_session === true) {
        $sh['user']          = Sh_UserData($user_id);
        $session             = Sh_CreateLoginSession($user_id);
        $_SESSION['user_id'] = $session;
        setcookie("user_id", $session, time() + (10 * 365 * 24 * 60 * 60));
        if ($sh['user']['user_id'] < 0 || empty($sh['user']['user_id']) || !is_numeric($sh['user']['user_id']) || Sh_UserActive($sh['user']['username']) === false) {
            header("Location: " . Sh_Link("logout"));
        }
        $sh['loggedin'] = true;
    }
}

if (!empty($_GET['theme'])) {
    Sh_CleanCache();
}
