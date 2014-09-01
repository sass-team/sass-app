<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) <year> <copyright holders>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @author Rizart Dokollari
 * @since 8/16/14.
 */

?>

<?php
require '../app/init.php';
$general->loggedOutProtect();

// redirect if user elevation is not that of secretary or tutor
if (!$user->isAdmin()) {
	header('Location: ' . BASE_URL . "error-403");
	exit();
}


// protect again any sql injections on url
if (!isset($_GET['id']) || !preg_match("/^[0-9]+$/", $_GET['id'])) {
	header('Location: ' . BASE_URL . 'error-404');
	exit();
} else {
	$userId = $_GET['id'];
}


try {
	if (($data = User::retrieve($db, $userId)) === FALSE) {
		header('Location: ' . BASE_URL . 'error-404');
		exit();
	}


	if (strcmp($data['type'], 'tutor') === 0) {
		$curUser = new Tutor($db, $data['id'], $data['f_name'], $data['l_name'], $data['email'], $data['mobile'], $data['img_loc'], $data['profile_description'], $data['date'], $data['type'], $data['active']);
	} else if (strcmp($data['type'], 'secretary') === 0) {
		$curUser = new Secretary($db, $data['id'], $data['f_name'], $data['l_name'], $data['email'], $data['mobile'], $data['img_loc'], $data['profile_description'], $data['date'], $data['type'], $data['active']);
	} else if (strcmp($data['type'], 'admin') === 0) {
		$curUser = new Admin($db, $data['id'], $data['f_name'], $data['l_name'], $data['email'], $data['mobile'], $data['img_loc'], $data['profile_description'], $data['date'], $data['type'], $data['active']);
	} else {
		throw new Exception("Something terrible has happened with the database. <br/>The software developers will tremble with fear.");
	}

	// retrieve courses data only user type is tutor
	if ($curUser->isTutor()) {
		$teachingCourses = Tutor::retrieveTeachingCourses($db, $curUser->getId());
		$notTeachingCourses = Tutor::retrieveCoursesNotTeaching($db, $curUser->getId());
	}


	if (isAddTeachingCoursesPressed()) {

		if ($curUser->addTeachingCourses($_POST['teachingCourses'])) {
			header('Location: ' . BASE_URL . 'users/edit/:' . $userId . '/success');
			exit();
		}
	} else if (isBtnSubmitReplaceCourse()) {

		Tutor::updateTeachingCourse($db, $curUser->getId(), $_POST['teachingCourse'], $_POST['hiddenUpdateCourseOldId']);
		header('Location: ' . BASE_URL . 'users/edit/:' . $userId . '/success');
		exit();
	} else if (isSaveBttnProfilePressed()) {
		$newDataAdded = false;

		$newFirstName = $_POST['firstName'];
		$newLastName = $_POST['lastName'];
		$newEmail = $_POST['email'];

		$oldFirstName = $curUser->getFirstName();
		$oldLastName = $curUser->getLastName();
		$oldEmail = $curUser->getEmail();


		if (strcmp($newFirstName, $oldFirstName) !== 0) {
			$user->validateName($newFirstName);
			$user->updateInfo("f_name", "user", $newFirstName, $userId);
			$newDataAdded = true;
		}

		if (strcmp($newLastName, $oldLastName) !== 0) {
			$user->validateName($newLastName);
			$user->updateInfo("l_name", "user", $newLastName, $userId);
			$newDataAdded = true;
		}

		if (strcmp($newEmail, $oldEmail) !== 0) {
			Person::validateEmail($db, $newEmail, User::DB_TABLE);
			$user->updateInfo("email", "user", $newEmail, $userId);
			$newDataAdded = true;
		}

		if (!$newDataAdded) {
			throw new Exception("No new data. No modifications were done.");
		} else {
			header('Location: ' . BASE_URL . 'users/edit/:' . $userId . '/success');
			exit();
		}
	} else if (isBtnDelTeachingCoursesPressed()) {
		$curUser->delTeachingCourse($_POST['delCourseIdModal']);
		header('Location: ' . BASE_URL . 'users/edit/:' . $userId . '/success');
		exit();
	} else if (isBtnSbmtChangeuserTypePrsd()) {
		$curUser->updateUserType($_POST['changeuserType']);
		header('Location: ' . BASE_URL . 'users/edit/:' . $userId . '/success');
		exit();
	} else if (isBtnSbmtChangeUserActivate()) {
		User::updateActiveStatus($db, $curUser->getId(), $_POST['changeUserActive'], $curUser->isActive());
		header('Location: ' . BASE_URL . 'users/edit/:' . $userId . '/success');
		exit();
	}
} catch (Exception $e) {
	$errors[] = $e->getMessage();
}


