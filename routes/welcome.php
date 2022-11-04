<?php
/**
 * essential-master
 * Created by SureCoder
 * FILE NAME: welcome.php
 * YEAR: 2022
 */

$sh['description'] = $sh['config']['siteDesc'];
$sh['keywords']    = $sh['config']['siteKeywords'];
$sh['page']        = 'welcome';
$sh['title']       = $sh['config']['siteTitle'];
$sh['content']     = Sh_LoadPage('welcome/content');
