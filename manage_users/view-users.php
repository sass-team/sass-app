<?php
require '../inc/init.php';
$general->logged_out_protect();

$page_title = "My Account - Profile";
$section = "manage_users";
require ROOT_PATH . 'inc/view/header.php';
require ROOT_PATH . 'inc/view/sidebar.php';
?>

<div id="content">		
		
		<div id="content-header">
			<h1>View Current Users</h1>
		</div> <!-- #content-header -->	


		<div id="content-container">

<div class="row">

				<div class="col-md-12">

					<div class="portlet">

						<div class="portlet-header">

							<h3>
								<i class="fa fa-filter"></i>
								Filter with Columns
							</h3>

						</div> <!-- /.portlet-header -->

						<div class="portlet-content">						

							<div class="table-responsive">

							<table 
								class="table table-striped table-bordered table-hover" 
								data-provide="datatable" 
								data-info="true"
							>
									<thead>
										<tr>
											<th data-filterable="true" class="hidden-xs hidden-sm">First Name</th>
											<th data-direction="asc" data-filterable="true">Last Name</th>
											<th data-filterable="true">Email</th>
											<th data-filterable="true">Position</th>
											<th data-filterable="false" class="hidden-xs hidden-sm">Mobile</th>
											<th data-filterable="true" class="hidden-xs hidden-sm">Major</th>
											<th data-filterable="false" class="hidden-xs hidden-sm">Account Created</th>

										</tr>
									</thead>
									<tbody>
										<tr >
											<td class="hidden-xs hidden-sm">Trident</td>
											<td>Internet Explorer 7</td>
											<td>Win XP SP2+</td>
											<td>test</td>
											<td class="hidden-xs hidden-sm">7</td>
											<td class="hidden-xs hidden-sm">A</td>
											<td class="hidden-xs hidden-sm">A</td>
										</tr>
										<tr >
											<td class="hidden-xs hidden-sm">Trident</td>
											<td>AOL browser (AOL desktop)</td>
											<td>Win XP</td>
											<td>test1</td>
											<td class="hidden-xs hidden-sm">6</td>
											<td class="hidden-xs hidden-sm">A</td>
											<td class="hidden-xs hidden-sm">A</td>
										</tr>
										<tr >
											<td class="hidden-xs hidden-sm">Gecko</td>
											<td>Firefox 2.0</td>
											<td>Win 98+ / OSX.2+</td>
											<td>test2</td>
											<td class="hidden-xs hidden-sm">1.8</td>
											<td class="hidden-xs hidden-sm">A</td>
											<td class="hidden-xs hidden-sm">A</td>
										</tr>
										<tr >
											<td class="hidden-xs hidden-sm">Gecko</td>
											<td>Firefox 3.0</td>
											<td>Win 2k+ / OSX.3+</td>
											<td>test3</td>
											<td class="hidden-xs hidden-sm">1.9</td>
											<td class="hidden-xs hidden-sm">A</td>
											<td class="hidden-xs hidden-sm">A</td>
										</tr>
									</tbody>
								</table>
							</div> <!-- /.table-responsive -->
							

						</div> <!-- /.portlet-content -->

					</div> <!-- /.portlet -->

				

				</div> <!-- /.col -->

			</div> <!-- /.row -->
			

			


			
		</div> <!-- /#content-container -->			
		
	</div> <!-- #content -->
	
	
</div> <!-- #wrapper -->

<?php include ROOT_PATH . "inc/view/footer.php"; ?>