function isSaveBttnProfilePressed() {
	return isset($_POST['hiddenSaveBttnProfile']) && empty($_POST['hiddenSaveBttnProfile']);
}

function isAddTeachingCoursesPressed() {
	return isset($_POST['hiddenSubmitAddTeachingCourse']) && empty($_POST['hiddenSubmitAddTeachingCourse']);
}

function isModificationSuccessful() {
	return isset($_GET['success']) && strcmp($_GET['success'], 'y1!' === 0);
}


function isBtnDelTeachingCoursesPressed() {
	return isset($_POST['hiddenSubmitDeleteCourse']) && empty($_POST['hiddenSubmitDeleteCourse']);
}

function isBtnSbmtChangeuserTypePrsd() {
	return isset($_POST['hiddenSbmtChangeuserType']) && empty($_POST['hiddenSbmtChangeuserType']);
}


function isBtnSbmtChangeUserActivate() {
	return isset($_POST['hiddenSbmtChangeUserActive']) && empty($_POST['hiddenSbmtChangeUserActive']);
}

function isBtnSubmitReplaceCourse() {
	return isset($_POST['hiddenSubmitReplaceCourse']) && empty($_POST['hiddenSubmitReplaceCourse']);
}


$page_title = "Edit";
$section = "users";

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
<?php require ROOT_PATH . 'app/views/head.php'; ?>
<body>
<div id="wrapper">
<?php
require ROOT_PATH . 'app/views/header.php';
require ROOT_PATH . 'app/views/sidebar.php';
?>


<div id="content">

<div id="content-header">
	<h1>Settings
		- <?php echo "<strong>" . $curUser->getFirstName() . " " . $curUser->getLastName() . "</strong>"; ?></h1>
</div>
<!-- #content-header -->


<div id="content-container">

<div class="row">

<?php if (empty($errors) !== true): ?>
	<div class="alert alert-danger">
		<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
		<strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
	</div>
<?php elseif (isModificationSuccessful()): ?>
	<div class="alert alert-success">
		<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
		<strong>Woohoo!</strong>

		<p>Update was successful.</p>
	</div>
<?php endif; ?>

<div class="col-md-3 col-sm-4">
	<ul id="myTab" class="nav nav-pills nav-stacked">

		<?php if ($curUser->isTutor()) { ?>
			<li class="active">
				<a href="#courses-majors" data-toggle="tab">
					<i class="fa fa-list-alt"></i>
					&nbsp;&nbsp; Teaching Courses
				</a>
			</li>
		<?php } ?>

		<li <?php if (!$curUser->isTutor()) echo "class='active'"; ?>>
			<a href="#profile-tab" data-toggle="tab">
				<i class="fa fa-user"></i>
				&nbsp;&nbsp;Profile Settings
			</a>
		</li>
		<li>
			<a href="#userType" data-toggle="tab">
				<i class="fa fa-flag-o"></i>
				&nbsp;&nbsp;Position
			</a>
		</li>
		<li>
			<a href="#status" data-toggle="tab">
				<i class="fa fa-warning"></i>
				&nbsp;&nbsp;Status
			</a>
		</li>
		<li>
			<a href="#notifications" data-toggle="tab">
				<i class="fa fa-envelope"></i>
				&nbsp;&nbsp;Notifications Settings
			</a>
		</li>

	</ul>
	<!-- #myTab -->
</div>
<!-- /.col-->

<div class="col-md-9 col-sm-8">

<div class="tab-content stacked-content">

