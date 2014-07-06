<?php
require '../inc/init.php';
$general->logged_out_protect();

$page_title = "View Users";
$section = "manage_users";
$all_users = $users->getAllUsers();

require ROOT_PATH . 'inc/view/header.php';
require ROOT_PATH . 'inc/view/sidebar.php';
?>


	<div id="content">

		<div id="content-header">
			<h1>Tables Advanced</h1>
		</div>
		<!-- #content-header -->


		<div id="content-container">

			<div class="row">

				<div class="col-md-12">

					<div class="portlet">

						<div class="portlet-header">

							<h3>
								<i class="fa fa-table"></i>
								Kitchen Sink
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
										<th class="checkbox-column">
											<input type="checkbox" class="icheck-input">
										</th>
										<th data-filterable="true" data-sortable="true" data-direction="desc">Name</th>
										<th data-direction="asc" data-filterable="true" data-sortable="true">Email</th>
										<th data-filterable="true" data-sortable="true">Position</th>
										<th data-filterable="false" class="hidden-xs hidden-sm">Profile</th>
										<th data-filterable="false" class="hidden-xs hidden-sm">Schedule</th>
									</tr>
									</thead>
									<tbody>
									<?php
									foreach (array_reverse($all_users) as $currentUserData) {
										include(ROOT_PATH . "inc/view/partials/user-table-data-view.html.php");
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
		<!-- /#content-container -->


	</div>
	<!-- /.col -->

	</div> <!-- #wrapper -->



<?php include ROOT_PATH . "inc/view/footer.php"; ?>