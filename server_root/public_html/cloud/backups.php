<?php
require __DIR__ . '/../app/init.php';
$general->loggedOutProtect();
// redirect if user elevation is not that of admin
if (!$user->isAdmin()) {
	header('Location: ' . BASE_URL . "error-403");
	exit();
}

if (isBtnConnectAppPrsd()) {
	var_dump($_POST);
}

// viewers
$pageTitle = "Dropbox - SASS App";
$section = "cloud";

function isBtnConnectAppPrsd() {
	return isset($_POST['hiddenConnectWholeApp']) && empty($_POST['hiddenConnectWholeApp']);
}

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
<?php require ROOT_PATH . 'views/head.php'; ?>
<body>
<div id="wrapper">
	<?php
	require ROOT_PATH . 'views/header.php';
	require ROOT_PATH . 'views/sidebar.php';
	?>


	<div id="content">

		<div id="content-header">
			<h1>Cloud</h1>
		</div>
		<!-- #content-header -->

		<div id="content-container">


			<h3 class="heading">Cloud Services</h3>

			<div class="row">

				<div class="col-md-12">

					<div class="table-responsive">

						<table class="table table-striped table-bordered media-table">
							<thead>
							<tr>
								<th style="width: 150px">Services</th>
								<th>Description</th>
								<th class="text-center">Actions</th>
							</tr>
							</thead>
							<tbody>

							<tr>
								<td>
									<div class="thumbnail">
										<img src="<?php echo BASE_URL; ?>assets/img/logos/dropbox-logo.png" width="125"
										     alt="Gallery Image"/>
									</div>
									<!-- /.thumbnail -->
								</td>
								<td><a href="https://www.dropbox.com/" title="Dropbox" target="_blank">Dropbox</a>

									<p>Connects a Dropbox account with SASS App database files. <a
											href="http://en.wikipedia.org/wiki/SQL" title="sql"
											target="_blank">sql</a></p>


								</td>
								<td class="text-center">
									<button type="submit" class="btn btn-block btn-primary">Connect</button>
									<input type="hidden" name="hiddenConnectWholeApp" value="">
								</td>
							</tr>


							</tbody>
						</table>

					</div>
					<!-- /.table-responsive -->


				</div>
				<!-- /.col -->

			</div>
			<!-- /.row -->


		</div>
		<!-- /#content-container -->


	</div>
	<!-- #content -->


	<?php include ROOT_PATH . "views/footer.php"; ?>
</div>
<!-- #wrapper<!-- #content -->

<?php include ROOT_PATH . "views/assets/footer_common.php"; ?>


</body>
</html>
