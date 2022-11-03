<?php
/**
 * get ip address
 * @return mixed
 */
function get_ip_address() {
    if (!empty($_SERVER['HTTP_X_FORWARDED']) && validate_ip($_SERVER['HTTP_X_FORWARDED']))
        return $_SERVER['HTTP_X_FORWARDED'];
    if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
        return $_SERVER['HTTP_FORWARDED_FOR'];
    if (!empty($_SERVER['HTTP_FORWARDED']) && validate_ip($_SERVER['HTTP_FORWARDED']))
        return $_SERVER['HTTP_FORWARDED'];
    return $_SERVER['REMOTE_ADDR'];
}


/**
 * Validate IP ADDRESS
 * @param $ip
 * @return bool
 */
function validate_ip($ip) {
    if (strtolower($ip) === 'unknown')
        return false;
    $ip = ip2long($ip);
    if ($ip !== false && $ip !== -1) {
        $ip = sprintf('%u', $ip);
        if ($ip >= 0 && $ip <= 50331647)
            return false;
        if ($ip >= 167772160 && $ip <= 184549375)
            return false;
        if ($ip >= 2130706432 && $ip <= 2147483647)
            return false;
        if ($ip >= 2851995648 && $ip <= 2852061183)
            return false;
        if ($ip >= 2886729728 && $ip <= 2887778303)
            return false;
        if ($ip >= 3221225984 && $ip <= 3221226239)
            return false;
        if ($ip >= 3232235520 && $ip <= 3232301055)
            return false;
        if ($ip >= 4294967040)
            return false;
    }
    return true;
}

/**
 * Ip address in range
 * @param $ip
 * @param $range
 * @return bool
 */
function ip_in_range($ip, $range) {
    if (strpos($range, '/') == false) {
        $range .= '/32';
    }
    // $range is in IP/CIDR format eg 127.0.0.1/24
    list($range, $netmask) = explode('/', $range, 2);
    $range_decimal    = ip2long($range);
    $ip_decimal       = ip2long($ip);
    $wildcard_decimal = pow(2, (32 - $netmask)) - 1;
    $netmask_decimal  = ~$wildcard_decimal;
    return (($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal));
}


/**
 * Get client access browser
 * @return array
 */
