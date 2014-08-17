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
$general->logged_out_protect();

// protect again any sql injections on url
if (isset($_GET['id']) && !preg_match("/^[0-9]+$/", $_GET['id'])) {
	header('Location: ' . BASE_URL . 'error-404');
	exit();
} else {
	$userId = $_GET['id'];
}

try {
	$userData = $users->getData($userId);
	$courseDb = new Courses($db->getDbConnection());
	$courses = $courseDb->getAll();
	$majors = $courseDb->getMajors();

	//$majors = array_unique(array_column($courses, 'Major'));
	//$majors_extensions = array_unique(array_column($courses, 'Extension'));
} catch (Exception $e) {
	$errors[] = $e->getMessage();
}

function is_create_bttn_Pressed() {
	return isset($_POST['hidden_submit_pressed']) && empty($_POST['hidden_submit_pressed']);
}


if (isSaveBttnPressed()) {
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$email = trim($_POST['email']);
	$user_type = trim($_POST['user_type']);
	$user_major_ext = trim($_POST['user_major']);
	$teaching_courses[] = isset($_POST['teaching_courses']) ? $_POST['teaching_courses'] : "";

	try {
		$users->register($first_name, $last_name, $email, $user_type, $user_major_ext, $teaching_courses);
	} catch (Exception $e) {
		$errors[] = $e->getMessage();
	}
}