<?php if ($curUser->isTutor()) { ?>
	<div class="tab-pane fade in active" id="courses-majors">


		<div class="col-md-12">

			<div class="portlet">

				<div class="portlet-header">

					<h3>
						<i class="fa fa-table"></i>
						Teaching Courses
					</h3>

				</div>
				<!-- /.portlet-header -->

				<div class="portlet-content">

					<div class="table-responsive">

						<table
							 class="table table-striped table-bordered table-hover table-highlight table-checkable"
							 data-provide="datatable"
							 data-display-rows="10"
							 data-info="true"
							 data-search="true"
							 data-length-change="true"
							 data-paginate="true"
							 >
							<thead>
							<tr>
								<th data-filterable="true" data-sortable="true" data-direction="desc">Code</th>
								<th data-direction="asc" data-filterable="true" data-sortable="false">Name</th>
								<th>Action</th>
							</tr>
							</thead>
							<tbody>

							<?php
							if (empty($errors) === true) {
								foreach ($teachingCourses as $course) {
									include(ROOT_PATH . "app/views/partials/course-table-data-view.html.php");

								}
							} ?>
							</tbody>
						</table>
					</div>
					<!-- /.table-responsive -->


				</div>
				<!-- /.portlet-content -->

			</div>
			<!-- /.portlet -->

			<br/>

			<div class="form-group">

				<a data-toggle="modal" id="bttn-coursesModal" href="#coursesModal" class="btn btn-primary">
					Add Teaching Courses
				</a>
				<!-- /.col-->

			</div>
			<!-- /.form - group-->
		</div>
		<!-- /.col -->

	</div>
<?php } ?>
<div class="tab-pane fade <?php if (!$curUser->isTutor()) echo "in active"; ?>" id="profile-tab">

	<h3 class=""> Edit Profile Settings </h3>

	<p>Here you will find all the changes that you can make for this user. The disabled fields are shown only
		for your convenience--to help you identify the user.</p>

	<hr/>

	<br/>

	<form action="<?php echo BASE_URL . 'users/edit/:' . $curUser->getId(); ?>"
	      class="form-horizontal" method="post">

		<div class="form-group">

			<label class="col-md-3"> Avatar</label>

			<div class="col-md-7">
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 180px; height: 180px;"><img
							 src="<?php echo BASE_URL . $curUser->getAvatarImgLoc(); ?>" alt="Profile Avatar"/>
					</div>
					<div class="fileupload-preview fileupload-exists thumbnail"
					     style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
					<div>
								<span disabled class="btn btn-default btn-file"><span
										 class="fileupload-new"> Select image </span><span
										 class="fileupload-exists"> Change</span><input type="file"></span>
						<a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">
							Remove</a>
					</div>
				</div>
				<!-- /.fileupload-->
			</div>
			<!-- /.col -->

		</div>
		<!-- ./form-group -->

		<div class="form-group">

			<label class="col-md-3" for="firstName"> First Name </label>

			<div class="col-md-7">
				<input type="text" name="firstName" id="firstName"
				       value="<?php echo $curUser->getFirstName(); ?>"
				       class="form-control">
			</div>
			<!-- /.col-->

		</div>
		<!-- /.form - group-->

		<div class="form-group">

			<label class="col-md-3" for="lastName"> Last Name </label>

			<div class="col-md-7">
				<input type="text" name="lastName" id="lastName"
				       value="<?php echo $curUser->getLastName(); ?>"
				       class="form-control">
			</div>
			<!-- /.col-->

		</div>
		<!-- /.form - group-->

		<div class="form-group">

			<label class="col-md-3" for="email"> Email Address </label>

			<div class="col-md-7">
				<input type="text" name="email" id="email"
				       value="<?php echo $curUser->getEmail(); ?>"
				       class="form-control">
			</div>
			<!-- /.col-->

		</div>
		<!-- /.form - group-->

		<div class="form-group">

			<label for="aboutTextarea" class="col-md-3">
				About <?php echo "<strong>" . $curUser->getFirstName() . " " . $curUser->getLastName() . "</strong>"; ?> </label>


			<div class="col-md-7">
				<textarea id="aboutTextarea" name="about-you" rows="6" disabled
				          class="form-control"><?php echo $curUser->getProfileDescription(); ?></textarea>
			</div>
			<!-- /.col-->

		</div>
		<!-- /.form - group-->

		<br/>

		<div class="form-group">

			<div class="col-md-7 col-md-push-3">
				<button type="submit" class="btn btn-primary"> Save Changes</button>
				<input type="hidden" name="hiddenSaveBttnProfile" value=""/>
				&nbsp;
				<a type="reset" class="btn btn-default" href="<?php echo BASE_URL . "users/overview"; ?>">
					Cancel</a>
			</div>
			<!-- /.col-->

		</div>
		<!-- /.form - group-->

	</form>
	<!-- /form - data -->

