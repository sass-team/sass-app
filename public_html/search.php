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