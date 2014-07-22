<?php
require "inc/app.php";
?>

<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>

	<title>Internal Server Error - Canvas Admin</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="description" content="">
	<meta name="author" content=""/>

	<link rel="stylesheet"
	      href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,800italic,400,600,800"
	      type="text/css">
	<link rel="stylesheet" href="app/assets/css/font-awesome.min.css" type="text/css"/>
	<link rel="stylesheet" href="app/assets/css/bootstrap.min.css" type="text/css"/>
	<link rel="stylesheet" href="app/assets/js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.css" type="text/css"/>

	<link rel="stylesheet" href="app/assets/css/App.css" type="text/css"/>

	<link rel="stylesheet" href="app/assets/css/custom.css" type="text/css"/>

</head>

<body>

<div id="wrapper">

<header id="header">

	<h1 id="site-logo">
		<a href="index.php">
			<img src="app/assets/img/logos/logo.png" alt="Site Logo"/>
		</a>
	</h1>

	<a href="javascript:;" data-toggle="collapse" data-target=".top-bar-collapse" id="top-bar-toggle"
	   class="navbar-toggle collapsed">
		<i class="fa fa-cog"></i>
	</a>

	<a href="javascript:;" data-toggle="collapse" data-target=".sidebar-collapse" id="sidebar-toggle"
	   class="navbar-toggle collapsed">
		<i class="fa fa-reorder"></i>
	</a>

</header>
<!-- header -->


<nav id="top-bar" class="collapse top-bar-collapse"></nav>
<!-- /#top-bar -->


<div id="sidebar-wrapper" class="collapse sidebar-collapse"></div>
<!-- /#sidebar-wrapper -->


<div id="content">

	<div id="content-header">
		<h1>Internal Server Error</h1>
	</div>
	<!-- #content-header -->


	<div id="content-container">


		<div class="row">

			<div class="col-md-12">

				<div class="error-container">

					<div class="error-code">
						500
					</div>
					<!-- /.error-code -->

					<div class="error-details">
						<h4>There was a problem serving the requested page.</h4>
						<br/>

						<p><strong>What should I do:</strong></p>


						<ul class="icons-list">
							<li>
								<i class="icon-li fa fa-check-square-o"></i>
								you can try refreshing the page, the problem may be temporary
							</li>
							<li>
								<i class="icon-li fa fa-check-square-o"></i>
								if you entered the url by hand, double check that it is correct
							</li>
							<li>
								<i class="icon-li fa fa-check-square-o"></i>
								Nothing! we've been notified of the problem and will do our best to make sure it doesn't
								happen again!
							</li>
							<li>
								<i class="icon-li fa fa-check-square-o"></i>
								Contact the <a
									href="mailto:r.dokollari@gmail.com,G.Skarlatos@acg.edu?Subject=<?php echo $_SERVER['SERVER_NAME']; ?>&#58;%20Errors%20Specifics"
									target="_blank">Developers</a>.<strong> Please include:</strong>
								<ol>
									<li>url where the error occured.</li>
									<li>The exact order and steps to re-produce the error.</li>
									<li>Anything else you believe we should be aware.</li>
								</ol>
								Please note that if the subject provided is modified and you do not provide above requirements
								the developers most probably will <strong>not</strong> respond to your mail.
							</li>
						</ul>
					</div>
					<!-- /.error-details -->

				</div>
				<!-- /.error -->

			</div>
			<!-- /.col-md-12 -->

		</div>
		<!-- /.row -->


	</div>
	<!-- /#content-container -->


</div>
<!-- #content -->


</div>
<!-- #wrapper -->

<footer id="footer">
	<ul class="nav pull-right">
		<li>
			Copyright &copy; 2013, Jumpstart Themes.
		</li>
	</ul>
</footer>

<script src="app/assets/js/libs/jquery-1.9.1.min.js"></script>
<script src="app/assets/js/libs/jquery-ui-1.9.2.custom.min.js"></script>
<script src="app/assets/js/libs/bootstrap.min.js"></script>

<script src="app/assets/js/App.js"></script>

</body>
</html>