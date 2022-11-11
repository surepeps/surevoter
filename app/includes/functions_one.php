<?php

require_once('app_starter.php');



/**
 * Get banned ap address
 * @param $type
 * @return array
 */
function Sh_GetBanned($type = '') {
    global $sqlConnect;
    $data  = array();
    $query = mysqli_query($sqlConnect, "SELECT * FROM " . T_BANNED_IPS . " ORDER BY id DESC");
    if ($type == 'user') {
        while ($fetched_data = mysqli_fetch_assoc($query)) {
            if (filter_var($fetched_data['ip_address'], FILTER_VALIDATE_IP)) {
                $data[] = $fetched_data['ip_address'];
            }
        }
    } else {
        while ($fetched_data = mysqli_fetch_assoc($query)) {
            $data[] = $fetched_data;
        }
    }
    return $data;
}


/* @param $user_id
* @param $password
* @return array|false|mixed|null
*/
function Sh_UserData($user_id, $password = true) {
   global $sh, $sqlConnect, $cache;
   if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
       return false;
   }
   $data = array();
   $user_id  = Sh_Secure($user_id);
   $query_one      = "SELECT * FROM " . T_USERS . " WHERE `user_id` = {$user_id}";
   $hashed_user_Id = md5($user_id);
   if ($sh['config']['cacheSystem'] == 1) {
       $fetched_data = $cache->read($hashed_user_Id . '_U_Data.tmp');
       if (empty($fetched_data)) {
           $sql          = mysqli_query($sqlConnect, $query_one);
           $fetched_data = mysqli_fetch_assoc($sql);
           $cache->write($hashed_user_Id . '_U_Data.tmp', $fetched_data);
       }
   } else {
       $sql          = mysqli_query($sqlConnect, $query_one);
       $fetched_data = mysqli_fetch_assoc($sql);
   }
   if (empty($fetched_data)) {
       return array();
   }
   if ($password == false) {
       unset($fetched_data['password']);
   }
   $fetched_data['avatar_org'] = $fetched_data['avatar'];
   $explode2                   = @end(explode('.', $fetched_data['avatar']));
   $explode3                   = @explode('.', $fetched_data['avatar']);

   $fetched_data['avatar'] = Sh_GetMedia($fetched_data['avatar']) . '?cache=' . $fetched_data['last_avatar_mod'];
   $fetched_data['id']     = $fetched_data['user_id'];
   $fetched_data['user_platform'] = Sh_GetPlatformFromUser_ID($fetched_data['user_id']);
   $fetched_data['type']   = 'user';

   $fetched_data['name']   = '';

   if (!empty($fetched_data['first_name'])) {
       if (!empty($fetched_data['last_name'])) {
           $fetched_data['name'] = $fetched_data['first_name'] . ' ' . $fetched_data['last_name'];
       } else {
           $fetched_data['name'] = $fetched_data['first_name'];
       }
   } else {
       $fetched_data['name'] = $fetched_data['username'];
   }

   return $fetched_data;
}

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


/**
 * @param $email
 * @return bool
 */
function Sh_EmailExists($email) {
    global $sqlConnect;
    if (empty($email)) {
        return false;
    }
    $email = Sh_Secure($email);
    $query = mysqli_query($sqlConnect, "SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE `email` = '{$email}'");
    return (Sh_Sql_Result($query, 0) == 1) ? true : false;
}



/**
 * @param $email
 * @param $code
 * @return bool|void
 */
function Sh_ActivateUser($email, $code) {

    global $sqlConnect;
    $email  = Sh_Secure($email);
    $code   = Sh_Secure($code);
    $query  = mysqli_query($sqlConnect, " SELECT COUNT(`user_id`)  FROM " . T_USERS . "  WHERE `email` = '{$email}' AND `email_code` = '{$code}' AND `active` = '0'");
    $result = Sh_Sql_Result($query, 0);
    if ($result == 1) {
        $query_two = mysqli_query($sqlConnect, " UPDATE " . T_USERS . "  SET `active` = '1' WHERE `email` = '{$email}' ");
        if ($query_two) {
            return true;
        }
    } else {
        return false;
    }
}

