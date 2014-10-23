<?php
require __DIR__ . '/../app/init.php';
$general->loggedOutProtect();
// redirect if user elevation is not that of admin
if (!$user->isAdmin()) {
	header('Location: ' . BASE_URL . "error-403");
	exit();
}

$appInfoFile = ROOT_PATH . "config/dropbox.app";
# Include the Dropbox SDK libraries
require_once ROOT_PATH . "plugins/dropbox-php-sdk-1.1.3/lib/Dropbox/autoload.php";
use Dropbox as dbx;

try {
	$accessToken = DropboxFetcher::retrieveAccessToken($user->getId())[DropboxFetcher::DB_COLUMN_ACCESS_TOKEN];
//	var_dump($accessToken);
	$appInfo = dbx\AppInfo::loadFromJsonFile($appInfoFile);
	$clientIdentifier = "sass-app/1.0";
	$webAuth = new dbx\WebAuthNoRedirect($appInfo, $clientIdentifier, "en");

	if ($accessToken !== NULL) {
		$dbxClient = new dbx\Client($accessToken, "PHP-Example/1.0");
		$adminAccountInfo = $dbxClient->getAccountInfo();

//		$filePath = ROOT_PATH . "storage/backups/";
//		$fileName = "database_backup_8_am_October_12_2014.sql.gz";
//		$f = fopen($filePath . $fileName, "rb");
//		$result = $dbxClient->uploadFile("/backups/$fileName", dbx\WriteMode::add(), $f);
//		fclose($f);
	}

	if (isBtnRqstTokenKeyPrsd()) {
		$authorizeUrl = $webAuth->start();
		header("Location: $authorizeUrl");
	} else if (isset($_POST['dropbox-auth-finish-database-backup'])) {
		$authCode = $_POST['dropbox-key-token'];
		if (empty($authCode)) throw new Exception("Key token is required.");
		list($accessToken, $userId) = $webAuth->finish($authCode);
		DropboxCon::insertAccessToken($accessToken, $user->getId(), DropboxCon::SERVICE_APP_DATABASE_BACKUP);

		header('Location: ' . BASE_URL . "cloud/backups/success");
		exit();
	} else if (isset($_POST['dropbox-disconnect-database-backup'])) {
		DropboxFetcher::disconnectServiceType(DropboxCon::SERVICE_APP_DATABASE_BACKUP);
		header('Location: ' . BASE_URL . "cloud/backups/success");
		exit();
	}

} catch (Exception $e) {
	$errors[] = $e->getMessage();
}


function isModificationSuccess() {
	return isset($_GET['success']) && strcmp($_GET['success'], 'y1!q' === 0);
}


function isBtnRqstTokenKeyPrsd() {
	return isset($_POST['dropbox-auth-start']) && empty($_POST['dropbox-auth-start']);
}


// viewers
$pageTitle = "Dropbox - SASS App";
$section = "cloud";


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
			<?php
			if (empty($errors) === false) {
				?>
				<div class="alert alert-danger">
					<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
					<strong>Oh
						snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>';
					?>
				</div>
			<?php
			} else if (isModificationSuccess()) {
				?>
				<div class="alert alert-success">
					<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
					<strong>Data successfully modified!</strong> <br/>
				</div>
			<?php
			}?>


			<div class="row">

				<div class="col-md-12">

					<div class="table-responsive">

						<form method="post" action="<?php echo BASE_URL . 'cloud/backups'; ?>"
						      id="request-dropbox-connection-form"
						      class="form">
							<table class="table table-striped table-bordered media-table">
								<thead>
								<tr>
									<th style="width: 150px">Services</th>
									<th>Description</th>
									<th class="text-center">Action</th>
									<th class="text-center">Keys</th>

								</tr>
								</thead>
								<tbody>
								<?php
								if ($accessToken !== NULL) {
									?>
									<tr>
										<td>
											<div class="thumbnail">
												<img
													src="<?php echo BASE_URL; ?>assets/img/logos/dropbox-database-sass.png"
													width="125"
													alt="Gallery Image"/>
											</div>
											<!-- /.thumbnail -->
										</td>
										<td><a href="https://www.dropbox.com/" title="Dropbox"
										       target="_blank">Dropbox</a>

											<p><strong>Account connected: <?php echo $adminAccountInfo['display_name']
														. ", " .
														$adminAccountInfo['email']; ?>
												</strong></p>

										</td>
										<td class="text-center">
											<div class="btn-group">
												<button type="button" class="btn btn-default" id="disconnect-dropbox-database-btn">
													<i class="fa fa-dropbox fa-fw"></i>
													Disconnect
												</button>
												<input type="hidden" class="form-control"
												       name="dropbox-disconnect-database-backup">
											</div>
										</td>
										<td>
										</td>
									</tr>
								<?php
								} else {
								?>

								<tr>

									<td>
										<div class="thumbnail">
											<img src="<?php echo BASE_URL; ?>assets/img/logos/dropbox-database-sass.png"
											     width="125"
											     alt="Gallery Image"/>
										</div>
										<!-- /.thumbnail -->
									</td>
									<td><a href="https://www.dropbox.com/" title="Dropbox" target="_blank">Dropbox</a>

										<p>Connects a Dropbox account with SASS App database files. <a
												href="http://en.wikipedia.org/wiki/SQL" title="sql"
												target="_blank">sql</a><br/>
										</p>

									</td>
									<td class="text-center">
										<div class="btn-group">
											<button type="button" class="btn btn-default dropdown-toggle"
											        data-toggle="dropdown"><i class="fa fa-dropbox fa-fw"></i>
												Request
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu" role="menu">
												<li><a id="request-dropbox-key-token-btn">Key Token</a></li>
												<li><a id="request-dropbox-connection-btn">Connection</a></li>
											</ul>
										</div>
									</td>
									<td>
										<input type="hidden" class="form-control"
										       name="dropbox-auth-finish-database-backup">
										<input type="text" class="form-control" name="dropbox-key-token"
										       placeholder="Key Token" required>
									</td>


									<?php
									}
									?>

								</tbody>
							</table>
						</form>
						<form target="_blank" id="request-dropbox-key-token-form" method="post"
						      action="<?php echo BASE_URL . 'cloud/backups'; ?>">
							<input type="hidden" name="dropbox-auth-start" value="">
						</form>
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

<script type="text/javascript">
	$(function () {
		$('#request-dropbox-key-token-btn').on('click', function () {
			$('#request-dropbox-key-token-form').submit();
		});

		$('#request-dropbox-connection-btn').on('click', function () {
			$('#request-dropbox-connection-form').submit();
		});

		$('#disconnect-dropbox-database-btn').on('click', function () {
			$('#request-dropbox-connection-form').submit();
		});


	});
</script>
</body>
</html>