</div>

<div class="tab-pane fade" id="userType">
	<div class="col-md-12">

		<div class="portlet">

			<div class="portlet-header">

				<h3>
					<i class="fa fa-hand-o-up"></i>
					User Type
				</h3>

			</div>
			<!-- /.portlet-header -->

			<div class="portlet-content">

				<form method="post" id="delete-form" action="" class="form">
					<div class="btn-group">
						<button type="submit"
						        class="btn btn-default <?php if ($curUser->isTutor()) echo "active"; ?> btnChangeType"
						        id="tutor" name="tutor" disabled>Tutor
						</button>
						<button type="submit"
						        class="btn btn-default <?php if ($curUser->isSecretary()) echo "active"; ?> btnChangeType"
						        id="secretary" name="secretary" disabled>
							Secretary
						</button>
						<button type="submit"
						        class="btn btn-default <?php if ($curUser->isAdmin()) echo "active"; ?> btnChangeType"
						        id="admin"
						        name="admin" disabled>
							Administrator
						</button>
						<input type="hidden" name="hiddenSbmtChangeuserType">
						<input type="hidden" id="changeuserType" name="changeuserType" value="">

					</div>


				</form>

				<br/>


			</div>
			<!-- /.portlet-content -->

		</div>
		<!-- /.portlet -->

	</div>
	<!-- /.col -->
</div>


<div class="tab-pane fade" id="status">
	<div class="col-md-12">

		<div class="portlet">

			<div class="portlet-header">

				<h3>
					<i class="fa fa-hand-o-up"></i>
					Account Status
				</h3>

			</div>
			<!-- /.portlet-header -->

			<div class="portlet-content">
				<form method="post" id="update-status-form" action="" class="form">

					<button type="submit"
					        class="btn btn-success <?php if ($curUser->isActive()) echo "active disabled"; ?> btnChangeActive"
					        id="activateAccount">Activate
					</button>

					<button type="submit"
					        class="btn btn-warning <?php if (!$curUser->isActive()) echo "active disabled"; ?> btnChangeActive"
					        id="deactivateAccount">Deactivate
					</button>
					<input type="hidden" name="hiddenSbmtChangeUserActive" value="">
					<input type="hidden" id="changeUserActive" name="changeUserActive" value="">

				</form>
			</div>
			<!-- /.portlet-content -->

		</div>
		<!-- /.portlet -->

	</div>
	<!-- /.col -->


</div>

<div class="tab-pane fade" id="notifications">
	<h3> Notification Settings </h3>

	<p> Email when a new workshop is assgined to tutor</p>

	<p> Email when my profile is update</p>

	<p> Email when a workshop session is due to next day</p>

	<h4>Please bare in mind that up to 25 sms can be in sent daily.</h4>

	<p> SMS when a new workshop is assgined to tutor</p>

	<p> SMS when my profile is update</p>

	<p> SMS when a workshop session is due to next day</p>

</div>

</div>
<!-- /.tab-content stacked-content -->
</div>
<!-- /.col-md-9 col-sm-8-->

</div>
<!-- /.row-->


</div>
<!-- #content-container -->


</div>
<!-- #content -->