/**
 * @param $user_id
 * @return bool
 */
function Sh_IsAdmin($user_id = 0) {
    global $sh, $sqlConnect;
    if ($sh['loggedin'] == false) {
        return false;
    }
    $user_id = Sh_Secure($user_id);
    if (!empty($user_id) && $user_id > 0) {
        $query = mysqli_query($sqlConnect, "SELECT COUNT(`user_id`) as count FROM " . T_USERS . " WHERE admin = '1' AND user_id = {$user_id}");
        $sql   = mysqli_fetch_assoc($query);
        if ($sql['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    if ($sh['user']['admin'] == 1) {
        return true;
    }
    return false;
}


/**
 * @param $username
 * @return bool
 */
function Sh_VerfiyIP($username = '') {
    global $sh, $db;
    if (empty($username)) {
        return false;
    }
    if ($sh['config']['login_auth'] == 0) {
        return true;
    }
    $getuser = Sh_UserData(Sh_UserIdForLogin($username));
    $get_ip = get_ip_address();
    $getIpInfo = fetchDataFromURL("http://ip-api.com/json/$get_ip");
    $getIpInfo = json_decode($getIpInfo, true);
    if ($getIpInfo['status'] == 'success' && !empty($getIpInfo['regionName']) && !empty($getIpInfo['countryCode']) && !empty($getIpInfo['timezone']) && !empty($getIpInfo['city'])) {
        $create_new = false;
        $_SESSION['last_login_data'] = $getIpInfo;
        if (empty($getuser['last_login_data'])) {
            $create_new = true;
        } else {
            $lastLoginData = (Array) json_decode($getuser['last_login_data']);
            if (($getIpInfo['regionName'] != $lastLoginData['regionName']) || ($getIpInfo['countryCode'] != $lastLoginData['countryCode']) || ($getIpInfo['timezone'] != $lastLoginData['timezone']) || ($getIpInfo['city'] != $lastLoginData['city'])) {
                // send email
                $code = rand(111111, 999999);
                $hash_code = md5($code);
                $sh['email']['username'] = $getuser['name'];
                $sh['email']['countryCode'] = $getIpInfo['countryCode'];
                $sh['email']['timezone'] = $getIpInfo['timezone'];
                $sh['email']['email'] = $getuser['email'];
                $sh['email']['ip_address'] = $get_ip;
                $sh['email']['code'] = $code;
                $sh['email']['city'] = $getIpInfo['city'];
                $sh['email']['date'] = date("Y-m-d h:i:sa");
                $update_code =  $db->where('user_id', $getuser['user_id'])->update(T_USERS, array('email_code' => $hash_code));
                $email_body = Sh_LoadPage("emails/unusual-login");
                $send_message_data       = array(
                    'from_email' => $sh['config']['siteEmail'],
                    'from_name' => $sh['config']['siteName'],
                    'to_email' => $getuser['email'],
                    'to_name' => $getuser['name'],
                    'subject' => 'Please verify that it’s you',
                    'charSet' => 'utf-8',
                    'message_body' => $email_body,
                    'is_html' => true
                );
                $send = Sh_SendMessage($send_message_data);
                if ($send && !empty($_SESSION['last_login_data'])) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }
        if ($create_new == true) {
            $lastLoginData = json_encode($getIpInfo);
            $update_user = $db->where('user_id', $getuser['user_id'])->update(T_USERS, array('last_login_data' => $lastLoginData));
            return true;
        }
        return false;
    } else {
        return true;
    }
}


/**
 * @param $table
 * @param $options
 * @return string
 */
function insertRow($table, $options){
    global $sqlConnect;
    $query = "";

    foreach($options as $key => $option){
        if(empty($option)){
            unset($options[$key]);
        }

        $options[$key] = addslashes($option);
    }

    if(count($options)){
        $columns = implode(',', array_keys($options));
        $values = "'" .implode("','", array_values($options)) . "'";

        $query = "INSERT INTO $table ({$columns}) VALUES ({$values})";

    }

    return mysqli_query($sqlConnect, $query);
}

