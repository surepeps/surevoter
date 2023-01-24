<?php



/**
 * @param $update_name
 * @param $value
 * @return bool
 */
function Sh_SaveConfig($update_name, $value) {
    global $sh, $config, $sqlConnect;
    if ($sh['admin_loggedin'] == false) {
        return false;
    }
    if (!array_key_exists($update_name, $config)) {
        return false;
    }
    $update_name = Sh_Secure($update_name);
    $value       = mysqli_real_escape_string($sqlConnect, $value);
    $query_one   = " UPDATE " . T_CONFIG . " SET `value` = '{$value}' WHERE `name` = '{$update_name}'";
    $query       = mysqli_query($sqlConnect, $query_one);
    if ($query) {
        return true;
    } else {
        return false;
    }
}


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
 * @param $cond
 * @return array
 */
function getallAdminAccountData($cond = ""){
    global $sqlConnect;

    if( isset($cond) || !empty($cond) ){
        $query = "SELECT * FROM ". T_ADMINS." ".$cond;
    }else {
        $query = "SELECT * FROM ". T_ADMINS." ";
    }

    $admins = [];

    $result = mysqli_query($sqlConnect, $query);
    while($admin_data = mysqli_fetch_assoc($result)){
        $admins[] = $admin_data;
    }
    return $admins;

}


function getSingleAdminData($admin_id){
    global $sqlConnect;

    $query = "SELECT * FROM ". T_ADMINS. " WHERE `admin_id` = $admin_id ";
    $result = mysqli_query($sqlConnect, $query);
    return mysqli_fetch_assoc($result);
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
        'user_id' => $sh['admin']['admin_id'],
        'page' => "User",
        'action_description' => "User data Updated",
        'action_type' => "update",
    );

    saveUserActions($actionTaken);

    return $queryUp;

}



/**
 * @param $data
 * @param $admin_id
 * @return bool|mysqli_result
 */
function UpdateAdminData($data,$admin_id){
    global $sh, $sqlConnect;

    if(empty($data) || !isset($data)){
        return false;
    }

    if(empty($admin_id) || !isset($admin_id)){
        return false;
    }


    $queryU = "UPDATE ".T_ADMINS." SET ";
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
    $queryU .= " WHERE `admin_id` = $admin_id ";

    $queryUp  = mysqli_query($sqlConnect,$queryU);

    $actionTaken = array(
        'user_id' => $sh['admin']['admin_id'],
        'page' => "User",
        'action_description' => "User data Updated",
        'action_type' => "update",
    );

    saveUserActions($actionTaken);

    return $queryUp;

}



/**
 * @param $data
 * @param $post_id
 * @return bool|mysqli_result
 */
