<?php

/**
 * @param $page_url
 * @return false|string
 */
function Sh_LoadAdminPage($page_url = '') {
    global $sh,$db;
    $page = __DIR__ .'/../../admin-system/pages/' . $page_url . '.phtml';
    $page_content = '';
    ob_start();
    require($page);
    $page_content = ob_get_contents();
    ob_end_clean();
    return $page_content;
}


/**
 * @param $cond
 * @return array
 */
function getallUserAccountData($cond = ""){
    global $sqlConnect;

    if( isset($cond) || !empty($cond) ){
        $query = "SELECT * FROM ". T_USERS." ".$cond;
    }else {
        $query = "SELECT * FROM ". T_USERS." ";
    }

    $users = [];

    $result = mysqli_query($sqlConnect, $query);
    while($user_data = mysqli_fetch_assoc($result)){
        $users[] = Sh_UserData($user_data['user_id']);
    }
    return $users;

}


/**
 * @param $data
 * @param $user_id
 * @return bool|mysqli_result
 */
function UpdateUserData($data,$user_id){
    global $sh, $sqlConnect;

    if(empty($data) || !isset($data)){
        return false;
    }

    if(empty($user_id) || !isset($user_id)){
        return false;
    }


    $queryU = "UPDATE ".T_USERS." SET ";
    $i = 0;
    foreach($data as $key => $value)
    {
        $i++;

        if(sizeof($data) > $i) {
            $queryU .= "`".$key."`= '{$value}', ";
        } else {
            $queryU .= "`".$key."`= '{$value}' ";
        }

    }
    $queryU .= " WHERE `user_id` = $user_id ";

    $queryUp  = mysqli_query($sqlConnect,$queryU);

    $actionTaken = array(
        'user_id' => $sh['user']['user_id'],
        'page' => "User",
        'action_description' => "User data Updated",
        'action_type' => "update",
    );

    saveUserActions($actionTaken);

    return $queryUp;

}



/**
 * @param $data
 * @return false|int|string
 */
function saveUserActions($data){
    global $sqlConnect;

    if(empty($data) || !isset($data)){
        return false;
    }

    $data['status'] = 1;

    $fields = '`' . implode('`,`', array_keys($data)) . '`';
    $data   = '\'' . implode('\', \'', $data) . '\'';
    $query  = mysqli_query($sqlConnect, "INSERT INTO " . T_ACTIONS . " ({$fields}) VALUES ({$data})");
    $aCTION_id = mysqli_insert_id($sqlConnect);

    return $aCTION_id;

}


/**
 * @param $user_id
 * @return false|int
 */
function DeleteUserData($user_id){

    global $sqlConnect, $sh;

    if($user_id == 0 || !isset($user_id) || !is_numeric($user_id)){
        return false;
    }

    $query = mysqli_query($sqlConnect, "DELETE FROM ". T_USERS ." WHERE `user_id` = $user_id");

    if($query){
        $actionTaken = array(
            'user_id' => $sh['user']['user_id'],
            'page' => "User",
            'action_description' => "User account deleted",
            'action_type' => "delete",
        );

        saveUserActions($actionTaken);

        return 1;
    }else{
        return 0;
    }

}




/**
 * @param $registration_data
 * @return false|int|string
 */
function Sh_RegisterUser($registration_data) {
    global $sh, $sqlConnect;

    if (empty($registration_data)) {
        return false;
    }

    if ($sh['config']['user_registration'] == 0) {
        return false;
    }

    $registration_data['password']   = Sh_Secure(password_hash($registration_data['password'], PASSWORD_DEFAULT));

    $fields  = '`' . implode('`,`', array_keys($registration_data)) . '`';
    $data    = '\'' . implode('\', \'', $registration_data) . '\'';
    $query   = mysqli_query($sqlConnect, "INSERT INTO " . T_USERS . " ({$fields}) VALUES ({$data})");
    $user_id = mysqli_insert_id($sqlConnect);

    if ($query) {
        return $user_id;
    } else {
        return 0;
    }

}