function getBrowser() {
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";
    // First get the platform?
    if (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    } elseif (preg_match('/iphone|IPhone/i', $u_agent)) {
        $platform = 'IPhone Web';
    } elseif (preg_match('/android|Android/i', $u_agent)) {
        $platform = 'Android Web';
    } else if (preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $u_agent)) {
        $platform = 'Mobile';
    } else if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif(preg_match('/Firefox/i',$u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif(preg_match('/Chrome/i',$u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif(preg_match('/Safari/i',$u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } elseif(preg_match('/Opera/i',$u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif(preg_match('/Netscape/i',$u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        } else {
            $version= $matches['version'][1];
        }
    } else {
        $version= $matches['version'][0];
    }
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern,
        'ip_address' => get_ip_address()
    );
}


/**
 * Clean string
 * @param $string
 * @return array|string|string[]|null
 */
function cleanString($string) {
    return $string = preg_replace("/&#?[a-z0-9]+;/i","", $string);
}


/**
 * Make string secure
 * @param $string
 * @param $censored_words
 * @param $br
 * @param $strip
 * @return array|string|string[]
 */
function Sh_Secure($string, $censored_words = 1, $br = true, $strip = 0) {
    global $sqlConnect;
    $string = trim($string);
    $string = cleanString($string);
    $string = mysqli_real_escape_string($sqlConnect, $string);
    $string = htmlspecialchars($string, ENT_QUOTES);
    if ($br == true) {
        $string = str_replace('\r\n', " <br>", $string);
        $string = str_replace('\n\r', " <br>", $string);
        $string = str_replace('\r', " <br>", $string);
        $string = str_replace('\n', " <br>", $string);
    } else {
        $string = str_replace('\r\n', "", $string);
        $string = str_replace('\n\r', "", $string);
        $string = str_replace('\r', "", $string);
        $string = str_replace('\n', "", $string);
    }
    if ($strip == 1) {
        $string = stripslashes($string);
    }
    $string = str_replace('&amp;#', '&#', $string);
    return $string;
}


/**
 * @return array
 */
function Sh_GetConfig() {
    global $sqlConnect;
    $data  = array();
    $query = mysqli_query($sqlConnect, "SELECT * FROM " . T_CONFIG);
    while ($fetched_data = mysqli_fetch_assoc($query)) {
        $data[$fetched_data['name']] = $fetched_data['value'];
    }
    return $data;
}


/**
 * @return array
 */
function Sh_GetNotifyMessages() {
    global $sqlConnect;
    $data  = array();
    $query = mysqli_query($sqlConnect, "SELECT * FROM " . T_NOTIFY_MESSAGES);
    while ($fetched_data = mysqli_fetch_assoc($query)) {
        $data[$fetched_data['name']] = $fetched_data['value'];
    }
    return $data;
}


/**
 * @param $lang
 * @return array
 */
function Sh_LangsNamesFromDB($lang = 'english') {
    global $sqlConnect, $sh;
    $data  = array();
    $query = mysqli_query($sqlConnect, "SHOW COLUMNS FROM " . T_LANGS);
    while ($fetched_data = mysqli_fetch_assoc($query)) {
        $data[] = $fetched_data['Field'];
    }
    unset($data[0]);
    unset($data[1]);
    unset($data[2]);
    return $data;
}


/**
 * @param $lang
 * @return array
 */
function Sh_LangsFromDB($lang = 'english') {
    global $sqlConnect, $sh;
    $data  = array();
    $query = mysqli_query($sqlConnect, "SELECT `lang_key`, `$lang` FROM " . T_LANGS);
    while ($fetched_data = mysqli_fetch_assoc($query)) {
        $data[$fetched_data['lang_key']] = htmlspecialchars_decode($fetched_data[$lang]);
    }
    return $data;
}


/**
 * @param $a
 * @param $code
 * @return array|bool
 */
function Sh_CustomCode($a = false,$code = array()){
    global $sh;
    $theme       = $sh['config']['theme'];
    $data        = array();
    $result      = false;
    $custom_code = array(
        "themes/$theme/custom/js/head.js",
        "themes/$theme/custom/js/footer.js",
        "themes/$theme/custom/css/style.css",
    );
    if ($a == 'g') {
        foreach ($custom_code as $key => $filepath) {
            if (is_readable($filepath)) {
                $data[$key] = file_get_contents($filepath);
            }
        }
        $result = $data;
    }
    else if($a == 'p' && !empty($code)){
        foreach ($code as $key => $content) {
            if (is_writable($custom_code[$key])) {
                @file_put_contents($custom_code[$key],$content);
            }
        }
        $result = true;
    }
    return $result;
}


/**
 * @return bool|void
 */
function Sh_IsLogged() {
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        $id = Sh_GetUserFromSessionID($_SESSION['user_id']);
        if (is_numeric($id) && !empty($id)) {
            return true;
        }
    } else if (!empty($_COOKIE['user_id']) && !empty($_COOKIE['user_id'])) {
        $id = Sh_GetUserFromSessionID($_COOKIE['user_id']);
        if (is_numeric($id) && !empty($id)) {
            return true;
        }
    } else {
        return false;
    }
}



/**
 * @return bool|void
 */
function Sh_IsCarted() {
    if (isset($_SESSION['cart_id']) && !empty($_SESSION['cart_id'])) {
        return true;
    } else if (!empty($_COOKIE['cart_id']) && !empty($_COOKIE['cart_id'])) {
        return true;
    } else {
        return false;
    }
}




/**
 * @param $session_id
 * @param $platform
 * @return false|mixed
 */
function Sh_GetUserFromSessionID($session_id, $platform = 'web') {
    global $sqlConnect, $db;
    if (empty($session_id)) {
        return false;
    }

    $session_id = Sh_Secure($session_id);
    $query      = mysqli_query($sqlConnect, "SELECT * FROM " . T_APP_SESSIONS . " WHERE `session_id` = '{$session_id}' LIMIT 1");
    $fetched_data = mysqli_fetch_assoc($query);

    if($fetched_data){
        if (empty($fetched_data['platform_details']) && $fetched_data['platform'] == 'web') {
            $ua = json_encode(getBrowser());
            if (isset($fetched_data['platform_details'])) {
                $update_session = $db->where('id', $fetched_data['id'])->update(T_APP_SESSIONS, array('platform_details' => $ua));
            }
        }
        return $fetched_data['user_id'];
    }else{
        return 0;
    }

}


/**
 * @param $res
 * @param $row
 * @param $col
 * @return false|mixed
 */
function Sh_Sql_Result($res, $row = 0, $col = 0) {
    $numrows = mysqli_num_rows($res);
    if ($numrows && $row <= ($numrows - 1) && $row >= 0) {
        mysqli_data_seek($res, $row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])) {
            return $resrow[$col];
        }
    }
    return false;
}


/**
 * @param $user_id
 * @param $where
 * @return false|void
 */
function Sh_CleanCache($user_id = '', $where = 'sidebar') {
    global $sh;
    if ($sh['config']['cache_sidebar'] == 0 || $sh['loggedin'] == false) {
        return false;
    }
    $file_path = './cache/sidebar-' . $sh['user']['user_id'] . '.tpl';
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}


/**
 * @param $string
 * @return string
 */
function Sh_Link($string) {
    global $site_url;
    return $site_url . '/' . $string;
}


/**
 * @param $page_url
 * @return false|string
 */
function Sh_LoadPage($page_url = '') {
    global $sh,$db;
    $create_file = false;
    if ($page_url == 'sidebar/content' && $sh['loggedin'] == true && $sh['config']['cache_sidebar'] == 1) {
        $file_path = './cache/sidebar-' . $sh['user']['user_id'] . '.tpl';
        if (file_exists($file_path)) {
            $get_file = file_get_contents($file_path);
            if (!empty($get_file)) {
                return $get_file;
            }
        } else {
            $create_file = true;
        }
    }
    $page         = './themes/' . $sh['config']['theme'] . '/layout/' . $page_url . '.phtml';
    $page_content = '';
    ob_start();
    require($page);
    $page_content = ob_get_contents();
    ob_end_clean();
    if ($create_file == true && $sh['config']['cache_sidebar'] == 1) {
        $create_sidebar_file = file_put_contents($file_path, $page_content);
        setcookie("last_sidebar_update", time(), time() + (10 * 365 * 24 * 60 * 60));
    }
    return $page_content;
}


/**
 * @param $link
 * @return string
 */
function Sh_LoadAdminLink($link = '') {
    global $site_url;
    return $site_url . '/admin-system/' . $link;
}


/**
 * @param $minlength
 * @param $maxlength
 * @param $uselower
 * @param $useupper
 * @param $usenumbers
 * @param $usespecial
 * @return string
 */
function Sh_GenerateKey($minlength = 20, $maxlength = 20, $uselower = true, $useupper = true, $usenumbers = true, $usespecial = false) {
    $charset = '';
    if ($uselower) {
        $charset .= "abcdefghijklmnopqrstuvwxyz";
    }
    if ($useupper) {
        $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    }
    if ($usenumbers) {
        $charset .= "123456789";
    }
    if ($usespecial) {
        $charset .= "~@#$%^*()_+-={}|][";
    }
    if ($minlength > $maxlength) {
        $length = mt_rand($maxlength, $minlength);
    } else {
        $length = mt_rand($minlength, $maxlength);
    }
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .= $charset[(mt_rand(0, strlen($charset) - 1))];
    }
    return $key;
}


/**
 * @param $url
 * @return bool|string
 */
function fetchDataFromURL($url = '') {
    if (empty($url)) {
        return false;
    }
    $ch = curl_init($url);
    curl_setopt( $ch, CURLOPT_POST, false );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
    curl_setopt( $ch, CURLOPT_HEADER, false );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt( $ch, CURLOPT_TIMEOUT, 5);
    return curl_exec( $ch );
}


/**
 * @param $data
 * @return bool|void
 */
function Sh_SendMessage($data = array()){
    global $sh, $sqlConnect;
    include_once('app/helpers/PHPMailer-Master/vendor/autoload.php');
    $mail = new PHPMailer\PHPMailer\PHPMailer;

    try{

        $email_from      = $data['from_email'] = Sh_Secure($data['from_email']);
        $to_email        = $data['to_email'] = Sh_Secure($data['to_email']);
        $subject         = $data['subject'];
        $message_body    = mysqli_real_escape_string($sqlConnect, $data['message_body']);
        $data['charSet'] = Sh_Secure($data['charSet']);
        if (isset($data['insert_database'])) {
            if ($data['insert_database'] == 1) {
                $user_id   = Sh_Secure($sh['user']['user_id']);
                $query_one = mysqli_query($sqlConnect, "INSERT INTO " . T_EMAILS . " (`email_to`, `user_id`, `subject`, `message`) VALUES ('{$to_email}', '{$user_id}', '{$subject}', '{$message_body}')");
                if ($query_one) {
                    return true;
                }
            }
            return true;
            exit();
        }

        if ($sh['config']['smtp_or_mail'] == 'mail') {
            $mail->IsMail();
        } else if ($sh['config']['smtp_or_mail'] == 'smtp') {
            $data['from_email'] = $sh['config']['smtp_username'];
            $mail->isSMTP();
            $mail->Host        = $sh['config']['smtp_host']; // Specify main and backup SMTP servers
            $mail->SMTPAuth    = true; // Enable SMTP authentication
            $mail->Username    = $sh['config']['smtp_username']; // SMTP username
            $mail->Password    = $sh['config']['smtp_password']; // SMTP password
            $mail->SMTPSecure  = $sh['config']['smtp_encryption']; // Enable TLS encryption, `ssl` also accepted
            $mail->Port        = $sh['config']['smtp_port'];
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
        } else {
            return false;
        }
        $mail->IsHTML($data['is_html']);
        $mail->setFrom($data['from_email'], $data['from_name']);
        $mail->addAddress($data['to_email'], $data['to_name']); // Add a recipient
        $mail->Subject = $data['subject'];
        $mail->CharSet = $data['charSet'];
        $mail->MsgHTML($data['message_body']);
        if (!empty($data['reply-to'])) {
            $mail->ClearReplyTos();
            $mail->AddReplyTo($data['reply-to'], $data['from_name']);
        }
        if ($mail->send()) {
            $mail->ClearAddresses();
            return true;
        }

    }catch (Exception $e) {
        return false;
//        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

}



/**
 * @param $text
 * @param string $divider
 * @return string
 */
function Sh_Slugify($text, string $divider = '-')
{
    // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, $divider);

    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}


/**
 * @param $data
 * @param $type
 * @param $crop
 * @return array|false|void
 */
function Sh_ShareFile($data = array(), $type = 0, $crop = true) {
    global $sh, $sqlConnect, $s3;

    $allowed = '';

    if (!file_exists('upload/files/' . date('Y'))) {
        @mkdir('upload/files/' . date('Y'), 0777, true);
    }
    if (!file_exists('upload/files/' . date('Y') . '/' . date('m'))) {
        @mkdir('upload/files/' . date('Y') . '/' . date('m'), 0777, true);
    }
    if (!file_exists('upload/photos/' . date('Y'))) {
        @mkdir('upload/photos/' . date('Y'), 0777, true);
    }
    if (!file_exists('upload/photos/' . date('Y') . '/' . date('m'))) {
        @mkdir('upload/photos/' . date('Y') . '/' . date('m'), 0777, true);
    }
    if (!file_exists('upload/videos/' . date('Y'))) {
        @mkdir('upload/videos/' . date('Y'), 0777, true);
    }
    if (!file_exists('upload/videos/' . date('Y') . '/' . date('m'))) {
        @mkdir('upload/videos/' . date('Y') . '/' . date('m'), 0777, true);
    }
    if (!file_exists('upload/sounds/' . date('Y'))) {
        @mkdir('upload/sounds/' . date('Y'), 0777, true);
    }
    if (!file_exists('upload/sounds/' . date('Y') . '/' . date('m'))) {
        @mkdir('upload/sounds/' . date('Y') . '/' . date('m'), 0777, true);
    }
    if (isset($data['file']) && !empty($data['file'])) {
        $data['file'] = $data['file'];
    }
    if (isset($data['name']) && !empty($data['name'])) {
        $data['name'] = Sh_Secure($data['name']);
    }
    if (empty($data)) {
        return false;
    }
    if ($sh['config']['fileSharing'] == 1) {

        if (isset($data['types'])) {
            $allowed = $data['types'];
        } else {
            $allowed = $sh['config']['allowedExtenstion'];
        }

    } else {
        $allowed = 'jpg,png,jpeg,gif,mp4,m4v,webm,flv,mov,mpeg,mp3,wav,doc,docx,xls,xlsx,csv,pptx,ppt';
    }

    $new_string        = pathinfo($data['name'], PATHINFO_FILENAME) . '.' . strtolower(pathinfo($data['name'], PATHINFO_EXTENSION));
    $extension_allowed = explode(',', $allowed);
    $file_extension    = pathinfo($new_string, PATHINFO_EXTENSION);
    if (!in_array($file_extension, $extension_allowed)) {
        return false;
    }
    if ($data['size'] > $sh['config']['maxUpload']) {
        return false;
    }
    if ($file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'png' || $file_extension == 'gif') {
        $folder   = 'photos';
        $fileType = 'image';
    } else if ($file_extension == 'mp4' || $file_extension == 'mov' || $file_extension == 'webm' || $file_extension == 'flv') {
        $folder   = 'videos';
        $fileType = 'video';
    } else if ($file_extension == 'mp3' || $file_extension == 'wav') {
        $folder   = 'sounds';
        $fileType = 'soundFile';
    } else {
        $folder   = 'files';
        $fileType = 'file';
    }
    if (empty($folder) || empty($fileType)) {
        return false;
    }
    $mime_types = explode(',', str_replace(' ', '', $sh['config']['mime_types'] . ',application/json,application/octet-stream'));
    if (Sh_IsAdmin()) {
        $mime_types = explode(',', str_replace(' ', '', $sh['config']['mime_types'] . ',application/json,application/octet-stream,image/svg+xml'));
    }

    if (!in_array($data['type'], $mime_types)) {
        return false;
    }
    $dir         = "upload/{$folder}/" . date('Y') . '/' . date('m');
    $filename    = $dir . '/' . Sh_GenerateKey() . '_' . date('d') . '_' . md5(time()) . "_{$fileType}.{$file_extension}";
    $second_file = pathinfo($filename, PATHINFO_EXTENSION);
    if (move_uploaded_file($data['file'], $filename)) {
        if ($second_file == 'jpg' || $second_file == 'jpeg' || $second_file == 'png' || $second_file == 'gif') {
            $check_file = getimagesize($filename);
            if (!$check_file) {
                unlink($filename);
            }
            if( $crop == true ){
                if ($type == 1) {
                    if ($second_file != 'gif') {
                        @Sh_CompressImage($filename, $filename, 50);
                    }
                    $explode2  = @end(explode('.', $filename));
                    $explode3  = @explode('.', $filename);
                    $last_file = $explode3[0] . '_small.' . $explode2;

                } else {
                    if (!isset($data['compress']) && $second_file != 'gif') {
                        @Sh_CompressImage($filename, $filename, 10);

                        if ($sh['config']['watermark'] == 1) {
                            watermark_image($filename);
                        }

                    }
                }
            }
        }
        if (!empty($data['crop'])) {
            $crop_image = Sh_Resize_Crop_Image($data['crop']['width'], $data['crop']['height'], $filename, $filename, 60);
        }

        $last_data             = array();
        $last_data['filename'] = $filename;
        $last_data['name']     = $data['name'];
        return $last_data;
    }
}



/**
 * @param $target
 * @return bool|string
 */
function watermark_image($target) {
    global $sh;
    include_once('assets/libraries/SimpleImage-master/src/claviska/SimpleImage.php');
    if ($sh['config']['watermark'] != 1) {
        return false;
    }
    try {
        $image = new \claviska\SimpleImage();

        $image
            ->fromFile($target)
            ->autoOrient()
            ->overlay("./themes/{$sh['config']['theme']}/img/icon.png", 'top left', 1, 30, 30)
            ->toFile($target, 'image/jpeg');

        return true;
    } catch(Exception $err) {
        return $err->getMessage();
    }
}


/**
 * @param $datetime
 * @param $full
 * @return string
 * @throws Exception
 */
function dataTimeFetch($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}


/**
 * @param $source_url
 * @param $destination_url
 * @param $quality
 * @return mixed
 */
function Sh_CompressImage($source_url, $destination_url, $quality) {
    $imgsize = getimagesize($source_url);
    $finfof  = $imgsize['mime'];
    $image_c = 'imagejpeg';
    if ($finfof == 'image/jpeg') {
        $image = @imagecreatefromjpeg($source_url);
    } else if ($finfof == 'image/gif') {
        $image = @imagecreatefromgif($source_url);
    } else if ($finfof == 'image/png') {
        $image = @imagecreatefrompng($source_url);
    } else {
        $image = @imagecreatefromjpeg($source_url);
    }
    $quality = 50;
    if (function_exists('exif_read_data')) {
        $exif = @exif_read_data($source_url);
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $image = @imagerotate($image, 180, 0);
                    break;
                case 6:
                    $image = @imagerotate($image, -90, 0);
                    break;
                case 8:
                    $image = @imagerotate($image, 90, 0);
                    break;
            }
        }
    }
    @imagejpeg($image, $destination_url, $quality);
    return $destination_url;
}


