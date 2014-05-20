<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<?php
require "../inc/config.php";

$pageTitle = "My Account - Profile";

require ROOT_PATH . 'inc/view/header.php';

//first checks if a session exists
if(isset($_SESSION['email'])){
//and then calls init.php
require ROOT_PATH . '/inc/init.php';
require ROOT_PATH . 'inc/view/sidebar.php';

?>


	
	<div id="content">		
		
		<div id="content-header">
			<h1>Settings</h1>
		</div> <!-- #content-header -->	


		<div id="content-container">

			<div class="row">

		    	<div class="col-md-3 col-sm-4">
		    
				    <ul id="myTab" class="nav nav-pills nav-stacked">
				        <li class="active">
				        	<a href="#profile-tab" data-toggle="tab">
				        		<i class="fa fa-user"></i> 
				        		&nbsp;&nbsp;Profile Settings
				        	</a>
				        </li>
				        <li>
				        	<a href="#password-tab" data-toggle="tab">
				        		<i class="fa fa-lock"></i> 
				        		&nbsp;&nbsp;Change Password
				        	</a>
				        </li>   
				    </ul>

				</div> <!-- /.col -->

				<div class="col-md-9 col-sm-8">

				      <div class="tab-content stacked-content">
				        <div class="tab-pane fade in active" id="profile-tab">
				          
				          <h3 class="">Edit Profile Settings</h3>

				          <hr />

				          <br />

				          <form action="./page-settings.html" class="form-horizontal">

				          	

				          	<div class="form-group">

				          		<label class="col-md-3">Avatar</label>

				          		<div class="col-md-7">
				          			<div class="fileupload fileupload-new" data-provides="fileupload">
									  <div class="fileupload-new thumbnail" style="width: 180px; height: 180px;"><img src="<?php echo BASE_URL . $img_loc ?>" alt="Profile Avatar" /></div>
									  <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
									  <div>
									    <span class="btn btn-default btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" /></span>
									    <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
									  </div>
									</div>
				          		</div> <!-- /.col -->

				          	</div> <!-- /.form-group -->

				          	<div class="form-group">

				          		<label class="col-md-3">Email</label>

				          		<div class="col-md-7">
				          			<input type="text" name="user-name" value="<?php echo $_SESSION['email']; ?>" class="form-control" disabled />
				          		</div> <!-- /.col -->

				          	</div> <!-- /.form-group -->

				          	<div class="form-group">

				          		<label class="col-md-3">First Name</label>

				          		<div class="col-md-7">
				          			<input type="text" name="first-name" value="<?php echo $first_name; ?>" class="form-control" />
				          		</div> <!-- /.col -->

				          	</div> <!-- /.form-group -->

				          	<div class="form-group">

				          		<label class="col-md-3">Last Name</label>

				          		<div class="col-md-7">
				          			<input type="text" name="last-name" value="<?php echo $last_name; ?>" class="form-control" />
				          		</div> <!-- /.col -->

				          	</div> <!-- /.form-group -->

				          	<div class="form-group">

				          		<label class="col-md-3">Mobile</label>

				          		<div class="col-md-7">
				          			<input type="text" name="mobile" value="<?php echo $mobile_num; ?>" class="form-control" />
				          		</div> <!-- /.col -->

				          	</div> <!-- /.form-group -->

				          	<div class="form-group">

				          		<label class="col-md-3">Short Description</label>

				          		<div class="col-md-7">
				          			<textarea id="about-textarea" name="about-you" rows="6" class="form-control"><?php echo $profile_description ?></textarea>
				          		</div> <!-- /.col -->

				          	</div> <!-- /.form-group -->

				          	<br />

				          	<div class="form-group">

				          		<div class="col-md-7 col-md-push-3">
				          			<button type="submit" class="btn btn-primary">Save Changes</button>
				          			&nbsp;
				          			<button type="reset" class="btn btn-default">Cancel</button>
				          		</div> <!-- /.col -->

				          	</div> <!-- /.form-group -->

				          </form>


				        </div>
				        <div class="tab-pane fade" id="password-tab">

				          <h3 class="">Change Your Password</h3>

				          <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.</p>

				          <br />

				          <form action="./page-settings.html" class="form-horizontal">

				          	<div class="form-group">

				          		<label class="col-md-3">Old Password</label>

				          		<div class="col-md-7">
				          			<input type="password" name="old-password" class="form-control" />
				          		</div> <!-- /.col -->

				          	</div> <!-- /.form-group -->


				          	<hr />


				          	<div class="form-group">

				          		<label class="col-md-3">New Password</label>

				          		<div class="col-md-7">
				          			<input type="password" name="new-password-1" class="form-control" />
				          		</div> <!-- /.col -->

				          	</div> <!-- /.form-group -->

				          	<div class="form-group">

				          		<label class="col-md-3">New Password Confirm</label>

				          		<div class="col-md-7">
				          			<input type="password" name="new-password-2" class="form-control" />
				          		</div> <!-- /.col -->

				          	</div> <!-- /.form-group -->

				          	<br />

				          	<div class="form-group">

				          		<div class="col-md-7 col-md-push-3">
				          			<button type="submit" class="btn btn-primary">Save Changes</button>
				          			&nbsp;
				          			<button type="reset" class="btn btn-default">Cancel</button>
				          		</div> <!-- /.col -->

				          	</div> <!-- /.form-group -->

				          </form>
				        </div>
				      </div>

				</div> <!-- /.col -->

			</div> <!-- /.row -->


		</div> <!-- /#content-container -->
		

	</div> <!-- #content -->
	
	
</div> <!-- #wrapper -->




<?php include ROOT_PATH . "inc/view/footer.php"; ?>
<?php } 
else {
	echo '<center><p style="color:red;"> You are not authorized to view this page.</p></center>';
} ?>