<div id="coursesModal" class="modal modal-styled fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="create-form" action="" class="form">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">Teaching Courses Form</h3>
				</div>
				<div class="modal-body">
					<div class="portlet">
						<?php
						if (empty($errors) === false) {
							?>
							<div class="alert alert-danger">
								<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
								<strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
							</div>
						<?php } ?>

						<div class="portlet-content">

							<div class="row">


								<div class="col-sm-12">

									<div class="form-group">

										<h5>
											<i class="fa fa-tasks"></i>
											<label for="teachingCoursesMulti">Courses</label>
										</h5>

										<select id="teachingCoursesMulti" name="teachingCourses[]"
										        class="form-control"
										        multiple>

											<?php
											foreach ($notTeachingCourses as $course) {
												include(ROOT_PATH . "app/views/partials/course-select-options-view.html.php");
											}
											?>

										</select>
									</div>


								</div>
							</div>

						</div>

					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-tertiary" data-dismiss="modal">Close</button>
					<input type="hidden" name="hiddenSubmitAddTeachingCourse">
					<button type="submit" class="btn btn-primary">Add</button>
				</div>
			</form>

		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<div id="deleteCourse" class="modal modal-styled fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="delete-form" action="" class="form">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">Remove course
						from <?php echo $curUser->getFirstName() . " " . $curUser->getLastName(); ?></h3>
				</div>
				<div class="modal-body">
					<div class="portlet">
						<?php
						if (empty($errors) === false) {
							?>
							<div class="alert alert-danger">
								<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
								<strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
							</div>
						<?php } ?>

						<div class="portlet-content">

							<div class="row">
								<div class="alert alert-warning">
									<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
									<strong>Warning!</strong><br/>Tutor will not be able to teach this course anymore.
								</div>
							</div>

						</div>

					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-tertiary" data-dismiss="modal">Cancel</button>
					<input type="hidden" id="delCourseIdModal" name="delCourseIdModal" value=""/>
					<input type="hidden" name="hiddenSubmitDeleteCourse">
					<button type="submit" class="btn btn-primary">Delete</button>
				</div>
			</form>

		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div id="updateCourse" class="modal modal-styled fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="delete-form" action="" class="form">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">Update Course
						for <?php echo $curUser->getFirstName() . " " . $curUser->getLastName(); ?></h3>
				</div>
				<div class="modal-body">
					<div class="portlet">

						<div class="portlet-content">

							<div class="col-sm-12">

								<h4 class="heading_a">New Course</h4>

								<select id="teachingCourse" name="teachingCourse"
								        class="form-control">


									<?php foreach ($notTeachingCourses as $course) {
										include(ROOT_PATH . "app/views/partials/course-select-options-view.html.php");
									}
									?>

								</select>

								<hr>
								<?php
								if (empty($errors) === false) {
									?>
									<div class="alert alert-danger">
										<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
										<strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
									</div>
								<?php } ?>

								<div class="alert alert-info">
									<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
									<strong>Careful!</strong><br/>The previous course will be replaced, and all it's upcoming
									workshop sessions data will be deleted.
								</div>
							</div>


						</div>

					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-tertiary" data-dismiss="modal">Cancel</button>
					<input type="hidden" name="hiddenSubmitReplaceCourse">
					<input type="hidden" name="hiddenUpdateCourseOldId" id="hiddenUpdateCourseOldId">
					<button type="submit" class="btn btn-primary">Replace</button>
				</div>
			</form>

		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<?php include ROOT_PATH . "app/views/footer.php"; ?>
</div>
<!-- #wrapper -->


<?php include ROOT_PATH . "app/views/assets/footer_common.php"; ?>

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/select2/select2.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/icheck/jquery.icheck.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/timepicker/bootstrap-timepicker.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/simplecolorpicker/jquery.simplecolorpicker.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/demos/form-extended.js"></script>


<script type="text/javascript">
	jQuery(function () {
		// prepare course id for delete on modal
		$(".btnDeleteCourse").click(function () {
			$inputVal = $(this).next('input').val();
			$("#delCourseIdModal").val($inputVal);
		});

		$("#teachingCourse").select2({
			placeholder: "Select..."
		});

		$("#teachingCoursesMulti").select2({
			placeholder: "Select courses..."
		});


		$(".btnChangeType").click(function () {
			$id = $(this).attr('id');
			$("#changeuserType").val($id);
		});

		$(".btnChangeActive").click(function () {
			$id = $(this).attr('id');
			$("#changeUserActive").val($id);
		});

		$(".btnUpdateCourse").click(function () {
			$courseId = $(this).next().next('input');
			$("#hiddenUpdateCourseOldId").val($courseId.val());
		});
	});


</script>
<!--        $("#create-form").submit(function (event) {-->
<!--            var error_last_name = validate($("#last_name"), /^[a-zA-Z]{1,16}$/);-->
<!--            var error_first_name = validate($("#first_name"), /^[a-zA-Z]{1,16}$/);-->
<!---->
<!--//			if ($('input[name=user_type').val() === "tutor") {-->
<!--//				alert("tutor");-->
<!--//			}-->
<!--            if (!error_last_name || !error_first_name) {-->
<!--                //event.preventDefault();-->
<!--            }-->
<!--        });-->
<!---->
<!--        setTimeout(function () {-->
<!--            $("#bttn-coursesModal").trigger("click");-->
<!--            //window.location.href = $href;-->
<!--        }, 10);-->
<!---->
<!--        $("#user_major").select2();-->

<!---->


</body>
</html>
