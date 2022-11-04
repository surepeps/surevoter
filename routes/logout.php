<?php
/**
 * betasouk
 * Created by SureCoder
 * FILE NAME: logout.php
 * YEAR: 2022
 */


session_unset();
if (!empty($_SESSION['user_id'])) {
    $_SESSION['user_id'] = '';
    $query = mysqli_query($sqlConnect, "DELETE FROM " .  T_APP_SESSIONS . " WHERE `session_id` = '" . Sh_Secure($_SESSION['user_id']) . "'");
}
session_destroy();
if (isset($_COOKIE['user_id'])) {
    $query = mysqli_query($sqlConnect, "DELETE FROM " .  T_APP_SESSIONS . " WHERE `session_id` = '" . Sh_Secure($_COOKIE['user_id']) . "'");
    $_COOKIE['user_id'] = '';
    unset($_COOKIE['user_id']);
    setcookie('user_id', '', -1);
    setcookie('user_id', '', -1,'/');
}
$_SESSION = array();
unset($_SESSION);
header("Location: " . $sh['config']['site_url']);
exit();