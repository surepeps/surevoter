<?php

require_once('app/init.php');

$f = '';
$s = '';

if (isset($_GET['f'])) {
    $f = Sh_Secure($_GET['f'], 0);
}

if (isset($_GET['s'])) {
    $s = Sh_Secure($_GET['s'], 0);
}

$hash_id = '';
if (!empty($_POST['hash_id'])) {
    $hash_id = $_POST['hash_id'];
} else if (!empty($_GET['hash_id'])) {
    $hash_id = $_GET['hash_id'];
} else if (!empty($_GET['hash'])) {
    $hash_id = $_GET['hash'];
} else if (!empty($_POST['hash'])) {
    $hash_id = $_POST['hash'];
}
$data = array();
$allow_array = array(
    'upgrade',
    'paystack',
    'cashfree',
    'payment',
    'download_user_info',
    'get_payment_method',
    'funding'
);

//if (!in_array($f, $allow_array)) {
//    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
//        if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
//            exit("Restrcited Area");
//        }
//    } else {
//        exit("Restrcited Area");
//    }
//}

$files = scandir('api/xhr_api');

unset($files[0]);
unset($files[1]);

if (file_exists('api/xhr_api/' . $f . '.php') && in_array($f . '.php', $files)) {
    include 'api/xhr_api/' . $f . '.php';
}

mysqli_close($sqlConnect);
unset($sh);
exit();