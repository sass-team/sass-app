<?php
require __DIR__ . '/app/init.php';
$general->loggedOutProtect();

// viewers
$pageTitle = "Support Center - SASS App";
$section = "support";

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
		<h1>Support Center</h1>
	</div>
	<!-- #content-header -->


	<div id="content-container">


		<div class="row">

			<div class="col-md-8">

				<h3 class="heading">Section Categories</h3>

				<!-- <hr /> -->

				<div class="row">

					<div class="col-md-6 col-sm-6">

						<h4>
							<i class="fa fa-folder-close"></i> &nbsp;
							Appointments
							&nbsp;
							<!--							<small>(1)</small>-->
						</h4>

						<ul class="icons-list support-list">
							<?php if ($user->isTutor()): ?>
								<li>
									<i class="icon-li fa fa-youtube-play"></i>
									<a rel="lightbox"
									   href="https://www.youtube.com/watch?v=R2QcWxyr9P4&list=UUum32knqNNMJWP-DkKqpBDg"
									   data-type="youtube"
									   data-title="How Can I View My Appointments?"
									   data-toggle="lightbox"
									   data-width="1024"
									   class="youtube-vid">How Can I View My Appointments?</a>
								</li>
							<?php else: ?>
								<li>
									<i class="icon-li fa fa-youtube-play"></i>
									<a rel="lightbox"
									   href="https://www.youtube.com/watch?v=sCW0N-B8_oQ&list=UUum32knqNNMJWP-DkKqpBDg"
									   data-type="youtube"
									   data-title="How Can I Delete An Appointment?"
									   data-toggle="lightbox"
									   data-width="1024"
									   class="youtube-vid">How Can I Delete An Appointment?</a>
								</li>
							<?php endif; ?>
						</ul>


					</div>
					<!-- /.col-md-6 -->


					<div class="col-md-6 col-sm-6">

						<h4>
							<i class="fa fa-folder-close"></i>
							SASS Staff
						</h4>

						<ul class="icons-list support-list">
							<li>
								<i class="icon-li fa fa-youtube-play"></i>
								<a rel="lightbox"
								   href="https://www.youtube.com/watch?v=qSMVMejo_GU&feature=youtu.be"
								   data-type="youtube"
								   data-title="How Can I View SASS Personnel?"
								   data-toggle="lightbox"
								   data-width="1024"
								   class="youtube-vid">How Can I View SASS Personnel?</a>
							</li>
						</ul>


					</div>
					<!-- /.col-md-6 -->

				</div>
				<!-- /.row -->


				<div class="row">
					<?php if (!$user->isTutor()): ?>

						<div class="col-md-6 col-sm-6">
							<h4>
								<i class="fa fa-folder-close"></i>
								Academia
							</h4>

							<ul class="icons-list support-list">
								<li>
									<i class="icon-li fa fa-youtube-play"></i>
									<a rel="lightbox"
									   href="https://www.youtube.com/watch?v=D4G6ZXCD8jI&list=UUum32knqNNMJWP-DkKqpBDg"
									   data-type="youtube"
									   data-title="How Can I Add/Edit/Delete Academia Data?"
									   data-toggle="lightbox"
									   data-width="1024"
									   class="youtube-vid">How Can I Add/Edit/Delete Academia Data?</a>
								</li>
							</ul>


						</div>
						<!-- /.col-md-6 -->
					<?php endif; ?>

					<div class="col-md-6 col-sm-6">
						<h4>
							<i class="fa fa-folder-close"></i>
							Account
						</h4>

						<ul class="icons-list support-list">
							<li>
								<i class="icon-li fa fa-youtube-play"></i>
								<a rel="lightbox"
								   href="https://www.youtube.com/watch?v=Qmr4aEpm_f4&list=UUum32knqNNMJWP-DkKqpBDg"
								   data-type="youtube"
								   data-title="What Do I Need To Know About My Account?"
								   data-toggle="lightbox"
								   data-width="1024"
								   class="youtube-vid">What Do I Need To Know About My Account?</a>
							</li>

						</ul>


					</div>
					<!-- /.col-md-6 -->

				</div>
				<!-- /.row -->


			</div>
			<!-- /.col-md-8 -->

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


<script src="<?php echo BASE_URL; ?>assets/js/plugins/lightbox-master/dist/ekko-lightbox.min.js"></script>

<script type="text/javascript">

	$(document).delegate('*[data-toggle="lightbox"]', 'click', function (event) {
		event.preventDefault();
		$(this).ekkoLightbox();
	});
</script>

</body>
</html>
