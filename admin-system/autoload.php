<?php
/**
 * betasouk
 * Created by SureCoder
 * FILE NAME: autoload.php
 * YEAR: 2022
 */


$page = 'dashboard';

// Pages settings
$pages = array(
    'general-settings',
    'transactions',
    'site-setting',
    'manage-users',
    'add-product',
    'category',
    'sub-category',
    'dashboard',
    'products',
    'edit-product',
    'orders',
    'view-order',
    'menu-settings',
    'rating-system',
    'subscribers',
    'create-page',
    'page-list',
    'edit-page',
    'logistics',
    'other-settings',
    'home-settings',
    'ads-management'
);

if (!empty($_GET['page'])) {
    $page = Sh_Secure($_GET['page'], 0);
}

if ($is_admin == false) {
    if (!in_array($page, $mod_pages)) {
        header("Location: " . Sh_Link(''));
        exit();
    }
}

if (in_array($page, $pages)) {
    $page_loaded = Sh_LoadAdminPage("$page/content");
}
if (empty($page_loaded)) {
    header("Location: " . Sh_Link('admin-cpanel'));
    exit();
}

?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin Panel | <?= $sh['config']['siteName'] ?></title>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="">
    <meta property="og:type" content="">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <!--  Restrict Google from crawling this page for search engine  -->
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?= $sh['config']['site_url'].'/'.$sh['config']['partner1_image'] ?>">
    <!-- Template CSS -->
    <link href="<?= Sh_LoadAdminLink('assets/css/main.css') ?>" rel="stylesheet" type="text/css" />

    <script src="<?= Sh_LoadAdminLink('assets/js/vendors/jquery-3.6.0.min.js') ?>"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" defer></script>
    <script src="<?= Sh_LoadAdminLink('assets/js/vendors/bootstrap.bundle.min.js') ?>"></script>

    <!--  DropZone Cdn  -->
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="<?= Sh_LoadAdminLink('assets/jquery.form.min.js') ?>"></script>

    <link href="<?= Sh_LoadAdminLink('plugins/notification/snackbar/snackbar.min.css') ?>" rel="stylesheet" type="text/css" />

    <link href="<?= Sh_LoadAdminLink('assets/css/waitMe.css') ?>" rel="stylesheet" />
    <script src="<?= Sh_LoadAdminLink('assets/js/waitMe.js') ?>"></script>

    <script src="https://kit.fontawesome.com/01b9b7eb33.js" crossorigin="anonymous"></script>

    <script src="<?= Sh_LoadAdminLink('plugins/sweetalerts/sweetalert2.min.js') ?>"></script>
    <link href="<?= Sh_LoadAdminLink('plugins/sweetalerts/sweetalert2.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= Sh_LoadAdminLink('plugins/sweetalerts/sweetalert.css') ?>" rel="stylesheet" type="text/css" />

    <!-- Amsify Plugin -->
    <link rel="stylesheet" href="<?= Sh_LoadAdminLink('plugins/tag/css/amsify.suggestags.css') ?>">
    <script src="<?= Sh_LoadAdminLink('plugins/tag/js/jquery.amsify.suggestags.js') ?>"></script>


    <script src="<?= Sh_LoadAdminLink('assets/js/custom.js') ?>"></script>


    <script>

        function Sh_Ajax_Requests_File(){
            return "<?php echo $sh['config']['site_url'].'/requests.php';?>"
        }


    </script>

    <!--  Form Validation cdn  -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/parsley.js/2.1.2/parsley.min.js"></script>
    <link href="https://parsleyjs.org/src/parsley.css" rel="stylesheet">

    <!--  SureSlugy  -->
    <script src="<?= Sh_LoadAdminLink('plugins/slugify/sureslugy.js') ?>"></script>

    <?php if($page == 'general-settings') : ?>
        <link rel="stylesheet" href="<?= Sh_LoadAdminLink('plugins/coloris/dist/coloris.min.css') ?>" />
        <script src="<?= Sh_LoadAdminLink('plugins/coloris/dist/coloris.min.js') ?>"></script>
    <?php endif; ?>

    <?php if ($page == "site-setting" || $page == 'home-settings' || $page == 'other-settings') { ?>
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />

        <link href="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css" rel="stylesheet" />

        <link href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css" rel="stylesheet" />
    <?php } ?>

    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <?php if ($page == 'site-setting' || $page == 'home-settings' || $page == 'other-settings' ) { ?>
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

    <?php if ($page == "create-page" || $page == "edit-page"){ ?>
        <!-- Main Quill library -->
        <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=qagffr3pkuv17a8on1afax661irst1hbr4e6tbv888sz91jc" referrerpolicy="origin"></script>
        <script src="https://cdn.jsdelivr.net/npm/@tinymce/tinymce-jquery@1/dist/tinymce-jquery.min.js"></script>

    <?php } ?>

    <script src="<?= Sh_LoadAdminLink('assets/js/vendors/chart.js') ?>"></script>
</head>

<body id="loader">

<div class="screen-overlay"></div>

<aside class="navbar-aside" id="offcanvas_aside">
    <div class="aside-top">
        <a href="<?= Sh_Link('admincpanel') ?>" class="brand-wrap">
            <img style="width: 120px; height: 100%;" src="<?= $sh['config']['site_url'].'/'.$sh['config']['site_logo'] ?>" class="logo" alt="<?= $sh['config']['siteName'] ?>">
        </a>
        <div>
            <button class="btn btn-icon btn-aside-minimize"> <i class="text-muted material-icons md-menu_open"></i> </button>
        </div>
    </div>
    <nav>
        <ul class="menu-aside">
            <li class="menu-item <?= ($page == 'dashboard' || $page == '') ? 'active' : '' ?>>">
                <a class="menu-link" href="<?= Sh_Link('admincpanel') ?>"> <i class="icon material-icons md-home"></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="menu-item <?= ($page == 'products') ? 'active' : '' ?>">
                <a class="menu-link" href="<?= Sh_Link('admincpanel/products') ?>"> <i class="icon material-icons md-shopping_bag"></i>
                    <span class="text">Products</span>
                </a>
            </li>
            <li class="menu-item <?= ($page == 'orders') ? 'active' : '' ?>">
                <a class="menu-link" href="<?= Sh_Link('admincpanel/orders') ?>"> <i class="icon material-icons md-shopping_cart"></i>
                    <span class="text">Orders</span>
                </a>
            </li>
            <!--            <li class="menu-item">-->
            <!--                <a class="menu-link" href="--><?//= Sh_Link('admincpanel') ?><!--"> <i class="icon material-icons md-store"></i>-->
            <!--                    <span class="text">Vendors</span>-->
            <!--                </a>-->
            <!--            </li>-->
            <li class="menu-item <?= ($page == 'add-product') ? 'active' : '' ?>">
                <a class="menu-link" href="<?= Sh_Link('admincpanel/add-product') ?>"> <i class="icon material-icons md-add_box"></i>
                    <span class="text">Add product</span>
                </a>
            </li>
            <li class="menu-item <?= ($page == 'manage-users') ? 'active' : '' ?>">
                <a class="menu-link" href="<?= Sh_Link('admincpanel/manage-users') ?>"> <i class="icon material-icons md-person"></i>
                    <span class="text">Accounts</span>
                </a>
            </li>
            <li class="menu-item <?= ($page == 'transactions') ? 'active' : '' ?>">
                <a class="menu-link" href="<?= Sh_Link('admincpanel/transactions') ?>"> <i class="icon material-icons md-monetization_on"></i>
                    <span class="text">Transactions</span>
                </a>
            </li>
            <li class="menu-item <?= ($page == 'logistics') ? 'active' : '' ?>">
                <a class="menu-link" href="<?= Sh_Link('admincpanel/logistics') ?>"> <i class="icon material-icons md-local_shipping"></i>
                    <span class="text">Logistics</span>
                </a>
            </li>
            <li class="menu-item has-submenu">
                <a class="menu-link" href="#"> <i class="icon material-icons md-stars"></i>
                    <span class="text">Categories</span>
                </a>
                <div class="submenu">
                    <a href="<?= Sh_Link('admincpanel/category') ?>">Category</a>
                    <a href="<?= Sh_Link('admincpanel/sub-category') ?>">Sub Category</a>
                </div>
            </li>
            <li class="menu-item">
                <a class="menu-link" href="<?= Sh_Link('admincpanel/rating-system') ?>"> <i class="icon material-icons md-comment"></i>
                    <span class="text">Reviews</span>
                </a>
            </li>
            <li class="menu-item <?= ($page == 'page-list' || $page == 'create-page' || $page == 'edit-page') ? 'active' : '' ?>">
                <a class="menu-link" href="<?= Sh_Link('admincpanel/page-list') ?>"> <i class="icon material-icons md-pie_chart"></i>
                    <span class="text">Extral Pages</span>
                </a>
            </li>
        </ul>
        <hr>
        <ul class="menu-aside">
            <li class="menu-item has-submenu <?= ($page == 'general-settings' || $page == 'site-setting' || $page == 'other-settings' || $page == 'home-settings' ) ? 'active' : '' ?>">
                <a class="menu-link" href="#"> <i class="icon material-icons md-settings"></i>
                    <span class="text">Settings</span>
                </a>
                <div class="submenu">
                    <a class="<?= ($page == 'ads-management') ? 'active' : '' ?>" href="<?= Sh_Link('admincpanel/ads-management') ?>">Ads Management</a>
                    <a class="<?= ($page == 'general-settings') ? 'active' : '' ?>" href="<?= Sh_Link('admincpanel/general-settings') ?>">General</a>
                    <a class="<?= ($page == 'home-settings') ? 'active' : '' ?>" href="<?= Sh_Link('admincpanel/home-settings') ?>">Home</a>
                    <a class="<?= ($page == 'other-settings') ? 'active' : '' ?>" href="<?= Sh_Link('admincpanel/other-settings') ?>">Others</a>
                    <a class="<?= ($page == 'site-setting') ? 'active' : '' ?>" href="<?= Sh_Link('admincpanel/site-setting') ?>">Site</a>
                </div>
            </li>
            <li class="menu-item">
                <a target="_parent" class="menu-link" href="<?= Sh_Link('') ?>"> <i class="icon material-icons md-local_offer"></i>
                    <span class="text">Go to Shop Page </span>
                </a>
            </li>
        </ul>
        <br>
        <br>
    </nav>
</aside>


<div class="modal fade editFormModal" id="editFormModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title allEditModalTitle" id="exampleModalLabel">Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="updateFormInit" id="updateForm_cat" method="post" >
                <div class="modal-body">
                    <div class="" id="updateFormMsg"></div>

                    <div class="allEditModalForm"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="allEditModalBtn" class="btn btn-primary disable_btn allEditModalBtn">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>

<main class="main-wrap">

    <header class="main-header navbar">
        <div class="col-search">
            <form class="searchform">
                <div class="input-group">
                    <input list="search_terms" type="text" class="form-control" placeholder="Search item">
                    <button class="btn btn-light bg" type="button"> <i class="material-icons md-search"></i></button>
                </div>
                <!--                <datalist id="search_terms">-->
                <!--                    <option value="Products">-->
                <!--                    <option value="New orders">-->
                <!--                    <option value="Apple iphone">-->
                <!--                    <option value="Ahmed Hassan">-->
                <!--                </datalist>-->
            </form>
        </div>
        <div class="col-nav">
            <button class="btn btn-icon btn-mobile me-auto" data-trigger="#offcanvas_aside"> <i class="material-icons md-apps"></i> </button>
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link btn-icon darkmode" href="#"> <i class="material-icons md-nights_stay"></i> </a>
                </li>
                <!--                <li class="dropdown nav-item">-->
                <!--                    <a class="dropdown-toggle" data-bs-toggle="dropdown" href="#" id="dropdownLanguage" aria-expanded="false"><i class="material-icons md-public"></i></a>-->
                <!--                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownLanguage">-->
                <!--                        <a class="dropdown-item text-brand" href="#"><img src="--><?//= Sh_LoadAdminLink('assets/imgs/theme/flag-us.png') ?><!--" alt="English">English</a>-->
                <!--                        <a class="dropdown-item" href="#"><img src="--><?//= Sh_LoadAdminLink('assets/imgs/theme/flag-fr.png') ?><!--" alt="Français">Français</a>-->
                <!--                        <a class="dropdown-item" href="#"><img src="--><?//= Sh_LoadAdminLink('assets/imgs/theme/flag-jp.png') ?><!--" alt="Français">日本語</a>-->
                <!--                        <a class="dropdown-item" href="#"><img src="--><?//= Sh_LoadAdminLink('assets/imgs/theme/flag-cn.png') ?><!--" alt="Français">中国人</a>-->
                <!--                    </div>-->
                <!--                </li>-->
                <li class="dropdown nav-item">
                    <a class="dropdown-toggle" data-bs-toggle="dropdown" href="#" id="dropdownAccount" aria-expanded="false">
                        <img class="img-xs rounded-circle" alt="<?= $sh['user']['name']; ?>" src="https://avatar.oxro.io/avatar.svg?name=<?= $sh['user']['name']; ?>">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownAccount">
                        <a class="dropdown-item" href="#"><i class="material-icons md-perm_identity"></i><?= $sh['user']['name'] ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?= Sh_Link('admincpanel/general-settings') ?>"><i class="material-icons md-settings"></i>System Settings</a>
                        <a class="dropdown-item" href="<?= Sh_Link('admincpanel/orders') ?>"><i class="material-icons md-account_balance_wallet"></i>Orders</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="<?= Sh_Link('logout') ?>"><i class="material-icons md-exit_to_app"></i>Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </header>



    <section class="content-main">
        <div class="col-md-12">
            <?php if (empty($paystackApi)): ?>
                <div class="alert alert-danger"><b>Payment Settings has not been made.</b>
                    <a class="btn btn-primary" href="<?= Sh_Link('admincpanel/site-setting') ?>">Complete Payment Settings here</a> </div>
            <?php endif; ?>
        </div>
        <?= $page_loaded ?>
    </section> <!-- content-main end// -->



    <footer class="main-footer font-xs">
        <div class="row pb-30 pt-15">
            <div class="col-sm-6">
                <script>
                    document.write(new Date().getFullYear())
                </script> Product of <b> <a href="https://betasouk.com/"></a> BetaSouk</b> Develop with ❤ by <a href="https://duromedia.com.ng/">Duromedia</a> for business
            </div> 
            <div class="col-sm-6">
                <div class="text-sm-end">
                    All rights reserved
                </div>
            </div>
        </div>
    </footer>
</main>



<script src="<?= Sh_LoadAdminLink('assets/js/vendors/select2.min.js') ?>"></script>
<script src="<?= Sh_LoadAdminLink('assets/js/vendors/perfect-scrollbar.js') ?>"></script>
<script src="<?= Sh_LoadAdminLink('assets/js/vendors/jquery.fullscreen.min.js') ?>"></script>

<!-- Main Script -->
<script src="<?= Sh_LoadAdminLink('assets/js/main.js" type="text/javascript') ?>"></script>
<script src="<?= Sh_LoadAdminLink('plugins/notification/snackbar/snackbar.min.js') ?>"></script>


</body>

</html>


