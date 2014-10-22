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
	if (isBtnConnectDropboxPrsd()) {
		$authorizeUrl = getWebAuth()->start();
		header("Location: $authorizeUrl");
		var_dump($_POST);

	} else if (isset($_GET['dropbox-auth-finish'])) {
		try {
			list($accessToken, $userId, $urlState) = getWebAuth()->finish($_GET);
			// We didn't pass in $urlState to finish, and we're assuming the session can't be
			// tampered with, so this should be null.
			assert($urlState === null);
		} catch (dbx\WebAuthException_BadRequest $ex) {
			respondWithError(400, "Bad Request");
			// Write full details to server error log.
			// IMPORTANT: Never show the $ex->getMessage() string to the user -- it could contain
			// sensitive information.
			error_log("/dropbox-auth-finish: bad request: " . $ex->getMessage());
			exit;
		} catch (dbx\WebAuthException_BadState $ex) {
			// Auth session expired.  Restart the auth process.
			header("Location: " . getPath("dropbox-auth-start"));
			exit;
		} catch (dbx\WebAuthException_Csrf $ex) {
			respondWithError(403, "Unauthorized", "CSRF mismatch");
			// Write full details to server error log.
			// IMPORTANT: Never show the $ex->getMessage() string to the user -- it contains
			// sensitive information that could be used to bypass the CSRF check.
			error_log("/dropbox-auth-finish: CSRF mismatch: " . $ex->getMessage());
			exit;
		} catch (dbx\WebAuthException_NotApproved $ex) {
			throw new Exception("Not Authorized?");
			exit;
		} catch (dbx\WebAuthException_Provider $ex) {
			error_log("/dropbox-auth-finish: unknown error: " . $ex->getMessage());
			respondWithError(500, "Internal Server Error");
			exit;
		} catch (dbx\Exception $ex) {
			error_log("/dropbox-auth-finish: error communicating with Dropbox API: " . $ex->getMessage());
			respondWithError(500, "Internal Server Error");
			exit;
		}

		// NOTE: A real web app would store the access token in a database.
		$_SESSION['access-token'] = $accessToken;

		echo renderHtmlPage("Authorized!",
			"Auth complete, <a href='" . htmlspecialchars(getPath("")) . "'>click here</a> to browse.");
	} else {
	}
} catch (Exception $e) {
	$errors[] = $e->getMessage();
}

function respondWithError($code, $title, $body = "") {
	$proto = $_SERVER['SERVER_PROTOCOL'];
	header("$proto $code $title", true, $code);
	$errors[] = $title . ": " . $body;
}

function getWebAuth() {

	list($appInfo, $clientIdentifier, $userLocale) = getAppConfig();

	$redirectUri = "http://" . $_SERVER['SERVER_NAME'] . "/cloud/backups/dropbox-auth-finish";
	$csrfTokenStore = new dbx\ArrayEntryStore($_SESSION, 'dropbox-auth-csrf-token');
	return new dbx\WebAuth($appInfo, $clientIdentifier, $redirectUri, $csrfTokenStore, $userLocale);
}

function getAppConfig() {
	global $appInfoFile;

	try {
		$appInfo = dbx\AppInfo::loadFromJsonFile($appInfoFile);
	} catch (dbx\AppInfoLoadException $ex) {
		throw new Exception("Unable to load \"$appInfoFile\": " . $ex->getMessage());
	}

	$clientIdentifier = "sass-app/1.0";
	$userLocale = null;

	return array($appInfo, $clientIdentifier, $userLocale);
}

function isBtnConnectDropboxPrsd() {
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
			<?php } ?>
			<div class="row">

				<div class="col-md-12">

					<div class="table-responsive">

						<form method="post" id="add-student-form"
						      action="<?php echo BASE_URL . 'cloud/backups'; ?>"
						      class="form">
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
											<img src="<?php echo BASE_URL; ?>assets/img/logos/dropbox-logo.png"
											     width="125"
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
										<input type="hidden" name="dropbox-auth-start" value="">
									</td>
								</tr>


								</tbody>
							</table>
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


</body>
</html>
