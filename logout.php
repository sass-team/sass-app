<?php
session_start();
session_destroy();


if(!isset($_SESSION['email'])) {
	header("location:index.php");
}else {
header("location:index.php"); }
?>