function UpdatePositionData($data,$post_id){
    global $sh, $sqlConnect;

    if(empty($data) || !isset($data)){
        return false;
    }

    if(empty($post_id) || !isset($post_id)){
        return false;
    }


    $queryU = "UPDATE ".T_POSTS_T." SET ";
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
    $queryU .= " WHERE `post_id` = $post_id ";

    $queryUp  = mysqli_query($sqlConnect,$queryU);

    $actionTaken = array(
        'user_id' => $sh['admin']['admin_id'],
        'page' => "Edit Postion",
        'action_description' => "Position data Updated",
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
            'user_id' => $sh['admin']['admin_id'],
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




function resetVotes($user_id = 0){
    global $sqlConnect, $sh;

    if ($user_id > 0){
        $whereCondV = " WHERE `voter_id` =  $user_id";
        $whereCondUV = " WHERE `user_id` = $user_id";
    }else{
        $whereCondV = "";
        $whereCondUV = "";
    }

    $query = mysqli_query($sqlConnect, "DELETE FROM ". T_VOTES_T. " ". $whereCondV);

    if($query){

        $queryU = "UPDATE ".T_USERS." SET `vote_status` = 0 ". $whereCondV;

        $queryUp  = mysqli_query($sqlConnect,$queryU);

        $actionTaken = array(
            'user_id' => $sh['admin']['admin_id'],
            'page' => "Votes",
            'action_description' => "Votes Reset",
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


/**
 * @param $registration_data
 * @return false|int|string
 */
function Sh_CreateAdminData($registration_data) {
    global $sh, $sqlConnect;

    if (empty($registration_data)) {
        return false;
    }

    $registration_data['password']   = Sh_Secure(password_hash($registration_data['password'], PASSWORD_DEFAULT));

    $fields  = '`' . implode('`,`', array_keys($registration_data)) . '`';
    $data    = '\'' . implode('\', \'', $registration_data) . '\'';
    $query   = mysqli_query($sqlConnect, "INSERT INTO " . T_ADMINS . " ({$fields}) VALUES ({$data})");
    $R_id = mysqli_insert_id($sqlConnect);

    if ($query) {
        return $R_id;
    } else {
        return 0;
    }

}


/**
 * @param $data
 * @return false|int|string
 */
function createCandidate($data){
    global $sqlConnect;

    if(empty($data) || !isset($data)){
        return false;
    }

    $data['status'] = 1;

    $fields = '`' . implode('`,`', array_keys($data)) . '`';
    $data   = '\'' . implode('\', \'', $data) . '\'';
    $query  = mysqli_query($sqlConnect, "INSERT INTO " . T_CANDI_T . " ({$fields}) VALUES ({$data})");
    $response_id = mysqli_insert_id($sqlConnect);

    return $response_id;

}


/**
 * @param $data
 * @return false|int|string
 */
function createPosition($data){
    global $sqlConnect;

    if(empty($data) || !isset($data)){
        return false;
    }

    $data['status'] = 1;

    $fields = '`' . implode('`,`', array_keys($data)) . '`';
    $data   = '\'' . implode('\', \'', $data) . '\'';
    $query  = mysqli_query($sqlConnect, "INSERT INTO " . T_POSTS_T . " ({$fields}) VALUES ({$data})");
    $response_id = mysqli_insert_id($sqlConnect);

    return $response_id;

}




function UpdateCandidateData($data,$cand_id){
    global $sh, $sqlConnect;

    if(empty($data) || !isset($data)){
        return false;
    }

    if(empty($cand_id) || !isset($cand_id)){
        return false;
    }


    $queryU = "UPDATE ".T_CANDI_T." SET ";
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
    $queryU .= " WHERE `candidate_id` = $cand_id ";

    $queryUp  = mysqli_query($sqlConnect,$queryU);

    return $queryUp;

}



function DeleteCandidateData($cand_id){

    global $sqlConnect;

    if($cand_id == 0 || !isset($cand_id) || !is_numeric($cand_id)){
        return false;
    }

    $query = mysqli_query($sqlConnect, "DELETE FROM ". T_CANDI_T ." WHERE `candidate_id` = $cand_id");

    if($query){
        return 1;
    }else{
        return 0;
    }

}


function DeletePositionData($post_id){

    global $sqlConnect;

    if($post_id == 0 || !isset($post_id) || !is_numeric($post_id)){
        return false;
    }

    $query = mysqli_query($sqlConnect, "DELETE FROM ". T_POSTS_T ." WHERE `post_id` = $post_id");

    if($query){
        return 1;
    }else{
        return 0;
    }

}


function DeleteAdminData($admin_id){

    global $sqlConnect;

    if($admin_id == 0 || !isset($admin_id) || !is_numeric($admin_id)){
        return false;
    }

    $query = mysqli_query($sqlConnect, "DELETE FROM ". T_ADMINS ." WHERE `admin_id` = $admin_id");

    if($query){
        return 1;
    }else{
        return 0;
    }

}


/**
 * @param $year
 * @return array|false
 */
function dashboardRecords(){
    global $sqlConnect;

    // get count of all users
    $usersCountQuery = mysqli_query($sqlConnect, "SELECT COUNT(`user_id`) as `usc` FROM " . T_USERS);
    $pUsersCountQuery = mysqli_fetch_assoc($usersCountQuery);

    // get count of all candidates
    $candidatesCountQuery = mysqli_query($sqlConnect, "SELECT COUNT(`candidate_id`) as `csc` FROM " . T_CANDI_T);
    $pCandidatesCountQuery = mysqli_fetch_assoc($candidatesCountQuery);

    // get count of all positions
    $postsCountQuery = mysqli_query($sqlConnect, "SELECT COUNT(`post_id`) as `psc` FROM " . T_POSTS_T);
    $pPostsCountQuery = mysqli_fetch_assoc($postsCountQuery);

    // get count of all votes
    $votesCountQuery = mysqli_query($sqlConnect, "SELECT COUNT(`vote_id`) as `vsc` FROM " . T_VOTES_T);
    $pVotesCountQuery = mysqli_fetch_assoc($votesCountQuery);


    return [
        'userCounts' => $pUsersCountQuery['usc'],
        'candidateCounts' => $pCandidatesCountQuery['csc'],
        'postCounts' => $pPostsCountQuery['psc'],
        'voteCounts' => $pVotesCountQuery['vsc']
    ];

}



function getCounterRecords($id, $type, $by = ''){
    global $sqlConnect;

    if (empty($id)){
        return false;
    }

    if (empty($type)){
        return false;
    }

    $typeTt = [
        'post' => T_POSTS_T,
        'candidate' => T_CANDI_T,
        'vote' => T_VOTES_T,
        'user' => T_USERS,
    ];


    $byQ = '';
    if ($by != ''){
        $byQ = " WHERE ${by} = $id";
    }

    if (array_key_exists($type, $typeTt)){
        $t_id = $type."_id";
        $tableN = $typeTt[$type];

        $counterQuery = mysqli_query($sqlConnect, "SELECT COUNT($t_id) as `usc` FROM " . $tableN. " ".$byQ);
        $pCounterQuery = mysqli_fetch_assoc($counterQuery);

        return $pCounterQuery['usc'];
    }else{
        return false;
    }

}


/**
 * @param $post_title
 * @return bool
 */
function getPositionExistence($post_title){
    global $sqlConnect;
    if (empty($post_title)) {
        return false;
    }
    $post_title = Sh_Secure($post_title);
    $query    = mysqli_query($sqlConnect, "SELECT COUNT(`post_id`) FROM " . T_POSTS_T . "  WHERE (`title` = '{$post_title}' AND `title` LIKE '%{$post_title}%') ");
    return (Sh_Sql_Result($query, 0) == 1) ? true : false;
}


/**
 * @param $data
 * @return false|int|string
 */
function createVoteRecord($data){
    global $sqlConnect;

    if(empty($data) || !isset($data)){
        return false;
    }

    $data['status'] = 1;

    $fields = '`' . implode('`,`', array_keys($data)) . '`';
    $data   = '\'' . implode('\', \'', $data) . '\'';
    $query  = mysqli_query($sqlConnect, "INSERT INTO " . T_VOTES_T . " ({$fields}) VALUES ({$data})");
    $response_id = mysqli_insert_id($sqlConnect);

    return $response_id;

}


/**
 * @param $username
 * @param $password
 * @return bool
 */
function Sh_AdminLogin($username, $password){
    global $sqlConnect;

    if (empty($username) || empty($password)) {
        return false;
    }

    $username = Sh_Secure($username);
    $query_hash = mysqli_query($sqlConnect, "SELECT * FROM " . T_ADMINS . " WHERE `username` = '{$username}' ");
    $mysqli_hash_upgrade = mysqli_fetch_assoc($query_hash);

    if (!$mysqli_hash_upgrade) {
        return false;
    }

    $login_password = '';
    $hash = 'md5';
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
        $query = mysqli_query($sqlConnect, "SELECT COUNT(`admin_id`) FROM " . T_ADMINS . " WHERE `username` = '{$username}'  AND `password` = '{$login_password}'");
        if (Sh_Sql_Result($query, 0) == 1) {
            if ($hash == 'sha1' || $hash == 'md5') {
                $new_password = Sh_Secure(password_hash($password, PASSWORD_DEFAULT));
                $query_ = mysqli_query($sqlConnect, "UPDATE " . T_ADMINS . " SET `password` = '$new_password' WHERE `username` = '{$username}' ");
            }
            return true;
        }
        return false;
    }

    return false;

}



function logOutAdmin(){
    global $sqlConnect;

    session_unset();
    if (!empty($_SESSION['admin_id'])) {
        $_SESSION['admin_id'] = '';
        $query = mysqli_query($sqlConnect, "DELETE FROM " .  T_APP_SESSIONS . " WHERE `session_id` = '" . Sh_Secure($_SESSION['admin_id']) . "'");
    }
    session_destroy();
    if (isset($_COOKIE['admin_id'])) {
        $query = mysqli_query($sqlConnect, "DELETE FROM " .  T_APP_SESSIONS . " WHERE `session_id` = '" . Sh_Secure($_COOKIE['admin_id']) . "'");
        $_COOKIE['admin_id'] = '';
        unset($_COOKIE['admin_id']);
        setcookie('admin_id', '', -1);
        setcookie('admin_id', '', -1,'/');
    }
    $_SESSION = array();
    unset($_SESSION);
}