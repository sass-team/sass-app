<?php
require __DIR__ . '/app/init.php';
$general->loggedOutProtect();
// redirect if user elevation is not that of admin
if (!$user->isAdmin()) {
	header('Location: ' . BASE_URL . "error-403");
	exit();
}

$pageTitle = "Search";
$section = "search";
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

                    <div class="col-md-12 col-sm-12">

                        <div class="portlet">

                            <div class="portlet-header">

                                <h3>
                                    <i class="fa fa-search"></i>
                                    <?php echo 'Search Criterias' ?>
                                </h3>

                            </div>
                            <!-- /.portlet-header -->

                            <div class="portlet-content">
                                
                            <h4>Range of Appointments or term(s)</h4>

                            <div class="row">
                            	<div class="col-sm-3">
                            		<input class="form-control" type="text" placeholder="Start date" id="dpStart" data-date-format="mm-dd-yyyy" data-date-autoclose="true">
                            	</div>

                            	<div class="col-sm-3">
                            		<input class="form-control" type="text" placeholder="End date" id="dpEnd" data-date-format="mm-dd-yyyy" data-date-autoclose="true">
                            	</div>

                            	<!-- </div> -->

                            	<div class="col-sm-6">

                            		<select id="s2_multi_value" class="form-control" placeholder="Select term(s)"multiple>
                            			<optgroup label="Alaskan/Hawaiian Time Zone">
                            				<option value="AK">Alaska</option>
                            				<option value="HI">Hawaii</option>
                            			</optgroup>
                            			<optgroup label="Pacific Time Zone">
                            				<option value="CA">California</option>
                            				<option value="NV">Nevada</option>
                            			</optgroup>
                            			<optgroup label="Mountain Time Zone">
                            				<option value="AZ">Arizona</option>
                            				<option value="CO">Colorado</option>
                            			</optgroup>
                            			<optgroup label="Central Time Zone">
                            				<option value="AL">Alabama</option>
                            				<option value="AR">Arkansas</option>
                            			</optgroup>
                            			<optgroup label="Eastern Time Zone">
                            				<option value="CT">Connecticut</option>
                            				<option value="DE">Delaware</option>
                            				<option value="FL">Florida</option>
                            			</optgroup>
                            		</select>
                            	</div>

                        </div>
                    <!-- row -->
                    <hr>
                    <div class="row">

                    	<div class="col-sm-6">
                    		<div class="form-group">	
								<label for="select-input">Appointment Status</label>
								<select id="select-input" class="form-control">
									<option>Complete</option>
									<option>Canceled by tutor</option>
									<option>Canceled by student</option>
									<option>No show by student</option>
									<option>No show by tutor</option>
									<option>Disabled by admin</option>
								</select>
							</div>
                    	</div>
                    	<div class="col-sm-6">
                    		<div class="form-group">	
								<label for="select-input">Report Status</label>
								<select id="select-input" class="form-control">
									<option>Complete</option>
									<option>Pending fill</option>
									<option>Pending validation</option>
								</select>
							</div>
                    	</div>

                    </div>
                    <!-- row -->
                    <hr>
                    <div class="row">

                    	<div class="col-sm-3 ">

                        <label for="s2_basic">Select a L.F.</label>
                            <select id="s2_basic" class="form-control">
                                
								<optgroup label="Mountain Time Zone">
									<option value="AZ">Arizona</option>
									<option value="CO">Colorado</option>
									<option value="ID">Idaho</option>
									<option value="MT">Montana</option>
									<option value="NE">Nebraska</option>
									<option value="NM">New Mexico</option>
									<option value="ND">North Dakota</option>
									<option value="UT">Utah</option>
									<option value="WY">Wyoming</option>
								</optgroup>
                            </select>

                        </div>
                        <div class="col-sm-3 ">

                            <label for="s2_basic">Select a Student</label>
                            <select id="s2_basic" class="form-control">
                                
								<optgroup label="Mountain Time Zone">
									<option value="AZ">Arizona</option>
									<option value="CO">Colorado</option>
									<option value="ID">Idaho</option>
									<option value="MT">Montana</option>
									<option value="NE">Nebraska</option>
									<option value="NM">New Mexico</option>
									<option value="ND">North Dakota</option>
									<option value="UT">Utah</option>
									<option value="WY">Wyoming</option>
								</optgroup>
                            </select>

                        </div>
                        <div class="col-sm-3 ">

                            <label for="s2_basic">Select a Course</label>
                            <select id="s2_basic" class="form-control">
                                
								<optgroup label="Mountain Time Zone">
									<option value="AZ">Arizona</option>
									<option value="CO">Colorado</option>
									<option value="ID">Idaho</option>
									<option value="MT">Montana</option>
									<option value="NE">Nebraska</option>
									<option value="NM">New Mexico</option>
									<option value="ND">North Dakota</option>
									<option value="UT">Utah</option>
									<option value="WY">Wyoming</option>
								</optgroup>
                            </select>

                        </div>
                        <div class="col-sm-3 ">

                            <label for="s2_basic">Select an Instructor</label>
                            <select id="s2_basic" class="form-control">
                                
								<optgroup label="Mountain Time Zone">
									<option value="AZ">Arizona</option>
									<option value="CO">Colorado</option>
									<option value="ID">Idaho</option>
									<option value="MT">Montana</option>
									<option value="NE">Nebraska</option>
									<option value="NM">New Mexico</option>
									<option value="ND">North Dakota</option>
									<option value="UT">Utah</option>
									<option value="WY">Wyoming</option>
								</optgroup>
                            </select>

                        </div>

                    </div>
                    <!-- row -->






                            </div>
                            <!-- portlet-content -->

                        </div>
                        <!-- /.portlet -->

                    </div>
                    <!-- /.col -->

                </div>
                <!-- /.row -->


            </div>
            <!-- content-container -->

    </div>
    <!-- content -->

<?php include ROOT_PATH . "views/footer.php"; ?>

</div>
<!-- #wrapper -->

<?php include ROOT_PATH . "views/assets/footer_common.php"; ?>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/select2/select2.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/icheck/jquery.icheck.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/datatables/DT_bootstrap.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/timepicker/bootstrap-timepicker.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/simplecolorpicker/jquery.simplecolorpicker.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/demos/form-extended.js"></script>

</body>
</html>