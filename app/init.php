<?php

include(__DIR__."/helpers/AllHelpers.php");

require_once('includes/db_tables.php');
require_once('includes/cache.php');
require_once('includes/general_functions.php');
require_once('includes/admin_functions.php');
require_once('includes/functions_one.php');

//Required ENV Data
requiredEnv();

@ini_set('session.cookie_httponly',1);
@ini_set('session.use_only_cookies',1);
@header("X-FRAME-OPTIONS: SAMEORIGIN");

//check if php version is compatible or not
if (!version_compare(PHP_VERSION, '7.3.0', '>=')) {
    exit("Required PHP_VERSION >= 7.3.0 , Your PHP_VERSION is : " . PHP_VERSION . "\n");
}

date_default_timezone_set('UTC');
session_start();

@ini_set('gd.jpeg_ignore_warning', 1);


if (!empty($_GET['ref']) && $sh['loggedin'] == false) {
    $get_ip = get_ip_address();
    if (!isset($_SESSION['ref']) && !empty($get_ip)) {
        $_GET['ref'] = Sh_Secure($_GET['ref']);
        $ref_user_id = Sh_UserIdFromUsername($_GET['ref']);
        $user_data = Sh_UserData($ref_user_id);
        if (!empty($user_data)) {
            $_SESSION['ref'] = $user_data['username'];
        }
    }
}
