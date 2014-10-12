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
 * @since 9/19/2014
 */
?>

<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();

$pageTitle = "Current Facilitated Courses";
$section = "staff";

$tutors = TutorFetcher::retrieveAll();
$currentTerms = TermFetcher::retrieveCurrTerm();
//$users = UserFetcher::
$courses = Tutor_has_course_has_termFetcher::retrieveCurrTermAllTeachingCourses();
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
			<h1><?php echo $pageTitle; ?></h1>
		</div>
		<!-- #content-header -->

		<div id="content-container">

			<div class="row">

				<div class="col-md-12">

					<div class="portlet">

						<div class="portlet-header">

							<h3>
								<i class="fa fa-table"></i>
								<?php
								$currentTermNames = "";

								if (sizeof($currentTerms) == 0) {
									echo "No term currently in duration.";
								} else {
									foreach ($currentTerms as $currentTerm) {
										$currentTermNames .= $currentTerm[TermFetcher::DB_COLUMN_NAME] . " - ";
									}
									$currentTermNames = rtrim($currentTermNames, " - ");
									echo $currentTermNames;
								}
								?>
							</h3>

						</div>
						<!-- /.portlet-header -->

						<div class="portlet-content">
							<div class="table-responsive">
								<table
									class="table table-striped table-bordered table-hover table-highlight table-checkable"
									data-provide="datatable"
									data-info="true"
									data-search="true"
									data-length-change="true"
									data-paginate="false"
									id="usersTable"
									>
									<thead>
									<tr>
										<th class="text-center" data-filterable="true" data-sortable="true"
										    data-direction="asc">First Name
										</th>
										<th class="text-center" data-direction="asc" data-filterable="true"
										    data-sortable="true">Last Name
										</th>
										<th class="text-center" data-filterable="true" data-sortable="true"
										    data-sortable="true">Course Code
										</th>
										<th class="text-center" data-filterable="true" data-sortable="false"
										    data-sortable="true">Course Name
										</th>
										<th class="text-center" data-filterable="true" data-sortable="true"
										    data-sortable="true">Term
										</th>
									</tr>
									</thead>
									<tbody>

									<?php
									if (empty($errors) === true) {
										foreach (array_reverse($courses) as $course) {
											include(ROOT_PATH . "views/partials/course/table-data-all-current-view.html.php");
										}
									}
									?>
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
		<!-- #content-container -->
	</div>
	<!-- #content -->

	<?php include ROOT_PATH . "views/footer.php"; ?>
</div>
<!-- #wrapper -->

<?php include ROOT_PATH . "views/assets/footer_common.php"; ?>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/select2/select2.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/datatables/DT_bootstrap.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/timepicker/bootstrap-timepicker.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/simplecolorpicker/jquery.simplecolorpicker.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/autosize/jquery.autosize.min.js"></script>

</body>
</html>