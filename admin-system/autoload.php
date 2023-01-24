<?php

$page = 'dashboard';

// Pages settings
$pages = array(
    'manage-user',
    'manage-admin',
    'make-candidate',
    'create-user',
    'create-position',
    'vote-record',
    'dashboard',
    'settings',
    'edit-users',
    'edit-admins',
    'make-admins',
    'page-list',
    'edit-position',
    'auth',
);



if (!empty($_GET['page'])) {
    $page = Sh_Secure($_GET['page'], 0);
}

if ( $sh['admin_loggedin'] ){
    if($page == "auth"){
        header("Location: " . Sh_Link("admincpanel"));
        exit();
    }elseif ($page == "logout"){
        logOutAdmin();
        header("Location: " . Sh_Link("admincpanel/auth"));
        exit();
    }else{
        $pageAuthLoad = $page;
    }
}else{
    if ($page == "auth"){
        $pageAuthLoad = "auth";
    }else{
        header("Location: " . Sh_Link("admincpanel/auth"));
        exit();
    }

}

if (in_array($pageAuthLoad, $pages)) {
    $page_loaded = Sh_LoadAdminPage("$pageAuthLoad/content");
}
if (empty($page_loaded)) {
    header("Location: " . Sh_Link('admin-cpanel'));
    exit();
}



?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <title>Admin Panel | <?= $sh['config']['siteName'] ?></title>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="">
    <meta property="og:type" content="">
    <meta property="og:url" content="">
    <meta property="og:image" content="<?= $sh['config']['site_url'].'/'.$sh['config']['site_logo'] ?>">

    <!--  Restrict Google from crawling this page for search engine  -->
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">


    <script src="<?= Sh_LoadAdminLink('assets/js/jquery-3.6.0.min.js') ?>"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" defer></script>

