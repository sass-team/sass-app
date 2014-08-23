<?php
require '../app/init.php';
$general->loggedOutProtect();

$page_title = "View Users";
$section = "academia";

try {
	$students = Student::retrieve($db);
} catch (Exception $e) {
	$errors[] = $e->getMessage();
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
<?php require ROOT_PATH . 'app/views/head.php'; ?>
<body>
<div id="wrapper">
	<?php
	require ROOT_PATH . 'app/views/header.php';
	require ROOT_PATH . 'app/views/sidebar.php';
	?>


	<div id="content">

		<div id="content-header">
			<h1>All Students</h1>
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
									 id="usersTable"
									 >
									<thead>
									<tr>
										<th data-filterable="true" data-sortable="true" data-direction="desc">Name</th>
										<th data-direction="asc" data-filterable="true" data-sortable="false">Email</th>
										<th data-filterable="true" data-sortable="false">Mobile</th>
										<th data-filterable="true" data-sortable="true">CI</th>
										<th data-filterable="true" data-sortable="true">Credits</th>

										<th data-filterable="false" class="hidden-xs hidden-sm">Profile</th>

										<?php if (!$user->isTutor()): ?>
											<th data-filterable="false" class="hidden-xs hidden-sm">Schedule</th>
										<?php endif; ?>


										<?php if ($user->isAdmin()): ?>
											<th data-filterable="false" class="hidden-xs hidden-sm">Data</th>
										<?php endif; ?>
									</tr>
									</thead>
									<tbody>

									<?php
									if (empty($errors) === true) {
										foreach (array_reverse($students) as $student) {
											include(ROOT_PATH . "app/views/partials/student-table-data-view.html.php");
										}
									} ?>
									</tbody>
								</table>
							</div>
							<!-- /.table-responsive -->



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

</div>
<!-- #wrapper -->

<?php include ROOT_PATH . "app/views/footer.php"; ?>
<?php include ROOT_PATH . "app/views/assets/footer_common.php"; ?>

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datatables/DT_bootstrap.js"></script>

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

