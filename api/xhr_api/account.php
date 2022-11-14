<?php


if ($f == "account"){

    if ($s == "update-user"){

        $first_name = Sh_Secure($_POST['first_name']);
        $last_name = Sh_Secure($_POST['last_name']);
        $email = Sh_Secure($_POST['email']);
        $phone = Sh_Secure($_POST['phone_number']);
        $status = Sh_Secure($_POST['status']);
        $adminAccess = Sh_Secure($_POST['admin']);

        $user_id = Sh_Secure($_POST['user_id']);

        $userData = array(
           'first_name' => $first_name,
           'last_name' => $last_name,
           'email' => $email,
           'phone_number' => $phone,
           'active' => $status,
           'status' => $status,
           'admin' => $adminAccess
        );

        $updUser = UpdateUserData($userData, $user_id);

        if ($updUser){

            $data = array(
                'status' => 200,
                'message' => "Account ".$sh['lang']['general_update_success_message'],
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


    if ($s == "delete_account"){

        $user_id = $_GET['user_id'];

        if(DeleteUserData($user_id)){
            $data = array(
                'status' => 200,
                'message' => "Account ".$sh['lang']['general_delete_success_message'],
            );
        }else{
             $data = array(
                 'status' => 400,
                 'message' => $sh['lang']['general_error_message'],
             );
        }

        header("Content-type: application/json");
        echo json_encode($data);
        exit();

    }


    if ($s == "getuserdata"){

        if (isset($_GET['user_id'])){

            $user_id = $_GET['user_id'];

            $userData = Sh_UserData($user_id);

            if ($userData){
        ?>
                <div class="mb-3">
                    <label for="first_name" class="col-form-label">First Name</label>
                    <input type="hidden" name="user_id" id="user_id" value="<?= $userData['user_id'] ?>" >
                    <input type="text" value="<?= $userData['first_name'] ?>" name="first_name" class="form-control" id="first_name">
                </div>
                <div class="mb-3">
                    <label for="last_name" class="col-form-label">Last Name</label>
                    <input type="text" name="last_name" value="<?= $userData['last_name'] ?>" class="form-control" id="last_name">
                </div>
                <div class="mb-3">
                    <label for="email" class="col-form-label">Email</label>
                    <input type="text" name="email" value="<?= $userData['email'] ?>" class="form-control" id="email">
                </div>
                <div class="mb-3">
                    <label for="phone_number" class="col-form-label">Phone Number</label>
                    <input type="text" name="phone_number" value="<?= $userData['phone_number'] ?>" class="form-control" id="phone_number">
                </div>
                <div class="mb-3">
                    <label for="slug" class="col-form-label">Account Status</label>
                    <select name="status" class="form-control">
                        <option value="1" <?= ($userData['status'] == 1) ? 'selected' : '' ?> >Active</option>
                        <option value="0" <?= ($userData['status'] == 0) ? 'selected' : '' ?>>In-Active</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Admin Cpanel Access</label>
                    <select name="admin" class="form-select">
                        <option <?= ($userData['admin'] == 0) ? 'selected' : '' ?> value="0">No</option>
                        <option <?= ($userData['admin'] == 1) ? 'selected' : '' ?> value="1">Yes</option>
                    </select>
                </div>

        <?php }else{ ?>
            <div class="alert alert-danger text-center my-3">
                <i class="fa fa-exclamation-circle" ></i> Sorry Account Data Could not be fetched....
            </div>
        <?php  }


        }else{ ?>
            <div class="alert alert-danger text-center my-3">
                <i class="fa fa-exclamation-circle" ></i> Sorry Invalid User Id...
            </div>
    <?php    }

    }


    if ($s == "account_search"){

        $accountName = "";

        if(!empty($_POST['keywords'])){
            $accountName = $_POST['keywords'];
        }

        $nquery = '';

        $whereBy = "";

        if (isset($_POST['filterByVote'])) {
            $filterBy = $_POST['filterByVote'];
            if ($filterBy == "voteed"){
                $whereBy = " AND status = 1";
            }else if($filterBy == "not_voted"){
                $whereBy = "AND status = 0";
            }else if($filterBy == "all"){
                $whereBy = "AND (status = 0 OR status = 1 OR status = 2)";
            }
        }

        $orderBy = "user_id DESC";

        $baseURL = $sh['config']['site_url'].'f=account&s=account_search';

        $perPage = 10;
        $page = 1;

        $start = !empty($_POST['page'])?$_POST['page']:0;

        // query conditions
        $conditionA = " WHERE (`username` LIKE '%$accountName%' OR `first_name` LIKE '%$accountName%' OR `last_name` LIKE '%$accountName%' OR `middle_name` LIKE '%$accountName%') ". $whereBy ." ORDER BY ".$orderBy;

        $conditionB = $conditionA . " limit " . $start . "," . $perPage;

        $allUserAccountWithoutLimit = getallUserAccountData($conditionA);

        $rowCount = count($allUserAccountWithoutLimit);

        // Initialize pagination class
        $pagConfig = array(
            'baseURL' => $baseURL,
            'totalRows' => $rowCount,
            'perPage' => $perPage,
            'currentPage' => $start,
            'contentDiv' => 'all-datas',
            'link_func' => 'searchProductsFilter'
        );


        $pagination =  new AdminPagination($pagConfig);

        $users = getallUserAccountData($conditionB);

        if(count($users) > 0 || !empty($users)) { ?>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th scope="col">User</th>
                    <th scope="col">Matric No</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Level</th>
                    <th scope="col">Vote Status</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody id="all-datas">
                <?php 
                    foreach($users as $aus){
                ?>
                    <tr  id="user_<?= $aus['user_id'] ?>">
                        <td class="py-1">
                            <img src="<?= $aus['avatar'] ?>" alt="image"/>
                        </td>
                        <td><?= $aus['username'] ?></td>
                        <td><?= $aus['name'] ?></td>
                        <td><?= $aus['level'] ?></td>
                        <td><?= ($aus['vote_status'] == 1) ? '<p class="badge badge-success">Voted</p>' : '<p class="badge badge-success">Not Yet</p>' ?></td>
                        <td>
                            <a href="">
                            <i class="mdi mdi-settings text-primary"></i>
                            </a>
                            &nbsp; &nbsp;
                            <a href="<?= Sh_Link('edit-user/'. $aus['user_id']) ?>=">
                            <i class="mdi mdi-grease-pencil text-primary"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                
                </tbody>
            </table>

            <?php echo $pagination->createLinks(); ?>
            
        <?php }else{ ?>

            <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="error-display">
                    <div class="alert alert-danger text-center">
                        <i class="fa fa-exclamation-circle" ></i> <?= $sh['lang']['no_record_found'] ?>
                    </div>
                </div>
            </div>


        <?php }

    }

}
