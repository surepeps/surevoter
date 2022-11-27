<?php
/**
 * betasouk
 * Created by SureCoder
 * FILE NAME: upload_pictures.php
 * YEAR: 2022
 */

global $sh, $sqlConnect;
require_once(__DIR__. '/../../app/init.php');
$ds = DIRECTORY_SEPARATOR;

// Path declarations
$productPath = "../../upload/photos";
$storeFolder = __DIR__ ."/../../upload/photos";

/**
 *
 *
 * PRODUCT IMAGE SETTINGS
 *
 *
 */
// Upload Users images
if(isset($_POST['action']) && $_POST['action'] == "upload_prop_imgs"){

    if (!empty($_FILES)) {

        $tempFile = $_FILES['file']['tmp_name'];

        $targetPath = dirname( __FILE__ ) . $ds. $productPath . $ds;

        $targetFile =  $targetPath. $_FILES['file']['name'];

        move_uploaded_file($tempFile,$targetFile);
    }

    // Insert into database
    $product_code = $product_id = $_POST['productcode'];
    $user_id = $_POST['user_id'];

    //count if products has been created before
    $countVal = Sh_countProductByProductCode($product_code);

    // Previous Image
    $first_img = array();

    $first_img[] = $_FILES['file']['name'];

    $serialize_images = serialize($first_img);

    if($countVal > 0){

        $query_one = "SELECT product_image FROM " . T_PRODUCTS . " WHERE `productcode` = '{$product_id}'";
        $sql       = mysqli_query($sqlConnect, $query_one);
        $sql_fetch_one = mysqli_fetch_assoc($sql);

        if(!empty($sql_fetch_one['product_image'])){
            $unserialize_data = unserialize($sql_fetch_one['product_image']);

            $pre_data = array($_FILES['file']['name']);

            $total_data = array_merge($unserialize_data, $pre_data);

            $multipel_serialize_images = serialize($total_data);

            $query = mysqli_query($sqlConnect, "UPDATE " . T_PRODUCTS . " SET `product_image` = '$multipel_serialize_images' WHERE `productcode` = '{$product_id}'");
        }
        else{
            $query = mysqli_query($sqlConnect, "UPDATE " . T_PRODUCTS . " SET `product_image` = '$serialize_images' WHERE `productcode` = '{$product_id}'");
        }
    }else{

        $query = mysqli_query($sqlConnect, "INSERT INTO " .  T_PRODUCTS . " (`user_id`,`productcode`, `product_image`) VALUES ({$user_id},'{$product_code}','{$serialize_images}') ");
    }
}

// Delete Images
if(isset($_POST['action']) && $_POST['action'] == "delete_prop_imgs"){

    $u_id = $_POST['user_id'];
    $p_cod = $_POST['pro_code'];
    $fileName = $_POST['fileName'];

    $queryfilter = mysqli_query($sqlConnect,"SELECT `product_image` FROM " . T_PRODUCTS . " WHERE `productcode` = '{$p_cod}'");
    $rowfilter = mysqli_fetch_array($queryfilter);

    $productimages = unserialize($rowfilter["product_image"]);
    foreach ($productimages as $key => $value) {
        if($value==$fileName)
            unset($productimages[$key]);
    }

    $productimages = serialize( $productimages);
    $query = mysqli_query($sqlConnect, "UPDATE " . T_PRODUCTS . " SET `product_image` = '$productimages' WHERE `productcode` = '{$p_cod}'");
    if($query){
        $targetPath = dirname( __FILE__ ) . $ds. $productPath . $ds;
        $filename = $targetPath.$fileName;
        unlink($filename);
        exit;
    }


}


/**
 *
 *
 * PAGE IMAGE SETTINGS
 *
 *
 */
