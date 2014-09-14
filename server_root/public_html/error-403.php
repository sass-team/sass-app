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

	<base href="http://<?php echo $_SERVER['HTTP_HOST']; ?>">
	<title>Forbidden </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="description" content="">
	<meta name="author" content=""/>

	<link rel="stylesheet"
	      href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,800italic,400,600,800"
	      type="text/css">
	<link rel="stylesheet" href="assets/css/font-awesome.min.css" type="text/css"/>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"/>
	<link rel="stylesheet" href="assets/js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.css" type="text/css"/>

	<link rel="stylesheet" href="assets/css/App.css" type="text/css"/>

	<link rel="stylesheet" href="assets/css/custom.css" type="text/css"/>

</head>

<body>

<div id="wrapper">

	<header id="header">

		<h1 id="site-logo">
			<a href="/">
				<img src="assets/img/logos/logo.png" alt="Site Logo"/>
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
			<h1>Forbidden</h1>
		</div>
		<!-- #content-header -->


		<div id="content-container">


			<div class="row">

				<div class="col-md-12">

					<div class="error-container">

						<div class="error-code">
							403
						</div> <!-- /.error-code -->

						<div class="error-details">
							<h3>Oops, You're not allowed here.</h3>
							<p><!-- Or try the search bar below--></p>
							<!-- Example row of columns -->
							<div class="row">
								<div class="col-md-12">
									<p>A 403 error status indicates that you don't have permission to access the file or page. In general, web servers and websites have directories and files that are not open to the public web for security reasons.</p>
									<p><a href="/" name="forgot" class="btn btn-default">
											Back to Dashboard
										</a></p>
								</div>
								<div class="col-md-6"></div>
							</div>
							<!--<form action="./" class="form">
								<div class="input-group">
									<input type="text" class="form-control" placeholder="Search...">
							      <span class="input-group-btn">
							        <button class="btn btn-primary" type="button">
								        <i class="fa fa-search"></i>
							        </button>
							      </span>
								</div><!-- /input-group -->

							<!--</form>-->
						</div> <!-- /.error-details -->

					</div> <!-- /.error -->

				</div> <!-- /.col-md-12 -->

			</div> <!-- /.row -->


		</div>
		<!-- /#content-container -->


	</div>
	<!-- #content -->


</div>
<!-- #wrapper -->

<?php function auto_copyright($year = 'auto') { ?>
	<?php if (intval($year) == 'auto') {
		$year = date('Y');
	} ?>
	<?php if (intval($year) == date('Y')) {
		echo intval($year);
	} ?>
	<?php if (intval($year) < date('Y')) {
		echo intval($year) . ' - ' . date('Y');
	} ?>
	<?php if (intval($year) > date('Y')) {
		echo date('Y');
	} ?>
<?php } ?>

<footer id="footer" class="navbar navbar-fixed-bottom">
	<ul class="nav pull-right">
		<li>
			Copyright &copy; <?php auto_copyright('2014'); // 2010 - 2011 ?>, <a href="https://github.com/rdok">rdok</a>
			&amp; <a href="http://gr.linkedin.com/pub/georgios-skarlatos/70/461/123">geoif</a>

		</li>
	</ul>
</footer>

<script src="assets/js/libs/jquery-1.9.1.min.js"></script>
<script src="assets/js/libs/jquery-ui-1.9.2.custom.min.js"></script>
<script src="assets/js/libs/bootstrap.min.js"></script>

<script src="assets/js/App.js"></script>

</body>
</html>