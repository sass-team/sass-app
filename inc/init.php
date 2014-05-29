<?php
/**
 *
 */
ob_start();

#starting the users session
session_start();

require "config.php";
// identical to require; but php will include it only if it has not already been included
require_once ROOT_PATH . 'inc/db.php';
require_once ROOT_PATH . "inc/model/User.php";
require_once ROOT_PATH . "inc/model/General.php";

$errors = array();

try {
	$db = new DB();
} catch (Exception $e) {
	$errors[] = $e->getMessage();
}
$general = new General();


// retrieves data if a user is logged in
// TODO: CEHCK IF DB IS INITALIZED.
if ($general->logged_in() === true) {
   // instantiate user class & connect to db.
   $user = new Admin($db->getDbConnection());

	$user_email = $_SESSION['email']; // getting user's id from the session.
//	$user_data_array = $user->getAllData($user_email); // getting all the data about the logged in user.
   $user->initData($user_email);
	// store the data used
//	$user_id = $user_data_array['id'];
//	$first_name = $user_data_array['f_name'];
//	$last_name = $user_data_array['l_name'];
//	$avatar_img_loc = $user_data_array['img_loc']; //avatar
//	$profile_description = $user_data_array['profile_description']; //short description in profile page
//	$date_account_created = $user_data_array['date']; //date of account creation
//	$user_type = $user_data_array['type']; //if s/he is admin/secretary/tutor
//	$mobile_num = $user_data_array['mobile'];
//	$tutor_major = $user_data_array['name']; //major of the tutor. in admin & secretary NO major (NULL)
}
?>