// Upload Page images
if(isset($_POST['action']) && $_POST['action'] == "upload_page_imgs"){

    if (!empty($_FILES)) {

        $tempFile = $_FILES['file']['tmp_name'];

        $targetPath = dirname( __FILE__ ) . $ds. $pagePath . $ds;

        $targetFile =  $targetPath. $_FILES['file']['name'];

        move_uploaded_file($tempFile,$targetFile);
    }

    // Insert into database
    $product_code = $product_id = $_POST['pagecode'];
    $user_id = $_POST['user_id'];

    //count if products has been created before
    $countVal = Sh_countPageByPageCode($product_code);

    // Previous Image
    $first_img = array();

    $first_img[] = $_FILES['file']['name'];

    $serialize_images = serialize($first_img);

    if($countVal > 0){

        $query_one = "SELECT page_image FROM " . T_PAGES_T . " WHERE `page_code` = '{$product_id}'";
        $sql       = mysqli_query($sqlConnect, $query_one);
        $sql_fetch_one = mysqli_fetch_assoc($sql);

        if(!empty($sql_fetch_one['page_image'])){
            $unserialize_data = unserialize($sql_fetch_one['page_image']);

            $pre_data = array($_FILES['file']['name']);

            $total_data = array_merge($unserialize_data, $pre_data);

            $multipel_serialize_images = serialize($total_data);

            $query = mysqli_query($sqlConnect, "UPDATE " . T_PAGES_T . " SET `page_image` = '$multipel_serialize_images' WHERE `page_code` = '{$product_id}'");
        }
        else{
            $query = mysqli_query($sqlConnect, "UPDATE " . T_PAGES_T . " SET `page_image` = '$serialize_images' WHERE `page_code` = '{$product_id}'");
        }
    }else{

        $query = mysqli_query($sqlConnect, "INSERT INTO " .  T_PAGES_T . " (`user_id`,`page_code`, `page_image`) VALUES ({$user_id},'{$product_code}','{$serialize_images}') ");
    }
}

// Delete Page Images
if(isset($_POST['action']) && $_POST['action'] == "delete_page_imgs"){

    $u_id = $_POST['user_id'];
    $p_cod = $_POST['page_code'];
    $fileName = $_POST['fileName'];

    $queryfilter = mysqli_query($sqlConnect,"SELECT `page_image` FROM " . T_PAGES_T . " WHERE `page_code` = '{$p_cod}'");
    $rowfilter = mysqli_fetch_array($queryfilter);

    $productimages = unserialize($rowfilter["page_image"]);
    foreach ($productimages as $key => $value) {
        if($value==$fileName)
            unset($productimages[$key]);
    }

    $productimages = serialize( $productimages);
    $query = mysqli_query($sqlConnect, "UPDATE " . T_PAGES_T . " SET `page_image` = '$productimages' WHERE `page_code` = '{$p_cod}'");
    if($query){
        $targetPath = dirname( __FILE__ ) . $ds. $pagePath . $ds;
        $filename = $targetPath.$fileName;
        unlink($filename);
        exit;
    }


}



/**
 *
 *
 * BANNER IMAGE SETTINGS
 *
 *
 */

// Banner images
if(isset($_POST['action']) && $_POST['action'] == "upload_banner_imgs"){

    if (!empty($_FILES)) {

        $tempFile = $_FILES['file']['tmp_name'];

        $targetPath = dirname( __FILE__ ) . $ds. $bannerPath . $ds;

        $targetFile =  $targetPath. $_FILES['file']['name'];

        move_uploaded_file($tempFile,$targetFile);
    }

    // Insert into database
    $banner_code = $banner_id = $_POST['bannercode'];
    $user_id = $_POST['user_id'];

    // Previous Image
    $first_img = array();

    $first_img[] = $_FILES['file']['name'];

    $serialize_images = serialize($first_img);

    if (isset($sh['config']['banner_image'])) {

        $unserialize_data = unserialize($sh['config']['banner_image']);

        $pre_data = array($_FILES['file']['name']);

        $total_data = array_merge($unserialize_data, $pre_data);

        $multipel_serialize_images = serialize($total_data);

        $saveSetting = Sh_SaveConfig('banner_image', $multipel_serialize_images);

    }else {

        $query = insertRow(T_CONFIG, [
            'name' => 'banner_image',
            'value' => $serialize_images
        ]);

        $saveSetting = $sqlConnect->query($query);

    }


}

// Sort Banner images
if(isset($_POST['action']) && $_POST['action'] == "sort_banner_imgs"){

    $u_id = $_POST['user_id'];
    $b_cod = $_POST['ban_code'];

    $multipel_serialize_images = serialize($_POST['filenames']);

    $query = Sh_SaveConfig('banner_image', $multipel_serialize_images);

    if($query){
        $data = array(
            'status' => 200,
            'msg' => 'yes done'
        );
    }else{
        $data = array(
            'status' => 400,
            'msg' => 'Error'
        );
    }

    header("Content-type: application/json");
    echo json_encode($data);
    die;

}

