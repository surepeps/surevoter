<?php
/**
 * essential-master
 * Created by SureCoder
 * FILE NAME: 404.php
 * YEAR: 2022
 */


header("HTTP/1.0 404 Not Found");
$sh['description'] = '';
$sh['keywords'] = '';
$sh['page'] = '404';
$sh['title'] = $sh['lang']['sorry_page_not_found'];
$sh['content'] = Sh_LoadPage('404/content');
