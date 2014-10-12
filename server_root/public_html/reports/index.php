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
 * @author George Skarlatos
 * @since 8/16/14.
 */

?>

<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();

$pageTitle = "All Reports";
$section = "reports";

try {
	$users = User::retrieveAll();
	$courses = CourseFetcher::retrieveAll();
	$reports = ReportFetcher::retrieveAll();
} catch (Exception $e) {
	$errors[] = $e->getMessage();
}

function get($objects, $findId, $column) {
	foreach ($objects as $object) {
		if ($object[$column] === $findId) return $object;
	}

	return false;
}

function isEditBttnPressed() {
	return isset($_GET['id']) && preg_match('/^[0-9]+$/', $_GET['id']);
}

function isModifyBttnPressed() {
	return isset($_POST['hidden_submit_pressed']) && empty($_POST['hidden_submit_pressed']);
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
			<h1>All Users</h1>
		</div>
		<!-- #content-header -->


		<div id="content-container">
			<?php
			if (empty($errors) === false) {
				?>
				<div class="alert alert-danger">
					<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
					<strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
				</div>
			<?php
			} ?>
			<div class="row">

				<div class="col-md-12">

					<div class="portlet">

						<div class="portlet-header">

							<h3>
								<i class="fa fa-table"></i>
								Users
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
									data-paginate="false"
									id="usersTable"
									>
									<thead>
									<tr>
										<th class="text-center" data-filterable="true" data-sortable="true" data-direction="desc">
											Name
										</th>
										<th class="text-center" data-direction="asc" data-filterable="true" data-sortable="false">
											Email
										</th>
										<th class="text-center" data-filterable="true" data-sortable="true">Position</th>
										<th class="text-center" data-filterable="true" data-sortable="false">Mobile</th>
										<th class="text-center" data-filterable="false" class="hidden-xs hidden-sm">Profile</th>

										<?php if (!$user->isTutor()): ?>
											<th class="text-center" data-filterable="false" class="hidden-xs hidden-sm">Teaching
											</th>
										<?php endif; ?>

										<?php if (!$user->isTutor()): ?>
											<th class="text-center" data-filterable="false" class="hidden-xs hidden-sm">Schedule
											</th>
										<?php endif; ?>


										<?php if ($user->isAdmin()): ?>
											<th class="text-center" data-filterable="false" class="hidden-xs hidden-sm">Data</th>
										<?php endif; ?>
									</tr>
									</thead>
									<tbody>

									<?php
									if (empty($errors) === true) {
										foreach (array_reverse($users) as $curUser) {
											include(ROOT_PATH . "views/partials/user/table-data-view.html.php");
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

				</div>
				<!-- /.col -->

			</div>
			<!-- /.row -->


		</div>
		<!-- /#content-container -->

	</div>
	<!-- /#content -->

	<?php include ROOT_PATH . "views/footer.php"; ?>

</div>
<!-- #wrapper -->

<?php include ROOT_PATH . "views/assets/footer_common.php"; ?>


<script src="<?php echo BASE_URL; ?>assets/js/plugins/select2/select2.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/icheck/jquery.icheck.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/timepicker/bootstrap-timepicker.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/simplecolorpicker/jquery.simplecolorpicker.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/demos/form-extended.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/datatables/DT_bootstrap.js"></script>


<script type="text/javascript">
	jQuery(function () {
		$(".edit-user").click(function () {
			$id = $(this).next('input').val();


		});

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
//			window.location = document.getElementById('#styledModal').href;
//			$("#styledModal").click();
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
				$("#teachingCourse").select2("enable", true);
			} else {
				$("#user_major").select2("enable", false);
				$("#teachingCourse").select2("enable", false);
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

