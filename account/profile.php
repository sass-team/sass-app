<?php
require '../app/init.php';
$general->logged_out_protect();

$page_title = "My Account - Profile";
$section = "account";
require ROOT_PATH . 'app/views/header.php';
require ROOT_PATH . 'app/views/sidebar.php';
?>


	<div id="content">

		<div id="content-header">
			<h1>Profile</h1>
		</div>
		<!-- #content-header -->


		<div id="content-container">


			<div class="row">

				<div class="col-md-9">

					<div class="row">

						<div class="col-md-4 col-sm-5">

							<div class="thumbnail">
								<img src="<?php echo BASE_URL . "app/assets/" . $user->getAvatarImgLoc(); ?>"
								     alt="Profile Picture"/>
							</div>
							<!-- /.thumbnail -->

							<br/>

						</div>
						<!-- /.col -->


						<div class="col-md-8 col-sm-7">

							<h2><?php echo $user->getFirstName() . " " . $user->getLastName(); ?></h2>

							<h4>Position: <?php echo ucfirst($user->getUserType()) ?></h4>

							<hr/>

							<p>
								<a href="javascript:;" class="btn btn-primary">Follow Rod</a>
								&nbsp;&nbsp;
								<a href="javascript:;" class="btn btn-secondary">Send Message</a>
							</p>

							<hr/>


							<ul class="icons-list">
								<li><i class="icon-li fa fa-envelope"></i> <?php echo $_SESSION['email']; ?></li>
								<li><i class="icon-li fa fa-phone"></i> <?php echo $user->getMobileNum() ?></li>
							</ul>
							<?php if ($user->is_tutor()) { ?>

								Major: <strong><?php echo $user->getMajor() ?></strong>

							<?php } ?>
							<br/>
							<br/>

							<p>

							<h3>About me</h3></p>
							<p><?php echo $user->getProfileDescription() ?></p>

							<hr/>

							<br/>

						</div>

					</div>

				</div>

			</div>
			<!-- /.row -->

		</div>
		<!-- /#content-container -->

	</div> <!-- #content -->

	</div> <!-- #wrapper -->

<?php include ROOT_PATH . "app/views/footer.php"; ?>