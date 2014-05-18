<?php 
require_once(ROOT_PATH . "inc/model/user.php");

// instantiate user class & connect to db.
$users = new Users($db);

$user_email = $_SESSION['email']; // getting user's id from the session.
$user_data_array = $users->get_data($user_email); // getting all the data about the logged in user.

$f_name =	$user_data_array['f_name'];
$l_name =	$user_data_array['l_name'];
$img =		$user_data_array['img'];	//avatar
$profile_description = $user_data_array['profile_description'];	//short description in profile page
$date =		$user_data_array['date'];	//date of account creation
$type =		$user_data_array['type'];	//if s/he is admin/secretary/tutor
$mobile =	$user_data_array['mobile'];
$major =	$user_data_array['name'];	//major of the tutor. in admin & secretary NO major (NULL)

?>