function isSaveBttnPressed() {
	return isset($_POST['hidden_submit_pressed']) && empty($_POST['hidden_submit_pressed']);
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
	<h1>Settings</h1>
</div>
<!-- #content-header -->


<div id="content-container">

<div class="row">

<?php if (empty($errors) !== true): ?>
	<div class="alert alert-danger">
		<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
		<strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
	</div>
<?php endif; ?>
<div class="col-md-3 col-sm-4">

	<ul id="myTab" class="nav nav-pills nav-stacked">
		<li class="active">
			<a href="#profile-tab" data-toggle="tab" >
			<i class="fa fa-user"></i>
			&nbsp;&nbsp;Profile Settings
			</a >
		</li>
		<li class="">
			<a href="#notifications" data-toggle="tab" >
			<i class="fa fa-envelope"></i>
			&nbsp;&nbsp;Notifications Settings
			</a >
		</li>
		<li class="">
			<a href="#payments" data-toggle="tab" >
			<i class="fa fa-list-alt"></i>
			&nbsp;&nbsp; Courses - Majors
			</a >
		</li>
		<li class="">
			<a href="#reports" data-toggle="tab" >
			<i class="fa fa-flag-o"></i>
			&nbsp;&nbsp; Position
			</a >
		</li>
		<li class="">
			<a href="#reports" data-toggle="tab" >
			<i class="fa fa-warning"></i>
			&nbsp;&nbsp;Status
			</a >
		</li>
	</ul>

</div>
<!-- /.col-->

<div class="col-md-9 col-sm-8">

	<div class="tab-content stacked-content">
		<div class="tab-pane fade active in" id="profile-tab">

			<h3 class=""> Edit Profile Settings </h3>

			<p> Lorem ipsum dolor sit amet, consectetuer adipiscing elit . Aenean commodo ligula eget dolor . Aenean massa
				.
				Cum sociis natoque penatibus et magnis dis parturient montes . Lorem ipsum dolor sit amet, consectetuer
				adipiscing elit .</p>

			<hr>

			<br>

			<form action="./page-settings.html" class="form-horizontal">


				<div class="form-group">

					<label class="col-md-3"> Avatar</label>

					<div class="col-md-7">
						<div class="fileupload fileupload-new" data
						- provides = "fileupload" >
						<div class="fileupload-new thumbnail" style="width: 180px; height: 180px;"><img
								src="<?php echo BASE_URL . $userData["img_loc"]; ?>" alt="Profile Avatar"></div>
						<div class="fileupload-preview fileupload-exists thumbnail"
						     style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
						<div>
								<span class="btn btn-default btn-file" disabled><span
										class="fileupload-new"> Select image </span><span
										class="fileupload-exists"> Change</span><input type="file"></span>
							<a href="#" class="btn btn-default fileupload-exists" data - dismiss = "fileupload" > Remove</a >
						</div>
					</div>
				</div>
				<!-- /.col-->

		</div>
		<!-- /.form - group-->

		<div class="form-group">

			<label class="col-md-3"> First Name </label>

			<div class="col-md-7">
				<input type="text" name="first-name" value="<?php echo $userData['f_name']; ?>" class="form-control">
			</div>
			<!-- /.col-->

		</div>
		<!-- /.form - group-->

		<div class="form-group">

			<label class="col-md-3"> Last Name </label>

			<div class="col-md-7">
				<input type="text" name="last-name" value=""<?php echo $userData['l_name']; ?>"" class="form-control">
			</div>
			<!-- /.col-->

		</div>
		<!-- /.form - group-->

		<div class="form-group">

			<label class="col-md-3"> Email Address </label>

			<div class="col-md-7">
				<input type="text" name="email-address" value=""<?php echo $userData['email']; ?>"" class="form-control">
			</div>
			<!-- /.col-->

		</div>
		<!-- /.form - group-->

		<div class="form-group">

			<label class="col-md-3"> Website</label>

			<div class="col-md-7">
				<input type="text" name="website" value="" class="form-control">
			</div>
			<!-- /.col-->

		</div>
		<!-- /.form - group-->

		<div class="form-group">

			<label for="about-textarea" class="col-md-3"> About You </label>

			<div class="col-md-7">
				<textarea id="about-textarea" name="about-you" rows="6" class="form-control" disabled><<?php echo $userData['profile_description']; ?></textarea>

				<div class="charleft originalTextareaInfo" style="width: 658px;"> 193 characters | 31 words</div>
			</div>
			<!-- /.col-->

		</div>
		<!-- /.form - group-->

		<br>

		<div class="form-group">

			<div class="col-md-7 col-md-push-3">
				<button type="submit" class="btn btn-primary"> Save Changes</button>
				&nbsp;
				<button type="reset" class="btn btn-default"> Cancel</button>
			</div>
			<!-- /.col-->

		</div>
		<!-- /.form - group-->

		</form >


	</div>

	<div class="tab-pane fade" id="notifications">
		<h3> Notification Settings </h3>

		<p> Enable / Disable Email Notifications for</p>

		<p> Enable / Disable sms notifications .</p>
		<hr/>
		<ul>
			<li> New workshop session added to schedule</li>
			<li> You have a workshop session in 3 hours, with x student, for x courses</li>
			<li> Workshop session is canceled by students</li>
		</ul>

	</div>

	<div class="tab-pane fade" id="payments">
		<h3> Major</h3>

		<p> List of Majors created </p>

		<p> LIst of courses created .</p>
	</div>

	<div class="tab-pane fade" id="reports">
		<h3> Reports Settings </h3>

		<p> Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny
			pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard
			locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid
			8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro
			mlkshk vice blog. Scenester cred you probably haven't heard of them, vinyl craft beer blog stumptown .
			Pitchfork sustainable tofu synth chambray yr .</p>

		<p> Lorem ipsum dolor sit amet, consectetuer adipiscing elit . Aenean commodo ligula eget dolor . Aenean massa .
			Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus . Donec quam felis,
			ultricies nec, pellentesque eu, pretium quis, sem . Nulla consequat massa quis enim . Donec pede justo,
			fringilla vel, aliquet nec, vulputate eget, arcu . In enim justo, rhoncus ut, imperdiet a, venenatis vitae,
			justo . Nullam dictum felis eu pede mollis pretium .</p>
	</div>

</div>

</div>
<!-- /.col-->

</div>
<!-- /.row-->


</div>
<!-- /#content-container -->


</div>

<?php include ROOT_PATH . "app/views/footer.php"; ?>
</div>
<!-- /#wrapper -->


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
		$("#create-form").submit(function (event) {
			var error_last_name = validate($("#last_name"), /^[a-zA-Z]{1,16}$/);
			var error_first_name = validate($("#first_name"), /^[a-zA-Z]{1,16}$/);

//			if ($('input[name=user_type').val() === "tutor") {
//				alert("tutor");
//			}
			if (!error_last_name || !error_first_name) {
				//event.preventDefault();
			}
		});

		setTimeout(function () {
			$("#bttn-styledModal").trigger("click");
			//window.location.href = $href;
		}, 10);

		$("#user_major").select2();

		// TODO: add error messages
		// TODO: add option for second major
		// TODO: check email ^ user major & course teaching are inputt if user is tutor type.
		// TODO: hide major & courses & user type NOT tutor
		var validate = function (element, regex) {
			var str = $(element).val();
			var $parent = $(element).parent();

			if (regex.test(str)) {
				$parent.attr('class', 'form-group has-success');
				return true;
			} else {
				$parent.attr('class', 'form-group has-error');
				return false;
			}
		};

		$("#last_name").blur(function () {
			validate(this, /^[a-zA-Z]{1,16}$/);
		});

		$("#first_name").blur(function () {
			validate(this, /^[a-zA-Z]{1,16}$/);
		});


		$('input[name=user_type').on('ifChecked', function (event) {
			if ($(this).val() === "tutor") {
				$("#user_major").select2("enable", true);
				$("#teaching_courses_multi").select2("enable", true);
			} else {
				$("#user_major").select2("enable", false);
				$("#teaching_courses_multi").select2("enable", false);
			}
		});

		$('input[name=iCheck]').each(function () {
			var self = $(this),
				label = self.next(),
				label_text = label.text();

			label.remove();
			self.iCheck({
				checkboxClass: 'icheckbox_line-red',
				radioClass: 'iradio_line-red',
				insert: '<div class="icheck_line-icon"></div>' + label_text
			});
		});

	});


</script>

</body>
</html>
