<?php
session_start();
/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/12/14
 * Time: 11:51 PM

*/
require ROOT_PATH . 'inc/db.php';
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<title><?php echo $pageTitle; ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="">
	<meta name="author" content="" />

	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,800italic,400,600,800" type="text/css">

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/font-awesome.min.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.css" type="text/css" />

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>js/plugins/fileupload/bootstrap-fileupload.css" type="text/css" />
	
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>js/plugins/icheck/skins/minimal/blue.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>js/plugins/select2/select2.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>js/plugins/fullcalendar/fullcalendar.css" type="text/css" />

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/App.css" type="text/css" />

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/custom.css" type="text/css" />
</head>

<body>

<div id="wrapper">

	<header id="header">

		<h1 id="site-logo">
			<a href="<?php echo BASE_URL; ?>index.php">
				<img src="<?php echo BASE_URL; ?>img/logos/logo.png" alt="Site Logo" />
			</a>
		</h1>

		<a href="javascript:;" data-toggle="collapse" data-target=".top-bar-collapse" id="top-bar-toggle" class="navbar-toggle collapsed">
			<i class="fa fa-cog"></i>
		</a>

		<a href="javascript:;" data-toggle="collapse" data-target=".sidebar-collapse" id="sidebar-toggle" class="navbar-toggle collapsed">
			<i class="fa fa-reorder"></i>
		</a>

	</header> <!-- header -->

	<nav id="top-bar" class="collapse top-bar-collapse">

	<?php 
          if(isset($_SESSION['email'])){
    ?>
		<ul class="nav navbar-nav pull-right">
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
					<i class="fa fa-user"></i>
					<?php echo "Welcome " . $_SESSION['email']; ?>
					<span class="caret"></span>
				</a>

				<ul class="dropdown-menu" role="menu">
					<!-- "My schedule" choice is shown only to type tutor -->
					<?php if($_SESSION["user_types_id"] == '3') { ?>					
					<li>
						<a href="<?php echo BASE_URL; ?>page-calendar.html">
							<i class="fa fa-calendar"></i>
							&nbsp;&nbsp;My Schedule
						</a>
					</li>
					<li class="divider"></li>
					<?php } ?>
					<li>
						<a href="<?php echo BASE_URL; ?>logout.php">
							<i class="fa fa-sign-out"></i>
							&nbsp;&nbsp;Logout
						</a>
					</li>
				</ul>
			</li>
		</ul>
	<?php } ?>

	</nav> <!-- /#top-bar -->