<?php


/**
 * @param $user_id
 * @return false|mixed
 */
function Sh_GetPlatformFromUser_ID($user_id = 0) {
    global $sqlConnect;
    if (empty($user_id)) {
        return false;
    }
    $user_id = Sh_Secure($user_id);
    $query   = mysqli_query($sqlConnect, "SELECT `platform` FROM " . T_APP_SESSIONS . " WHERE `user_id` = '{$user_id}' ORDER BY `time` DESC LIMIT 1");
    $mysqli  = mysqli_fetch_assoc($query);
    $returnR = ($mysqli['platform']) ?? '';
    return $returnR;
}



/**
 * @param $username
 * @return bool
 */
function Sh_UserActive($username) {
    global $sqlConnect;
    if (empty($username)) {
        return false;
    }
    $username = Sh_Secure($username);
    $query    = mysqli_query($sqlConnect, "SELECT COUNT(`user_id`) FROM " . T_USERS . "  WHERE (`username` = '{$username}' OR `email` = '{$username}' ) AND `active` = '1'");
    return (Sh_Sql_Result($query, 0) == 1) ? true : false;
}

/**
 * @param $username
 * @return bool
 */
function Sh_UserInactive($username) {
    global $sqlConnect;
    if (empty($username)) {
        return false;
    }
    $username = Sh_Secure($username);
    $query    = mysqli_query($sqlConnect, "SELECT COUNT(`user_id`) FROM " . T_USERS . "  WHERE (`username` = '{$username}' OR `email` = '{$username}') AND `active` = '2'");
    return (Sh_Sql_Result($query, 0) == 1) ? true : false;
}


/**
 * @param $username
 * @return bool
 *
 */
function Sh_UserExists($username) {
    global $sqlConnect;
    if (empty($username)) {
        return false;
    }
    $username = Sh_Secure($username);
    $query    = mysqli_query($sqlConnect, "SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE `username` = '{$username}'");
    return (Sh_Sql_Result($query, 0) == 1) ? true : false;
}


/**
 * @param $user_id
 * @param $session_id
 * @param $platform
 * @return bool
 */
function Sh_CheckUserSessionID($user_id = 0, $session_id = '', $platform = 'web') {
    global $sh, $sqlConnect;
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    if (empty($session_id)) {
        return false;
    }
    $platform  = Sh_Secure($platform);
    $query     = mysqli_query($sqlConnect, "SELECT COUNT(`id`) as `session` FROM " . T_APP_SESSIONS . " WHERE `user_id` = '{$user_id}' AND `session_id` = '{$session_id}' AND `platform` = '{$platform}'");
    $query_sql = mysqli_fetch_assoc($query);
    if ($query_sql['session'] > 0) {
        return true;
    }
    return false;
}


/**
 * @param $username
 * @return false|mixed
 */
function Sh_UserIdFromUsername($username) {
    global $sqlConnect;
    if (empty($username)) {
        return false;
    }
    $username = Sh_Secure($username);
    $query    = mysqli_query($sqlConnect, "SELECT `user_id` FROM " . T_USERS . " WHERE `username` = '{$username}'");
    return Sh_Sql_Result($query, 0, 'user_id');
}



/**
 * @param $user_id
 * @return false|string|void
 */
function Sh_CreateLoginSession($user_id = 0) {
    global $sqlConnect, $db;
    if (empty($user_id)) {
        return false;
    }
    $user_id   = Sh_Secure($user_id);
    $hash      = sha1(rand(111111111, 999999999)) . md5(microtime()) . rand(11111111, 99999999) . md5(rand(5555, 9999));
    $query_two = mysqli_query($sqlConnect, "DELETE FROM " . T_APP_SESSIONS . " WHERE `session_id` = '{$hash}'");
    if ($query_two) {
        $ua = json_encode(getBrowser());
        $delete_same_session = $db->where('user_id', $user_id)->where('platform_details', $ua)->delete(T_APP_SESSIONS);
        $query_three = mysqli_query($sqlConnect, "INSERT INTO " . T_APP_SESSIONS . " (`user_id`, `session_id`, `platform`, `platform_details`, `time`) VALUES('{$user_id}', '{$hash}', 'web', '$ua'," . time() . ")");
        if ($query_three) {
            return $hash;
        }
    }
}



/**
 * @param $media
 * @return string
 */
function Sh_GetMedia($media) {
    global $sh;
    if (empty($media)) {
        return '';
    }
    if ($sh['config']['amazone_s3'] == 1) {
        if (empty($sh['config']['amazone_s3_key']) || empty($sh['config']['amazone_s3_s_key']) || empty($sh['config']['region']) || empty($sh['config']['bucket_name'])) {
            return $sh['config']['site_url'] . '/' . $media;
        }
        return $sh['config']['s3_site_url'] . '/' . $media;
    } else if ($sh['config']['spaces'] == 1) {
        if (empty($sh['config']['spaces_key']) || empty($sh['config']['spaces_secret']) || empty($sh['config']['space_region']) || empty($sh['config']['space_name'])) {
            return $sh['config']['site_url'] . '/' . $media;
        }
        return  'https://' . $sh['config']['space_name'] . '.' . $sh['config']['space_region'] . '.digitaloceanspaces.com/' . $media;
    } else if ($sh['config']['ftp_upload'] == 1) {
        return addhttp($sh['config']['ftp_endpoint']) . '/' . $media;

    } else if ($sh['config']['cloud_upload'] == 1) {
        return 'https://storage.cloud.google.com/'. $sh['config']['cloud_bucket_name'] . '/' . $media;
    }
    return $sh['config']['site_url'] . '/' . $media;
}