<!--    <script src="--><?//= Sh_LoadAdminLink('assets/js/bootstrap.bundle.min.js') ?><!--"></script>-->

    <script src="<?= Sh_LoadAdminLink('assets/jquery.form.min.js') ?>"></script>

    <!-- base:css -->
    <link rel="stylesheet" href="<?= Sh_LoadAdminLink('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
    <link rel="stylesheet" href="<?= Sh_LoadAdminLink('assets/vendors/base/vendor.bundle.base.css') ?>">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="<?= Sh_LoadAdminLink('assets/css/style.css') ?>">
    <!-- endinject -->
    <link rel="shortcut icon" href="<?= $sh['config']['site_url'].'/'.$sh['config']['site_logo'] ?>" />

    <link href="<?= Sh_LoadAdminLink('plugins/notification/snackbar/snackbar.min.css') ?>" rel="stylesheet" type="text/css" />
    <script src="<?= Sh_LoadAdminLink('plugins/notification/snackbar/snackbar.min.js') ?>"></script>

    <link href="<?= Sh_LoadAdminLink('assets/css/waitMe.css') ?>" rel="stylesheet" />
    <script src="<?= Sh_LoadAdminLink('assets/js/waitMe.js') ?>"></script>

    <script src="<?= Sh_LoadAdminLink('plugins/sweetalerts/sweetalert2.min.js') ?>"></script>
    <link href="<?= Sh_LoadAdminLink('plugins/sweetalerts/sweetalert2.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= Sh_LoadAdminLink('plugins/sweetalerts/sweetalert.css') ?>" rel="stylesheet" type="text/css" />

    <!--  Form Validation cdn  -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/parsley.js/2.1.2/parsley.min.js"></script>
    <link href="https://parsleyjs.org/src/parsley.css" rel="stylesheet">


      <!--  DropZone Cdn  -->
      <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
      <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

      <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
      <?php if ($page == 'settings' ) { ?>
          <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
          <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />

          <link href="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css" rel="stylesheet" />

          <link href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css" rel="stylesheet" />

          <!-- include FilePond library -->
          <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

          <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js"></script>

          <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>

          <script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js"></script>

          <script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.min.js"></script>

          <script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.min.js"></script>

          <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.min.js"></script>

          <script src="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.js"></script>

          <!-- include FilePond plugins -->
          <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>

          <script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>

          <!-- include FilePond jQuery adapter -->
          <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>

      <?php } ?>



    <script>
      function Sh_Ajax_Requests_File(){
            return "<?php echo $sh['config']['site_url'].'/requests.php';?>"
        }
    </script>

      <script src="<?= Sh_LoadAdminLink('assets/js/custom.js') ?>"></script>
  </head>
  <body id="loader">
    <div class="px-5 container-scroller">
				
		<!-- partial:partials/_horizontal-navbar.html -->
   <?php  if($sh['admin_loggedin']){ ?>
    <div class="horizontal-menu">
      <nav class="navbar top-navbar col-lg-12 col-12 p-0">
        <div class="container-fluid">
          <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
            <ul class="navbar-nav navbar-nav-left">
              <li class="nav-item ms-0 me-5 d-lg-flex d-none">
                <a href="#" class="nav-link horizontal-nav-left-menu"><i class="mdi mdi-format-list-bulleted"></i></a>
              </li>
            </ul>
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="<?= $sh['site_url']  ?>"><img src="<?= $sh['config']['site_url'].'/'.$sh['config']['site_logo'] ?>" alt="logo"/></a>
                <a class="navbar-brand brand-logo-mini" href="<?= $sh['site_url']  ?>"><img src="<?= $sh['config']['site_url'].'/'.$sh['config']['site_logo'] ?>" alt="logo"/></a>
            </div>
            <ul class="navbar-nav navbar-nav-right">

                <li class="nav-item nav-profile dropdown">
                  <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                    <span class="nav-profile-name"><?= $sh['admin']['name'] ?></span>
                    <span class="online-status"></span>
                    <img src="<?= $sh['config']['site_url'].'/'.$sh['config']['site_logo'] ?>" alt="profile"/>
                  </a>
                  
                </li>
                <li class="nav-item nav-profile dropdown">
                    <a href="<?= $sh['site_url'].'/admincpanel/logout'  ?>" class="dropdown-item">
                        <i class="mdi mdi-logout text-primary"></i>
                        Logout
                    </a>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
              <span class="mdi mdi-menu"></span>
            </button>
          </div>
        </div>
      </nav>


      <nav class="bottom-navbar">
        <div class="container">
            <ul class="nav page-navigation">
              <li class="nav-item">
                <a class="nav-link" href="<?= $sh['site_url'].'/admincpanel'  ?>">
                  <i class="mdi mdi-file-document-box menu-icon"></i>
                  <span class="menu-title">Dashboard</span>
                </a>
              </li>
              <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="mdi mdi-account menu-icon"></i>
                    <span class="menu-title">Users</span>
                    <i class="menu-arrow"></i>
                  </a>
                  <div class="submenu">
                      <ul>
                          <li class="nav-item"><a class="nav-link" href="<?= $sh['site_url'].'/admincpanel/manage-user'  ?>">Manage Users</a></li>
                          <li class="nav-item"><a class="nav-link" href="<?= $sh['site_url'].'/admincpanel/create-user'  ?>">Create User</a></li>
                      </ul>
                  </div>
              </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="mdi mdi-account menu-icon"></i>
                        <span class="menu-title">Admin</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="submenu">
                        <ul>
                            <li class="nav-item"><a class="nav-link" href="<?= $sh['site_url'].'/admincpanel/manage-admin'  ?>">Manage Admins</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= $sh['site_url'].'/admincpanel/make-admins'  ?>">Create Admin</a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="<?= $sh['site_url'].'/admincpanel/create-position'  ?>" class="nav-link">
                        <i class="mdi mdi-bookmark-check menu-icon"></i>
                        <span class="menu-title">Positions</span>
                        <i class="menu-arrow"></i>
                    </a>
                </li>

              <li class="nav-item">
                  <a href="<?= $sh['site_url'].'/admincpanel/vote-record'  ?>" class="nav-link">
                    <i class="mdi mdi-bookmark-check menu-icon"></i>
                    <span class="menu-title">Vote Records</span>
                    <i class="menu-arrow"></i>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="<?= $sh['site_url'].'/admincpanel/settings'  ?>" class="nav-link">
                    <i class="mdi mdi-settings menu-icon"></i>
                    <span class="menu-title">Settings</span>
                    <i class="menu-arrow"></i>
                  </a>
              </li>
            </ul>
        </div>
      </nav>
    </div>
  <?php } ?>

    <!-- partial -->
		<div class="container-fluid page-body-wrapper">
			<div class="main-panel">

                 <?= $page_loaded ?>
				<!-- content-wrapper ends -->
		
                <!-- partial:partials/_footer.html -->

        <?php  if($sh['admin_loggedin']){ ?>
        <footer class="footer">
          <div class="footer-wrap">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted text-center text-sm-left d-block d-sm-inline-block"> <?= $sh['config']['site_footer'] ?> </span>
            </div>
          </div>
        </footer>
        <?php } ?>
				<!-- partial -->
			</div>
			<!-- main-panel ends -->
		</div>
		<!-- page-body-wrapper ends -->
    </div>
		<!-- container-scroller -->
    <!-- base:js -->
<!--    <script src="--><?//= Sh_LoadAdminLink('assets/vendors/base/vendor.bundle.base.js') ?><!--"></script>-->
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="<?= Sh_LoadAdminLink('assets/js/template.js') ?>"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <!-- End plugin js for this page -->
    <script src="<?= Sh_LoadAdminLink('assets/vendors/chart.js/Chart.min.js') ?>"></script>
    <script src="<?= Sh_LoadAdminLink('assets/vendors/progressbar.js/progressbar.min.js') ?>"></script>
		<script src="<?= Sh_LoadAdminLink('assets/vendors/chartjs-plugin-datalabels/chartjs-plugin-datalabels.js') ?>"></script>
		<script src="<?= Sh_LoadAdminLink('assets/vendors/justgage/raphael-2.1.4.min.js') ?>"></script>
		<script src="<?= Sh_LoadAdminLink('assets/vendors/justgage/justgage.js') ?>"></script>
    <script src="<?= Sh_LoadAdminLink('assets/js/jquery.cookie.js') ?>" type="text/javascript"></script>
    <!-- Custom js for this page-->
    <script src="<?= Sh_LoadAdminLink('assets/js/dashboard.js') ?>"></script>

    <!-- End custom js for this page-->
  </body>
</html>