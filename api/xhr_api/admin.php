<?php

if ($f == "admin"){



    if ($s == "auth_admin"){


        $error_icon = "";

        $data_ = array();

        if (!empty($_SESSION['admin_id'])) {
            $_SESSION['admin_id'] = '';
            unset($_SESSION['admin_id']);
        }

        if (!empty($_COOKIE['admin_id'])) {
            $_COOKIE['admin_id'] = '';
            unset($_COOKIE['admin_id']);
            setcookie('admin_id', '', -1);
            setcookie('admin_id', '', -1,'/');
        }

        if (isset($_POST['username']) && isset($_POST['password'])) {

            $username = Sh_Secure($_POST['username']);
            $password = $_POST['password'];
            $result   = Sh_AdminLogin($username, $password);

            if ($result === false) {
                $errors[] = $error_icon . $sh['lang']['incorrect_username_or_password_label'];
                if ($sh['config']['prevent_system'] == 1) {
                    ShAddBadLoginLog();
                }
            }

            if (empty($errors)) {

                $adminid = Sh_AdminIdForLogin($username);
                $ip = Sh_Secure(get_ip_address());
                $update = mysqli_query($sqlConnect, "UPDATE " . T_ADMINS . " SET `ip_address` = '{$ip}' WHERE `admin_id` = '{$adminid}'");
                $session = Sh_CreateLoginSession(Sh_AdminIdForLogin($username));
                $_SESSION['admin_id'] = $session;
                setcookie("admin_id", $session, time() + (10 * 365 * 24 * 60 * 60));
                $data = array(
                    'status' => 200,
                    'message' => "Successfully Logged In"
                );

                if (!empty($_GET['page'])) {
                    $redirectTo = Sh_Secure($_GET['page'], 0);;
                } else {
                    $redirectTo = '';
                }

                $data['location'] = $sh['config']['site_url'] . '/admincpanel/'.$redirectTo;

            }

        }

        header("Content-type: application/json");

        if (!empty($errors)) {
            echo json_encode(array(
                'errors' => $errors
            ));
        } else {
            echo json_encode($data);
        }

        exit();


    }



    if ($s == "add_new_admin"){

        $full_name = Sh_Secure($_POST['full_name']);
        $username = Sh_Secure($_POST['username']);
        $password = $_POST['password'];
        $phoneNumber = Sh_Secure($_POST['phone_number']);

        $errors = false;
        if($full_name == "" || $username == "" || $password == ""){
            $errors = true;
            $data = array(
                'status' => 400,
                'message' => "Please fill in all required fields",
            );

        }

        if (!$errors) {

            if(getAdminExistence($username)){
                $data = array(
                    'status' => 400,
                    'message' => $username." ".$sh['lang']['found_already'],
                );
            }else{

                $adminData = [
                    'username' => $username,
                    'name' => $full_name,
                    'phone_number' => $phoneNumber,
                    'password' => $password,
                    'ip_address' => get_ip_address()
                ];

                $admin_id = Sh_CreateAdminData($adminData);

                if ($admin_id > 0){
                    $data = array(
                        'status' => 200,
                        'message' => "Admin Created Successfully"
                    );
                }else{
                    $data = array(
                        'status' => 400,
                        'message' => "Error While Processing your Request"
                    );
                }

            }


        }


        header("Content-type: application/json");
        echo json_encode($data);
        exit();

    }



    if ($s == "delete_admin_acct"){

        $error = 0;
        if (!isset($_GET['admin_id'])){
            $error = 1;
            $message = "Please Provide Admin Id";
        }

        if (!getAdminData(Sh_Secure($_GET['admin_id']))){
            $error = 1;
            $message = "Invalid Admin Id";
        }

        //reset all votes of all users
        if (!$error){

            DeleteAdminData($_GET['admin_id']);

            $data = array(
                'status' => 200,
                'message' => "Admin Successfully deleted",
            );

        }else{
            $data = array(
                'status' => 400,
                'message' => $message,
            );
        }


        header("Content-type: application/json");
        echo json_encode($data);
        exit();

    }



    if ($s == "update-admin"){

        $full_name = Sh_Secure($_POST['full_name']);
        $username = Sh_Secure($_POST['username']);
        $phoneNumber = Sh_Secure($_POST['phone_number']);
        $accountAccess = Sh_Secure($_POST['active']);

        $admin_id = Sh_Secure($_POST['admin_id']);


        // check if title is existing already
        $exist_admin = getAdminExistenceForUpdate($username,$admin_id);

        if ($exist_admin) {

            $data = array(
                'status' => 400,
                'message' => $username." ".$sh['lang']['found_already'],
            );

        }else{

            $adminData = [
                'username' => $username,
                'name' => $full_name,
                'phone_number' => $phoneNumber,
                'status' => $accountAccess
            ];

            $updAdmin = UpdateAdminData($adminData, $admin_id);

            if ($updAdmin){

                $data = array(
                    'status' => 200,
                    'message' => "Account ".$sh['lang']['general_update_success_message'],
                );

            }else{

                $data = array(
                    'status' => 400,
                    'message' => $sh['lang']['general_update_error_message'],
                );

            }


        }


        header("Content-type: application/json");
        echo json_encode($data);
        exit();

    }
    
    
    
    if ($s == "update-admin-pass"){

        $Inppassword = $_POST['password'];

        $admin_id = Sh_Secure($_POST['admin_id']);

        $password   = Sh_Secure(password_hash($Inppassword, PASSWORD_DEFAULT));

        $adminData = [
            'password' => $password
        ];

        $updAdmin = UpdateAdminData($adminData, $admin_id);

        if ($updAdmin){

            $data = array(
                'status' => 200,
                'message' => "Account Password ".$sh['lang']['general_update_success_message'],
            );

        }else{

            $data = array(
                'status' => 400,
                'message' => $sh['lang']['general_update_error_message'],
            );

        }





        header("Content-type: application/json");
        echo json_encode($data);
        exit();

    }


}