// Delete Banner Images
if( isset($_POST['action']) && $_POST['action'] == "delete_banner_imgs"){

    $u_id = $_POST['user_id'];
    $b_cod = $_POST['ban_code'];
    $fileName = $_POST['fileName'];

    $bannerimages = unserialize($sh['config']['banner_image']);

    foreach ($bannerimages as $key => $value) {
        if($value==$fileName)
            unset($bannerimages[$key]);
    }

    $bannerimages = serialize($bannerimages);

    $query = Sh_SaveConfig('banner_image', $bannerimages);

    if($query){
        $targetPath = dirname( __FILE__ ) . $ds. $bannerPath . $ds;
        $filename = $targetPath.$fileName;
        unlink($filename);
        exit;
    }


}


/**
 *
 *
 * HERO SECTION BANNER
 *
 *
 */
if(isset($_POST['action']) && $_POST['action'] == "upload_hero_sec_banner_imgs"){

    if (!empty($_FILES)) {

        $tempFile = $_FILES['file']['tmp_name'];

        $targetPath = dirname( __FILE__ ) . $ds. $bannerPath . $ds;

        $targetFile =  $targetPath. $_FILES['file']['name'];

        move_uploaded_file($tempFile,$targetFile);
    }

    // Insert into database
    $banner_code = $banner_id = $_POST['bannercode'];
    $user_id = $_POST['user_id'];

    // Previous Image
    $first_img = array();

    $first_img[] = $_FILES['file']['name'];

    $serialize_images = serialize($first_img);

    if (isset($sh['config']['hero_section_banner']) && !empty($sh['config']['hero_section_banner'])) {

        $unserialize_data = unserialize($sh['config']['hero_section_banner']);

        $pre_data = array($_FILES['file']['name']);

        $total_data = array_merge($unserialize_data, $pre_data);

        $multipel_serialize_images = serialize($total_data);

        $saveSetting = Sh_SaveConfig('hero_section_banner', $multipel_serialize_images);

    }else {

        $query = insertRow(T_CONFIG, [
            'name' => 'hero_section_banner',
            'value' => $serialize_images
        ]);

        $saveSetting = $sqlConnect->query($query);

    }


}

// Sort Banner images
if(isset($_POST['action']) && $_POST['action'] == "sort_hero_sec_banner_imgs"){

    $u_id = $_POST['user_id'];
    $b_cod = $_POST['ban_code'];

    $multipel_serialize_images = serialize($_POST['filenames']);

    $query = Sh_SaveConfig('hero_section_banner', $multipel_serialize_images);

    if($query){
        $data = array(
            'status' => 200,
            'msg' => 'yes done'
        );
    }else{
        $data = array(
            'status' => 400,
            'msg' => 'Error'
        );
    }

    header("Content-type: application/json");
    echo json_encode($data);
    die;

}

// Delete Banner Images
if( isset($_POST['action']) && $_POST['action'] == "delete_hero_sec_banner_imgs"){

    $u_id = $_POST['user_id'];
    $b_cod = $_POST['ban_code'];
    $fileName = $_POST['fileName'];

    $bannerimages = unserialize($sh['config']['hero_section_banner']);

    foreach ($bannerimages as $key => $value) {
        if($value==$fileName)
            unset($bannerimages[$key]);
    }

    $bannerimages = serialize($bannerimages);

    $query = Sh_SaveConfig('hero_section_banner', $bannerimages);

    if($query){
        $targetPath = dirname( __FILE__ ) . $ds. $bannerPath . $ds;
        $filename = $targetPath.$fileName;
        unlink($filename);
        exit;
    }


}



/**
 *
 *
 * DEALS OF THE DAY SECTION BANNER
 *
 *
 */
