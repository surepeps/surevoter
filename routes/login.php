<?php

if ($sh['loggedin'] == true) {
    header("Location:" . $sh['config']['site_url']);
    exit();
}

$sh['description'] = $sh['config']['siteDesc'];
$sh['keywords']    = $sh['config']['siteKeywords'];
$sh['page']        = 'login';
$sh['title']       = 'Login | ' . $sh['config']['siteTitle'];
$sh['content']     = Sh_LoadPage('welcome/login');