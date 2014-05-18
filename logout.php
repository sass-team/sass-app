<?php
session_start();
session_destroy();


if(!isset($_SESSION['email'])) {
	header("location:login.php");
}else {
header("location:login.php"); }
?>