if(isset($_POST['action']) && $_POST['action'] == "upload_deals_of_the_day_sec_banner_imgs"){

    if (!empty($_FILES)) {

        $tempFile = $_FILES['file']['tmp_name'];

        $targetPath = dirname( __FILE__ ) . $ds. $bannerPath . $ds;

        $targetFile =  $targetPath. $_FILES['file']['name'];

        move_uploaded_file($tempFile,$targetFile);
    }

    // Insert into database
    $banner_code = $banner_id = $_POST['bannercode'];
    $user_id = $_POST['user_id'];

    // Previous Image
    $first_img = array();

    $first_img[] = $_FILES['file']['name'];

    $serialize_images = serialize($first_img);

    if (isset($sh['config']['deals_of_the_day_section_banner']) && !empty($sh['config']['deals_of_the_day_section_banner'])) {

        $unserialize_data = unserialize($sh['config']['deals_of_the_day_section_banner']);

        $pre_data = array($_FILES['file']['name']);

        $total_data = array_merge($unserialize_data, $pre_data);

        $multipel_serialize_images = serialize($total_data);

        $saveSetting = Sh_SaveConfig('deals_of_the_day_section_banner', $multipel_serialize_images);

    }else {

        $query = insertRow(T_CONFIG, [
            'name' => 'deals_of_the_day_section_banner',
            'value' => $serialize_images
        ]);

        $saveSetting = $sqlConnect->query($query);

    }


}

// Sort Banner images
if(isset($_POST['action']) && $_POST['action'] == "sort_deals_of_the_day_sec_banner_imgs"){

    $u_id = $_POST['user_id'];
    $b_cod = $_POST['ban_code'];

    $multipel_serialize_images = serialize($_POST['filenames']);

    $query = Sh_SaveConfig('deals_of_the_day_section_banner', $multipel_serialize_images);

    if($query){
        $data = array(
            'status' => 200,
            'msg' => 'yes done'
        );
    }else{
        $data = array(
            'status' => 400,
            'msg' => 'Error'
        );
    }

    header("Content-type: application/json");
    echo json_encode($data);
    die;

}

// Delete Banner Images
if( isset($_POST['action']) && $_POST['action'] == "delete_deals_of_the_day_sec_banner_imgs"){

    $u_id = $_POST['user_id'];
    $b_cod = $_POST['ban_code'];
    $fileName = $_POST['fileName'];

    $bannerimages = unserialize($sh['config']['deals_of_the_day_section_banner']);

    foreach ($bannerimages as $key => $value) {
        if($value==$fileName)
            unset($bannerimages[$key]);
    }

    $bannerimages = serialize($bannerimages);

    $query = Sh_SaveConfig('deals_of_the_day_section_banner', $bannerimages);

    if($query){
        $targetPath = dirname( __FILE__ ) . $ds. $bannerPath . $ds;
        $filename = $targetPath.$fileName;
        unlink($filename);
        exit;
    }


}



/**
 *
 *
 * BELOW CATEGORY SECTION BANNER
 *
 *
 */
if(isset($_POST['action']) && $_POST['action'] == "upload_below_cat_sec_banner_imgs"){

    if (!empty($_FILES)) {

        $tempFile = $_FILES['file']['tmp_name'];

        $targetPath = dirname( __FILE__ ) . $ds. $bannerPath . $ds;

        $targetFile =  $targetPath. $_FILES['file']['name'];

        move_uploaded_file($tempFile,$targetFile);
    }

    // Insert into database
    $banner_code = $banner_id = $_POST['bannercode'];
    $user_id = $_POST['user_id'];

    // Previous Image
    $first_img = array();

    $first_img[] = $_FILES['file']['name'];

    $serialize_images = serialize($first_img);

    if (isset($sh['config']['below_cat_section_banner']) && !empty($sh['config']['below_cat_section_banner'])) {

        $unserialize_data = unserialize($sh['config']['below_cat_section_banner']);

        $pre_data = array($_FILES['file']['name']);

        $total_data = array_merge($unserialize_data, $pre_data);

        $multipel_serialize_images = serialize($total_data);

        $saveSetting = Sh_SaveConfig('below_cat_section_banner', $multipel_serialize_images);

    }else {

        $query = insertRow(T_CONFIG, [
            'name' => 'below_cat_section_banner',
            'value' => $serialize_images
        ]);

        $saveSetting = $sqlConnect->query($query);

    }


}

