<?php

require_once('app/init.php');

if (!empty($_GET['ref']) && $sh['loggedin'] == false && !isset($_COOKIE['src'])) {
    $get_ip = get_ip_address();
    if (!isset($_SESSION['ref']) && !empty($get_ip)) {
        $_GET['ref'] = Sh_Secure($_GET['ref']);
        $ref_user_id = Sh_UserIdFromUsername($_GET['ref']);
        $user_data = Sh_UserData($ref_user_id);
        if (!empty($user_data)) {
            if (ip_in_range($user_data['ip_address'], '/24') === false && $user_data['ip_address'] != $get_ip) {
                $_SESSION['ref'] = $user_data['username'];
            }
        }
    }
}

if (!isset($_COOKIE['src'])) {
    @setcookie('src', '1', time() + 31556926, '/');
}

$page = '';

if ($sh['loggedin'] == true && !isset($_GET['link1'])) {
    $page = 'home';
} elseif (isset($_GET['link1'])) {
    $page = $_GET['link1'];
}



if ((!isset($_GET['link1']) && $sh['loggedin'] == false) || (isset($_GET['link1']) && $sh['loggedin'] == false && $page == 'home')) {
    $page = 'welcome';
}


if ($sh['loggedin'] == true) {

    switch ($page) {
        case 'welcome':
            include('routes/welcome.php');
            break;
        case 'home':
            include('routes/welcome.php');
            break;
        case 'login':
            include('routes/login.php');
            break;
        case 'logout':
            include('routes/logout.php');
            break;

    }

} else {

    switch ($page) {
        case 'welcome':
            include('routes/welcome.php');
            break;
        case 'register':
            include('routes/register.php');
            break;
        case 'login':
            include('routes/login.php');
            break;
        case 'logout':
            include('routes/logout.php');
            break;    
    }

}

if (empty($sh['content'])) {
    include('routes/404.php');
}

if ($sh['page'] == 'login') {
    echo Sh_LoadPage('login_viewer');
}else{
    echo Sh_LoadPage('page_viewer');
}



mysqli_close($sqlConnect);
unset($sh);