/**
 * @param $max_width
 * @param $max_height
 * @param $source_file
 * @param $dst_dir
 * @param $quality
 * @return bool
 */
function Sh_Resize_Crop_Image($max_width, $max_height, $source_file, $dst_dir, $quality = 80) {
    $imgsize = @getimagesize($source_file);
    $width   = $imgsize[0];
    $height  = $imgsize[1];
    $mime    = $imgsize['mime'];
    $image   = "imagejpeg";
    switch ($mime) {
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            break;
        case 'image/png':
            $image_create = "imagecreatefrompng";
            break;
        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            break;
        default:
            return false;
            break;
    }
    $dst_img = @imagecreatetruecolor($max_width, $max_height);
    $src_img = @$image_create($source_file);
    if (function_exists('exif_read_data')) {
        $exif          = @exif_read_data($source_file);
        $another_image = false;
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $src_img = @imagerotate($src_img, 180, 0);
                    @imagejpeg($src_img, $dst_dir, $quality);
                    $another_image = true;
                    break;
                case 6:
                    $src_img = @imagerotate($src_img, -90, 0);
                    @imagejpeg($src_img, $dst_dir, $quality);
                    $another_image = true;
                    break;
                case 8:
                    $src_img = @imagerotate($src_img, 90, 0);
                    @imagejpeg($src_img, $dst_dir, $quality);
                    $another_image = true;
                    break;
            }
        }
        if ($another_image == true) {
            $imgsize = @getimagesize($dst_dir);
            if ($width > 0 && $height > 0) {
                $width  = $imgsize[0];
                $height = $imgsize[1];
            }
        }
    }
    @$width_new = $height * $max_width / $max_height;
    @$height_new = $width * $max_height / $max_width;
    if ($width_new > $width) {
        $h_point = (($height - $height_new) / 2);
        @imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    } else {
        $w_point = (($width - $width_new) / 2);
        @imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }
    @imagejpeg($dst_img, $dst_dir, $quality);
    if ($dst_img)
        @imagedestroy($dst_img);
    if ($src_img)
        @imagedestroy($src_img);
    return true;
}


