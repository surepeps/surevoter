<?php

if ($f == 'login') {

    $error_icon = "";

    if (!empty($_SESSION['user_id'])) {
        $_SESSION['user_id'] = '';
        unset($_SESSION['user_id']);
    }

    if (!empty($_COOKIE['user_id'])) {
        $_COOKIE['user_id'] = '';
        unset($_COOKIE['user_id']);
        setcookie('user_id', '', -1);
        setcookie('user_id', '', -1,'/');
    }

    $data_ = array();
    $phone = 0;

    if (isset($_POST['username']) && isset($_POST['password'])) {
        if ($sh['config']['prevent_system'] == 1) {
            if (!ShCanLogin()) {
                $errors[] = $error_icon . $sh['lang']['login_attempts'];
                header("Content-type: application/json");
                echo json_encode(array(
                    'errors' => $errors
                ));
                exit();
            }
        }
        $username = Sh_Secure($_POST['username']);
        $password = $_POST['password'];
        $result   = Sh_Login($username, $password);

        
        if ($result === false) {
            $errors[] = $error_icon . $sh['lang']['incorrect_username_or_password_label'];
            if ($sh['config']['prevent_system'] == 1) {
                ShAddBadLoginLog();
            }
        } else if (Sh_UserInactive($_POST['username']) === true) {
            $errors[] = $error_icon . $sh['lang']['account_disbaled_contanct_admin_label'];
        }
//        else if (Sh_VerfiyIP($_POST['username']) === false) {
//            $_SESSION['code_id'] = Sh_UserIdForLogin($username);
//            $data_               = array(
//                'status' => 600,
//                'location' => Sh_Link('unusual-login')
//            );
//            $phone = 1;
//        }
        else if (Sh_UserActive($_POST['username']) === false) {
            $_SESSION['code_id'] = Sh_UserIdForLogin($username);
            $data_               = array(
                'status' => 600,
                'location' => Sh_Link('user-activation')
            );
            $phone = 1;
        }
        if (empty($errors) && $phone == 0) {
            $userid              = Sh_UserIdForLogin($username);
            $ip                  = Sh_Secure(get_ip_address());
            $update              = mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET `ip_address` = '{$ip}' WHERE `user_id` = '{$userid}'");
            $session             = Sh_CreateLoginSession(Sh_UserIdForLogin($username));
            $_SESSION['user_id'] = $session;
            setcookie("user_id", $session, time() + (10 * 365 * 24 * 60 * 60));
            $data = array(
                'status' => 200,
                'message' => "Successfully Logged In"
            );

            if (!empty($_POST['last_url'])) {
                $data['location'] = $_POST['last_url'];
            } else {
                $redirect = '';
                if (isset($_GET['redirect'])){
                    $redirect = '/'.$_GET['redirect'];
                }
                $data['location'] = $sh['config']['site_url'] . $redirect;

            }
            $user_data = Sh_UserData($userid);
        }
    }

    header("Content-type: application/json");

    if (!empty($errors)) {
        echo json_encode(array(
            'errors' => $errors
        ));
    } else if (!empty($data_)) {
        echo json_encode($data_);
    } else {
        echo json_encode($data);
    }


    exit();
}