<?php
require '../inc/init.php';
$general->logged_out_protect();

$page_title = "Manage Users";
$section = "users";
require ROOT_PATH . 'inc/view/header.php';
require ROOT_PATH . 'inc/view/sidebar.php';


if (isSaveBttnPressed()) {
	$firstName = trim($_POST['firstName']);
	$lastName = trim($_POST['lastName']);
	$email = trim($_POST['email']);
	$userMajor = trim($_POST['userMajor']);
	$teachingCourses = $_POST['teachingCourses'];
	$userType = trim($_POST['userType']);
	var_dump($_POST);
}

function isSaveBttnPressed() {
	return isset($_POST['hidden_submit_pressed']) && empty($_POST['hidden_submit_pressed']);
}

?>

	<div id="content">

		<div id="content-header">
			<h1>Manage Users</h1>
		</div>
		<!-- #content-header -->


		<div id="content-container">

			<h3 class="heading">Manage Users</h3>


			<div class="row">

				<div class="col-md-3 col-sm-5">

					<ul id="myTab" class="nav nav-pills nav-stacked">
						<li class="active"><a href="#add" data-toggle="tab"><i class="fa fa-plus"></i> &nbsp;&nbsp;Add
								User</a></li>
						<li><a href="#profile-3" data-toggle="tab"><i class="fa fa-user"></i> &nbsp;&nbsp;Profile</a></li>
						<li class="dropdown">
							<a href="javascript:;" id="myTabDrop3" class="dropdown-toggle" data-toggle="dropdown"><i
									class="fa fa-chevron-sign-down"></i> &nbsp;&nbsp;Dropdown <b class="caret"></b></a>
							<ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
								<li><a href="#dropdown5" tabindex="-1" data-toggle="tab">@fat</a></li>
								<li><a href="#dropdown6" tabindex="-1" data-toggle="tab">@mdo</a></li>
							</ul>
						</li>
					</ul>

				</div>
				<!-- /.col -->

				<div class="col-md-9 col-sm-7">

					<div id="myTabContent" class="tab-content stacked-content">
						<div class="tab-pane fade in active" id="add">
							<p>In this section admin is able to add a new user and fill out the appropriate fields. Only
								necessary fields are required in order to create a new user. Users(tutors or secretaries) are
								able to modify their profile once they are logged in.</p>

							<p>
								<a data-toggle="modal" href="#styledModal" class="btn btn-primary">Create a new user</a>
							</p>
						</div>

						<div class="tab-pane fade" id="profile-3">
							<p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid.
								Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four
								loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk
								aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore
								aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente
								labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard
								ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher
								vero sint qui sapiente accusamus tattooed echo park.</p>

							<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
								Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus
								mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa
								quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo,
								rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>
						</div>

						<div class="tab-pane fade" id="dropdown5">
							<p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo
								retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft
								beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR
								banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever
								gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you
								probably haven't heard of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu
								synth chambray yr.</p>

							<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
								Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus
								mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa
								quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo,
								rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>
						</div>

						<div class="tab-pane fade" id="dropdown6">
							<p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out
								master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan
								DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia
								PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater. Lomo wolf
								viral, mustache readymade thundercats keffiyeh craft beer marfa ethical. Wolf salvia freegan,
								sartorial keffiyeh echo park vegan.</p>

							<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
								Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus
								mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa
								quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo,
								rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>
						</div>
					</div>

				</div>
				<!-- /.col -->

			</div>
			<!-- /.row -->


		</div>
		<!-- /#content-container -->

	</div> <!-- #content -->


	<div id="styledModal" class="modal modal-styled fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form method="post" id="login-form" action="manage.php" class="form">

					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 class="modal-title">Create User Form</h3>
					</div>
					<div class="modal-body">
						<div class="portlet">

							<div class="portlet-content">

								<div class="row">


									<div class="col-sm-12">

										<div class="form-group">
											<label for="text-input">First Name</label>
											<input type="text" id="text-input" name="firstName" class="form-control">
										</div>

										<div class="form-group">
											<label for="text-input">Last Name</label>
											<input type="text" id="text-input" name="lastName" class="form-control">
										</div>

										<div class="form-group">
											<label for="text-input">Email</label>
											<input type="email" id="text-input" name="email" class="form-control">
										</div>

										<div class="form-group">
											<label for="text-input">User Major</label>
											<select id="s2_basic" name="userMajor" class="form-control">

												<option value="AK">IT</option>
												<option value="HI">Accounting and Finance</option>
												<option value="HI">Marketing</option>
												<option value="HI">Management</option>
												<option value="HI">CIS</option>

											</select>
										</div>

										<div class="form-group">
											<label for="text-input">Teaching Courses</label>
											<select id="s2_multi_value" name="teachingCourses[]" class="form-control" multiple>

												<option value="CS2188">CS 2188 Introduction to Programming</option>
												<option value="MA1001">MA 1001 Finite Mathematics</option>
												<option value="MA1105">MA 1105 Applied Calculus</option>
												<option value="AF2006">AF 2006 Financial Accounting </option>
												<option value="MG2003">MG 2003 Management Principles</option>

											</select>
										</div>

										<label for="text-input">Type of user</label>

										<div class="form-group">
											<div class="radio">
												<label>
													<input type="radio" name="userType" value="tutor" class="" checked>
													Tutor
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="userType" value="secretary" class="" data-required="true">
													Secretary
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="userType" value="admin" class="" data-required="true">
													Admin
												</label>
											</div>
										</div>
										<!-- /.form-group -->

									</div>
								</div>

							</div>

						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-tertiary" data-dismiss="modal">Close</button>
						<input type="hidden" name="hidden_submit_pressed">
						<button type="submit" class="btn btn-primary">Save changes</button>
					</div>
				</form>

			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div><!-- /.modal -->
<?php include ROOT_PATH . "inc/view/footer.php"; ?>