/**
 * @param $url
 * @return mixed|string
 */
function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}



/**
 * @return bool|void
 */
function ShAddBadLoginLog() {
    global $sh, $sqlConnect;
    if ($sh['loggedin'] == true) {
        return false;
    }
    $ip = get_ip_address();
    if (empty($ip)) {
        return true;
    }
    $time      = time();
    $query     = mysqli_query($sqlConnect, "INSERT INTO " . T_BAD_LOGIN . " (`ip`, `time`) VALUES ('{$ip}', '{$time}')");
    if ($query) {
        return true;
    }
}


/**
 * @return bool
 */
function ShCanLogin() {
    global $sh, $sqlConnect,$db;
    if ($sh['loggedin'] == true) {
        return false;
    }
    $ip = get_ip_address();
    if (empty($ip)) {
        return true;
    }
    if ($sh['config']['lock_time'] < 1) {
        return true;
    }
    if ($sh['config']['bad_login_limit'] < 1) {
        return true;
    }

    $time      = time() - (60 * $sh['config']['lock_time']);
    $login = $db->where('ip',$ip)->get(T_BAD_LOGIN);
    if (count($login) >= $sh['config']['bad_login_limit']) {
        $last = end($login);
        if ($last->time >= $time) {
            return false;
        }
    }
    $db->where('time',time()-(60 * $sh['config']['lock_time'] * 2),'<')->delete(T_BAD_LOGIN);
    return true;
}


/**
 * @param $username
 * @param $password
 * @return bool
 */
function Sh_Login($username, $password) {
    global $sqlConnect;
    if (empty($username) || empty($password)) {
        return false;
    }
    $username            = Sh_Secure($username);
    $query_hash          = mysqli_query($sqlConnect, "SELECT * FROM " . T_USERS . " WHERE (`username` = '{$username}' OR `email` = '{$username}' OR `phone_number` = '{$username}')");
    $mysqli_hash_upgrade = mysqli_fetch_assoc($query_hash);

    if(!$mysqli_hash_upgrade){
        return false;
    }

    $login_password = '';
    $hash                = 'md5';
    if (preg_match('/^[a-f0-9]{32}$/', $mysqli_hash_upgrade['password'])) {
        $hash = 'md5';
    } else if (preg_match('/^[0-9a-f]{40}$/i', $mysqli_hash_upgrade['password'])) {
        $hash = 'sha1';
    } else if (strlen($mysqli_hash_upgrade['password']) == 60) {
        $hash = 'password_hash';
    }
    if ($hash == 'password_hash') {
        if (password_verify($password, $mysqli_hash_upgrade['password'])) {
            return true;
        }
    } else {
        $login_password = Sh_Secure($hash($password));
    }		//edie($login_password);	//echo "SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE (`username` = '{$username}' OR `email` = '{$username}' OR `phone_number` = '{$username}') AND `password` = '{$login_password}'";
    $query          = mysqli_query($sqlConnect, "SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE (`username` = '{$username}' OR `email` = '{$username}' OR `phone_number` = '{$username}') AND `password` = '{$login_password}'");
    if (Sh_Sql_Result($query, 0) == 1) {
        if ($hash == 'sha1' || $hash == 'md5') {
            $new_password = Sh_Secure(password_hash($password, PASSWORD_DEFAULT));
            $query_       = mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET password = '$new_password' WHERE (`username` = '{$username}' OR `email` = '{$username}' OR `phone_number` = '{$username}')");
        }
        return true;
    }
    return false;
}



/**
 * @param $username
 * @return false|mixed
 */
function Sh_UserIdForLogin($username) {
    global $sqlConnect;
    if (empty($username)) {
        return false;
    }
    $username =   Sh_Secure($username);
    $query    = mysqli_query($sqlConnect, "SELECT `user_id` FROM " . T_USERS . " WHERE `username` = '{$username}' OR `email` = '{$username}'");
    return Sh_Sql_Result($query, 0, 'user_id');
}

/**
 * @return mixed|string
 */
function Sh_CreateSession() {
    $hash = sha1(rand(1111, 9999));
    if (!empty($_SESSION['hash_id'])) {
        $_SESSION['hash_id'] = $_SESSION['hash_id'];
        return $_SESSION['hash_id'];
    }
    $_SESSION['hash_id'] = $hash;
    return $hash;
}


/**
 * @return false|mixed|string
 */
function Sh_CreateMainSession() {
    $hash = substr(sha1(rand(1111, 9999)), 0, 20);
    if (!empty($_SESSION['main_hash_id'])) {
        $_SESSION['main_hash_id'] = $_SESSION['main_hash_id'];
        return $_SESSION['main_hash_id'];
    }
    $_SESSION['main_hash_id'] = $hash;
    return $hash;
}




/**
 * @param $hash
 * @return bool
 */
function Sh_CheckMainSession($hash = '') {
    if (!isset($_SESSION['main_hash_id']) || empty($_SESSION['main_hash_id'])) {
        return false;
    }
    if (empty($hash)) {
        return false;
    }
    if ($hash == $_SESSION['main_hash_id']) {
        return true;
    }
    return false;
}
