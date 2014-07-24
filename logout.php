<?php
session_start();
session_destroy();


if(!isset($_SESSION['email'])) {
	header("location:edit.php");
}else {
header("location:edit.php"); }
?>