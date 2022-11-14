<?php

// You can access the admin panel by using the following url: http://yoursite.com/admincp

require __DIR__ .'/../app/init.php';

$is_admin = Sh_IsAdmin();

//  if ($is_admin == false) {
//  	header("Location: " . Sh_Link('welcome'));
//      exit();
//  }

// autoload admin panel files
require __DIR__ .'/../admin-system/autoload.php';