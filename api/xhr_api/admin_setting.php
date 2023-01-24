<?php
use Aws\S3\S3Client;
use Google\Cloud\Storage\StorageClient;
if ($f == 'admin_setting' AND (Sh_IsAdmin()) ) {


    if ($s == "update_site_logo") {
        $saveSetting = false;
      if (isset($_FILES['site_logo']) && !empty($_FILES['site_logo'])) {

            
           if (!empty($_FILES['site_logo']["tmp_name"])) {

               $orignalname_cb = $_FILES['site_logo']["name"];
               $fileInfo_cb = array(
                 'file' => $_FILES["site_logo"]["tmp_name"],
                 'name' => $_FILES['site_logo']['name'],
                 'size' => $_FILES["site_logo"]["size"],
                 'type' => $_FILES["site_logo"]["type"],
                 'types' => 'jpeg,jpg,png,gif',
               );

               $media_cb = Sh_ShareFile($fileInfo_cb, 0, false);

               if (!empty($media_cb)) {

                 $filename_cb = $media_cb['filename'];

                 if (isset($sh['config']['site_logo'])) {

                   $saveSetting = Sh_SaveConfig('site_logo', $filename_cb);

                 }else {

                   $query = insertRow(T_CONFIG, [
                       'name' => 'site_logo',
                       'value' => $filename_cb
                   ]);

                   $saveSetting = $sqlConnect->query($query);

                 }


               }

           }

       }

       if ($saveSetting === true) {
           $data['status'] = 200;
       }


       header("Content-type: application/json");
       echo json_encode($data);
       exit();



    }
//
//    if ($s == "update_contact_banner") {
//      if (isset($_FILES['contact_banner']) && !empty($_FILES['contact_banner'])) {
//
//           if (!empty($_FILES['contact_banner']["tmp_name"])) {
//
//               $orignalname_cb = $_FILES['contact_banner']["name"];
//               $fileInfo_cb = array(
//                 'file' => $_FILES["contact_banner"]["tmp_name"],
//                 'name' => $_FILES['contact_banner']['name'],
//                 'size' => $_FILES["contact_banner"]["size"],
//                 'type' => $_FILES["contact_banner"]["type"],
//                 'types' => 'jpeg,jpg,png,gif',
//               );
//
//               $media_cb = Sh_ShareFile($fileInfo_cb, 0, false);
//
//               if (!empty($media_cb)) {
//
//                 $filename_cb = $media_cb['filename'];
//
//                 if (isset($sh['config']['contact_banner'])) {
//
//                   $saveSetting = Sh_SaveConfig('contact_banner', $filename_cb);
//
//                 }else {
//
//                   $query = insertRow(T_CONFIG, [
//                       'name' => 'contact_banner',
//                       'value' => $filename_cb
//                   ]);
//
//                   $saveSetting = $sqlConnect->query($query);
//
//                 }
//
//
//               }
//
//           }
//
//       }
//
//       if ($saveSetting === true) {
//           $data['status'] = 200;
//       }
//
//
//       header("Content-type: application/json");
//       echo json_encode($data);
//       exit();
//
//
//
//    }
//
//    if($s == "update_page_banner_image"){
//      if (isset($_FILES['page_banner']) && !empty($_FILES['page_banner'])) {
//
//           if (!empty($_FILES['page_banner']["tmp_name"])) {
//
//               $orignalname_cb = $_FILES['page_banner']["name"];
//               $fileInfo_cb = array(
//                 'file' => $_FILES["page_banner"]["tmp_name"],
//                 'name' => $_FILES['page_banner']['name'],
//                 'size' => $_FILES["page_banner"]["size"],
//                 'type' => $_FILES["page_banner"]["type"],
//                 'types' => 'jpeg,jpg,png,gif',
//               );
//
//               $media_cb = Sh_ShareFile($fileInfo_cb, 0, false);
//
//               if (!empty($media_cb)) {
//
//                 $filename_cb = $media_cb['filename'];
//
//                 if (isset($sh['config']['page_banner'])) {
//
//                   $saveSetting = Sh_SaveConfig('page_banner', $filename_cb);
//
//                 }else {
//
//                   $query = insertRow(T_CONFIG, [
//                       'name' => 'page_banner',
//                       'value' => $filename_cb
//                   ]);
//
//                   $saveSetting = $sqlConnect->query($query);
//
//                 }
//
//
//               }
//
//           }
//
//       }
//
//       if ($saveSetting === true) {
//           $data['status'] = 200;
//       }
//
//
//       header("Content-type: application/json");
//       echo json_encode($data);
//       exit();
//
//    }
//
//    if ($s == "update_banner_image") {
//      if (isset($_FILES['banner_image']) && !empty($_FILES['banner_image'])) {
//
//           if (!empty($_FILES['banner_image']["tmp_name"])) {
//
//               $orignalname_cb = $_FILES['banner_image']["name"];
//               $fileInfo_cb = array(
//                 'file' => $_FILES["banner_image"]["tmp_name"],
//                 'name' => $_FILES['banner_image']['name'],
//                 'size' => $_FILES["banner_image"]["size"],
//                 'type' => $_FILES["banner_image"]["type"],
//                 'types' => 'jpeg,jpg,png,gif',
//               );
//
//               $media_cb = Sh_ShareFile($fileInfo_cb, 0, false);
//
//               if (!empty($media_cb)) {
//
//                 $filename_cb = $media_cb['filename'];
//
//                 if (isset($sh['config']['banner_image'])) {
//
//                   $saveSetting = Sh_SaveConfig('banner_image', $filename_cb);
//
//                 }else {
//
//                   $query = insertRow(T_CONFIG, [
//                       'name' => 'banner_image',
//                       'value' => $filename_cb
//                   ]);
//
//                   $saveSetting = $sqlConnect->query($query);
//
//                 }
//
//
//               }
//
//           }
//
//       }
//
//       if ($saveSetting === true) {
//           $data['status'] = 200;
//       }
//
//
//       header("Content-type: application/json");
//       echo json_encode($data);
//       exit();
//
//
//
//    }
//
//    if ($s == "update_event_image") {
//      if (isset($_FILES['event_image']) && !empty($_FILES['event_image'])) {
//
//           if (!empty($_FILES['event_image']["tmp_name"])) {
//
//               $orignalname_cb = $_FILES['event_image']["name"];
//               $fileInfo_cb = array(
//                 'file' => $_FILES["event_image"]["tmp_name"],
//                 'name' => $_FILES['event_image']['name'],
//                 'size' => $_FILES["event_image"]["size"],
//                 'type' => $_FILES["event_image"]["type"],
//                 'types' => 'jpeg,jpg,png,gif',
//               );
//
//               $media_cb = Sh_ShareFile($fileInfo_cb, 0, false);
//
//               if (!empty($media_cb)) {
//
//                 $filename_cb = $media_cb['filename'];
//
//                 if (isset($sh['config']['event_image'])) {
//
//                   $saveSetting = Sh_SaveConfig('event_image', $filename_cb);
//
//                 }else {
//
//                   $query = insertRow(T_CONFIG, [
//                       'name' => 'event_image',
//                       'value' => $filename_cb
//                   ]);
//
//                   $saveSetting = $sqlConnect->query($query);
//
//                 }
//
//
//               }
//
//           }
//
//       }
//
//       if ($saveSetting === true) {
//           $data['status'] = 200;
//       }
//
//
//       header("Content-type: application/json");
//       echo json_encode($data);
//       exit();
//
//
//
//    }
//
//    if ($s == "update_partner1_image") {
//      if (isset($_FILES['partner1_image']) && !empty($_FILES['partner1_image'])) {
//
//           if (!empty($_FILES['partner1_image']["tmp_name"])) {
//
//               $orignalname_cb = $_FILES['partner1_image']["name"];
//               $fileInfo_cb = array(
//                 'file' => $_FILES["partner1_image"]["tmp_name"],
//                 'name' => $_FILES['partner1_image']['name'],
//                 'size' => $_FILES["partner1_image"]["size"],
//                 'type' => $_FILES["partner1_image"]["type"],
//                 'types' => 'jpeg,jpg,png,gif',
//               );
//
//               $media_cb = Sh_ShareFile($fileInfo_cb, 0, false);
//
//               if (!empty($media_cb)) {
//
//                 $filename_cb = $media_cb['filename'];
//
//                 if (isset($sh['config']['partner1_image'])) {
//
//                   $saveSetting = Sh_SaveConfig('partner1_image', $filename_cb);
//
//                 }else {
//
//                   $query = insertRow(T_CONFIG, [
//                       'name' => 'partner1_image',
//                       'value' => $filename_cb
//                   ]);
//
//                   $saveSetting = $sqlConnect->query($query);
//
//                 }
//
//
//               }
//
//           }
//
//       }
//
//       if ($saveSetting === true) {
//           $data['status'] = 200;
//       }
//
//
//       header("Content-type: application/json");
//       echo json_encode($data);
//       exit();
//
//
//
//    }
//
//    if ($s == "update_partner2_image") {
//      if (isset($_FILES['partner2_image']) && !empty($_FILES['partner2_image'])) {
//
//           if (!empty($_FILES['partner2_image']["tmp_name"])) {
//
//               $orignalname_cb = $_FILES['partner2_image']["name"];
//               $fileInfo_cb = array(
//                 'file' => $_FILES["partner2_image"]["tmp_name"],
//                 'name' => $_FILES['partner2_image']['name'],
//                 'size' => $_FILES["partner2_image"]["size"],
//                 'type' => $_FILES["partner2_image"]["type"],
//                 'types' => 'jpeg,jpg,png,gif',
//               );
//
//               $media_cb = Sh_ShareFile($fileInfo_cb, 0, false);
//
//               if (!empty($media_cb)) {
//
//                 $filename_cb = $media_cb['filename'];
//
//                 if (isset($sh['config']['partner2_image'])) {
//
//                   $saveSetting = Sh_SaveConfig('partner2_image', $filename_cb);
//
//                 }else {
//
//                   $query = insertRow(T_CONFIG, [
//                       'name' => 'partner2_image',
//                       'value' => $filename_cb
//                   ]);
//
//                   $saveSetting = $sqlConnect->query($query);
//
//                 }
//
//
//               }
//
//           }
//
//       }
//
//       if ($saveSetting === true) {
//           $data['status'] = 200;
//       }
//
//
//       header("Content-type: application/json");
//       echo json_encode($data);
//       exit();
//
//
//
//    }
//
    if ($s == "remove_logo_image"){

      $img = $_GET['img'];
      $admin_id = $sh['admin']['admin_id'];

      $upd = Sh_SaveConfig('site_logo', "");

      if ($upd) {

        $data = array(
          'status' => 200,
          'message' => "Logo Picture ".$sh['lang']['general_update_success_message'],
        );

      }else {

        $data = array(
          'status' => 400,
          'message' => $sh['lang']['general_update_error_message'],
        );

      }

      unlink($img);

      header("Content-type: application/json");
      echo json_encode($data);
      exit();

    }

    if ($s == "reset_all_votes"){

        if (isset($_GET['user_id'])){
            $Puser_id = Sh_Secure($_GET['user_id']);
        }else{
            $Puser_id = 0;
        }

        //reset all votes of all users
        if (resetVotes()){
            $data = array(
                'status' => 200,
                'message' => "Votes Successfully reset",
            );
        }else{
            $data = array(
                'status' => 400,
                'message' => $sh['lang']['general_update_error_message'],
            );
        }



        header("Content-type: application/json");
        echo json_encode($data);
        exit();

    }



    if ($s == "delete_position"){

        $error = 0;
        if (!isset($_GET['post_id'])){
            $error = 1;
            $message = "Please Provide Position Id";
        }

        if (!getPositionData(Sh_Secure($_GET['post_id']))){
            $error = 1;
            $message = "Invalid Position Id";
        }

        //Delete position
        if (!$error){

            DeletePositionData($_GET['post_id']);

            $data = array(
                'status' => 200,
                'message' => "Position Successfully deleted",
            );

        }else{
            $data = array(
                'status' => 400,
                'message' => $message,
            );
        }


        header("Content-type: application/json");
        echo json_encode($data);
        exit();

    }


    if ($s == "create_post"){

        $title = Sh_Secure($_POST['title']);

        if (getPositionExistence($title)) {

            $data = array(
                'status' => 400,
                'message' => $title." ".$sh['lang']['found_already'],
            );

        }else {

            $dataP = array(
                'title' => $title,
                'location' => "Faculty of Science"
            );

            // create position
            if (createPosition($dataP)){
                $data = array(
                    'status' => 200,
                    'message' => "Successfully Created New Position"
                );
            }else{
                $data = array(
                    'status' => 400,
                    'message' => "Error While Processing your Request"
                );
            }

        }

        header("Content-type: application/json");
        echo json_encode($data);
        exit();

    }


    if ($s == "update_post"){

        $error = 0;
        if (!isset($_POST['post_id'])){
            $error = 1;
            $message = "Please Provide Position Id";
        }

        if (!isset($_POST['title'])){
            $error = 1;
            $message = "Please Provide Position Title";
        }

        if (!isset($_POST['status'])){
            $error = 1;
            $message = "Please Provide Position Status";
        }

        if (!getPositionData(Sh_Secure($_POST['post_id']))){
            $error = 1;
            $message = "Invalid Position Id";
        }

        if (!$error){

            $post_id = Sh_Secure($_POST['post_id']);

            $postData = [
                'title' => Sh_Secure($_POST['title']),
                'status' => Sh_Secure($_POST['status']),
            ];

            UpdatePositionData($postData,$post_id);

            $data = array(
                'status' => 200,
                'message' => "Position Successfully Updated",
            );

        }else{
            $data = array(
                'status' => 400,
                'message' => $message,
            );
        }

        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
//
//    if ($s == "remove_banner_image"){
//
//      $img = $_GET['img'];
//      $user_id = $sh['user']['user_id'];
//
//      $upd = Sh_SaveConfig('banner_image', "");
//
//      if ($upd) {
//
//        $data = array(
//          'status' => 200,
//          'message' => "Banner Image ".$sh['lang']['general_update_success_message'],
//        );
//
//      }else {
//
//        $data = array(
//          'status' => 400,
//          'message' => $sh['lang']['general_update_error_message'],
//        );
//
//      }
//
//      unlink($img);
//
//      header("Content-type: application/json");
//      echo json_encode($data);
//      exit();
//
//    }
//
//    if ($s == "remove_event_image"){
//
//      $img = $_GET['img'];
//      $user_id = $sh['user']['user_id'];
//
//      $upd = Sh_SaveConfig('event_image', "");
//
//      if ($upd) {
//
//        $data = array(
//          'status' => 200,
//          'message' => "Banner Image ".$sh['lang']['general_update_success_message'],
//        );
//
//      }else {
//
//        $data = array(
//          'status' => 400,
//          'message' => $sh['lang']['general_update_error_message'],
//        );
//
//      }
//
//      unlink($img);
//
//      header("Content-type: application/json");
//      echo json_encode($data);
//      exit();
//
//    }
//
//    if ($s == "remove_page_image"){
//
//      $img = $_GET['img'];
//      $user_id = $sh['user']['user_id'];
//
//      $upd = Sh_SaveConfig('page_banner', "");
//
//      if ($upd) {
//
//        $data = array(
//          'status' => 200,
//          'message' => "Page Banner Image ".$sh['lang']['general_update_success_message'],
//        );
//
//      }else {
//
//        $data = array(
//          'status' => 400,
//          'message' => $sh['lang']['general_update_error_message'],
//        );
//
//      }
//
//      unlink($img);
//
//      header("Content-type: application/json");
//      echo json_encode($data);
//      exit();
//
//    }
//
//    if ($s == "remove_partner1_image"){
//
//      $img = $_GET['img'];
//      $user_id = $sh['user']['user_id'];
//
//      $upd = Sh_SaveConfig('partner1_image', "");
//
//      if ($upd) {
//
//        $data = array(
//          'status' => 200,
//          'message' => "Partner 1 Image ".$sh['lang']['general_update_success_message'],
//        );
//
//      }else {
//
//        $data = array(
//          'status' => 400,
//          'message' => $sh['lang']['general_update_error_message'],
//        );
//
//      }
//
//      unlink($img);
//
//      header("Content-type: application/json");
//      echo json_encode($data);
//      exit();
//
//    }
//
//    if ($s == "remove_partner2_image"){
//
//      $img = $_GET['img'];
//      $user_id = $sh['user']['user_id'];
//
//      $upd = Sh_SaveConfig('partner2_image', "");
//
//      if ($upd) {
//
//        $data = array(
//          'status' => 200,
//          'message' => "Partner 2 Image ".$sh['lang']['general_update_success_message'],
//        );
//
//      }else {
//
//        $data = array(
//          'status' => 400,
//          'message' => $sh['lang']['general_update_error_message'],
//        );
//
//      }
//
//      unlink($img);
//
//      header("Content-type: application/json");
//      echo json_encode($data);
//      exit();
//
//    }
//
//    if ($s == "remove_contact_banner_image"){
//
//      $img = $_GET['img'];
//      $user_id = $sh['user']['user_id'];
//
//      $upd = Sh_SaveConfig('contact_banner', "");
//
//      if ($upd) {
//
//        $data = array(
//          'status' => 200,
//          'message' => "Contact Banner Image ".$sh['lang']['general_update_success_message'],
//        );
//
//      }else {
//
//        $data = array(
//          'status' => 400,
//          'message' => $sh['lang']['general_update_error_message'],
//        );
//
//      }
//
//      unlink($img);
//
//      header("Content-type: application/json");
//      echo json_encode($data);
//      exit();
//
//    }

    if ($s == 'update_general_setting' && Sh_CheckSession($hash_id) === true) {

        $saveSetting = false;

        foreach ($_POST as $key => $value) {

            if (isset($sh['config'][$key])) {

                $saveSetting = Sh_SaveConfig($key, $value);

            }else{

                $query = insertRow(T_CONFIG, [
                    'name' => $key,
                    'value' => $value
                ]);

                $saveSetting = $sqlConnect->query($query);

            }
        }


        if ($saveSetting === true) {
            $data['status'] = 200;
        }


        header("Content-type: application/json");
        echo json_encode($data);
        exit();


    }



}