// Sort Banner images
if(isset($_POST['action']) && $_POST['action'] == "sort_below_cat_sec_banner_imgs"){

    $u_id = $_POST['user_id'];
    $b_cod = $_POST['ban_code'];

    $multipel_serialize_images = serialize($_POST['filenames']);

    $query = Sh_SaveConfig('below_cat_section_banner', $multipel_serialize_images);

    if($query){
        $data = array(
            'status' => 200,
            'msg' => 'yes done'
        );
    }else{
        $data = array(
            'status' => 400,
            'msg' => 'Error'
        );
    }

    header("Content-type: application/json");
    echo json_encode($data);
    die;

}

// Delete Banner Images
if( isset($_POST['action']) && $_POST['action'] == "delete_below_cat_sec_banner_imgs"){

    $u_id = $_POST['user_id'];
    $b_cod = $_POST['ban_code'];
    $fileName = $_POST['fileName'];

    $bannerimages = unserialize($sh['config']['below_cat_section_banner']);

    foreach ($bannerimages as $key => $value) {
        if($value==$fileName)
            unset($bannerimages[$key]);
    }

    $bannerimages = serialize($bannerimages);

    $query = Sh_SaveConfig('below_cat_section_banner', $bannerimages);

    if($query){
        $targetPath = dirname( __FILE__ ) . $ds. $bannerPath . $ds;
        $filename = $targetPath.$fileName;
        unlink($filename);
        exit;
    }


}



/**
 *
 *
 * NEWSLETTER CATEGORY SECTION BANNER
 *
 *
 */
if(isset($_POST['action']) && $_POST['action'] == "upload_newsletter_sec_banner_imgs"){

    if (!empty($_FILES)) {

        $tempFile = $_FILES['file']['tmp_name'];

        $targetPath = dirname( __FILE__ ) . $ds. $bannerPath . $ds;

        $targetFile =  $targetPath. $_FILES['file']['name'];

        move_uploaded_file($tempFile,$targetFile);
    }

    // Insert into database
    $banner_code = $banner_id = $_POST['bannercode'];
    $user_id = $_POST['user_id'];

    // Previous Image
    $first_img = array();

    $first_img[] = $_FILES['file']['name'];

    $serialize_images = serialize($first_img);

    if (isset($sh['config']['newsletter_cat_section_banner']) && !empty($sh['config']['newsletter_cat_section_banner'])) {

        $unserialize_data = unserialize($sh['config']['newsletter_cat_section_banner']);

        $pre_data = array($_FILES['file']['name']);

        $total_data = array_merge($unserialize_data, $pre_data);

        $multipel_serialize_images = serialize($total_data);

        $saveSetting = Sh_SaveConfig('newsletter_cat_section_banner', $multipel_serialize_images);

    }else {

        $query = insertRow(T_CONFIG, [
            'name' => 'newsletter_cat_section_banner',
            'value' => $serialize_images
        ]);

        $saveSetting = $sqlConnect->query($query);

    }


}

// Sort Banner images
if(isset($_POST['action']) && $_POST['action'] == "sort_newsletter_sec_banner_imgs"){

    $u_id = $_POST['user_id'];
    $b_cod = $_POST['ban_code'];

    $multipel_serialize_images = serialize($_POST['filenames']);

    $query = Sh_SaveConfig('newsletter_cat_section_banner', $multipel_serialize_images);

    if($query){
        $data = array(
            'status' => 200,
            'msg' => 'yes done'
        );
    }else{
        $data = array(
            'status' => 400,
            'msg' => 'Error'
        );
    }

    header("Content-type: application/json");
    echo json_encode($data);
    die;

}

// Delete Banner Images
if( isset($_POST['action']) && $_POST['action'] == "delete_newsletter_sec_banner_imgs"){

    $u_id = $_POST['user_id'];
    $b_cod = $_POST['ban_code'];
    $fileName = $_POST['fileName'];

    $bannerimages = unserialize($sh['config']['newsletter_cat_section_banner']);

    foreach ($bannerimages as $key => $value) {
        if($value==$fileName)
            unset($bannerimages[$key]);
    }

    $bannerimages = serialize($bannerimages);

    $query = Sh_SaveConfig('newsletter_cat_section_banner', $bannerimages);

    if($query){
        $targetPath = dirname( __FILE__ ) . $ds. $bannerPath . $ds;
        $filename = $targetPath.$fileName;
        unlink($filename);
        exit;
    }


}