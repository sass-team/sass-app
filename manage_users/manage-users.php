<?php
require '../inc/init.php';
$general->logged_out_protect();

$page_title = "My Account - Profile";
$section = "manage_users";
require ROOT_PATH . 'inc/view/header.php';
require ROOT_PATH . 'inc/view/sidebar.php';
?>

<?php include ROOT_PATH . "inc/view/footer.php"; ?>