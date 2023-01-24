<?php

if ($sh['loggedin'] == false) {
    header("Location:" . $sh['config']['site_url'].'/login');
    exit();
}

$sh['description'] = $sh['config']['siteDesc'];
$sh['keywords']    = $sh['config']['siteKeywords'];
$sh['page']        = 'Voting Page';
$sh['title']       = $sh['config']['siteTitle'];
$sh['content']     = Sh_LoadPage('vote/content');