/**
 * @param $postData
 * @return false|mixed|string
 */
function GetPaystackData($postData){
    global $sh, $paystackApi;

    if (empty($postData)){
        return false;
    }

    $url = "https://api.paystack.co/transaction/initialize";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  //Post Fields
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $headers = [
        'Authorization: Bearer ' .$paystackApi,
        'Content-Type: application/json',
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $request = curl_exec($ch);

    curl_close($ch);

    if ($request) {

        $result = json_decode($request, true);

        $linkR =  $result['data']['authorization_url'];

    }else{
        $linkR = $sh['config']['site_url'].'/404';
    }

    return $linkR;
}


/**
 * @param $ref
 * @return array|false
 */
function verifyPaystackPayment($ref){
    global $sh, $paystackApi;

    if (empty($ref)){
        return false;
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$ref,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$paystackApi,
            "Cache-Control: no-cache",
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    return $response;
}


/**
 * @param $email
 * @return false|mixed
 */
function Sh_UserIdFromEmail($email) {
    global $sqlConnect;
    if (empty($email)) {
        return false;
    }
    $email = Sh_Secure($email);
    $query = mysqli_query($sqlConnect, "SELECT `user_id` FROM " . T_USERS . " WHERE `email` = '{$email}'");
    return Sh_Sql_Result($query, 0, 'user_id');
}



/**
 * @param $email_code
 * @return false|mixed
 */
function Sh_UserIDFromEmailCode($email_code) {
    global $sqlConnect;
    if (empty($email_code)) {
        return false;
    }
    $email_code = Sh_Secure($email_code);
    $query      = mysqli_query($sqlConnect, "SELECT `user_id` FROM " . T_USERS . " WHERE `email_code` = '{$email_code}'");
    return Sh_Sql_Result($query, 0, 'user_id');
}


/**
 * @param $hostname
 * @param $username
 * @param $password
 * @param $dbname
 * @return string
 */
function check_database_connection($hostname, $username, $password, $dbname) {
    $link = mysqli_connect($hostname, $username, $password, $dbname);
    if (!$link) {
        mysqli_close($link);
        return 'failed';
    }
    $db_selected = mysqli_select_db($link, $dbname);
    if (!$db_selected) {
        mysqli_close($link);
        return "db_not_exist";
    }
    mysqli_close($link);
    return 'Database Successfully Connected.';
}


/**
 * @return void
 */
function configure_database() {
    // write database.php
    $data_db = file_get_contents(ROOT.'/.env');
    session_start();
    $data_db = str_replace('db_name',	$_SESSION['dbname'],	$data_db);
    $data_db = str_replace('db_user',	$_SESSION['username'],	$data_db);
    $data_db = str_replace('db_pass',	$_SESSION['password'],	$data_db);
    $data_db = str_replace('db_host',	$_SESSION['hostname'],	$data_db);
    file_put_contents(ROOT.'/.env', $data_db);
}


/**
 * @return void
 */
function run_blank_sql() {
    global $sqlConnect;
    // Set line to collect lines that wrap
    $templine = '';
    // Read in entire file
    $lines = file(ROOT.'/upload/install.sql');
    // Loop through each line
    foreach ($lines as $line) {
        // Skip it if it's a comment
        if (substr($line, 0, 2) == '--' || $line == '')
            continue;
        // Add this line to the current templine we are creating
        $templine .= $line;
        // If it has a semicolon at the end, it's the end of the query so can process this templine
        if (substr(trim($line), -1, 1) == ';') {
            // Perform the query
            mysqli_query($sqlConnect, $templine);
            // Reset temp variable to empty
            $templine = '';
        }
    }
}



function limit_text($text, $limit) {
    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos   = array_keys($words);
        $text  = substr($text, 0, $pos[$limit]) . '...';
    }
    return $text;
}