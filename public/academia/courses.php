<?php
require '../app/init.php';
$general->loggedOutProtect();

// redirect if user elevation is not that of secretary or tutor
if (!$user->isAdmin()) {
    header('Location: ' . BASE_URL . "error-403");
    exit();
}

try {
    $courses = CourseFetcher::retrieveAll($db);
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

function is_create_bttn_Pressed() {
    return isset($_POST['hidden_submit_pressed']) && empty($_POST['hidden_submit_pressed']);
}

if (isSaveBttnPressed()) {
    $course_code = trim($_POST['course_code']);
    $course_name = trim($_POST['course_name']);

    try {
        $user->createCourse($course_code, $course_name);
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}


function isSaveBttnPressed() {
    return isset($_POST['hidden_submit_pressed']) && empty($_POST['hidden_submit_pressed']);
}

$page_title = "Manage Courses";
$section = "academia";
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
        <h1>All Courses</h1>
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

            <div class="col-md-6">

                <div class="portlet">

                    <div class="portlet-header">

                        <h3>
                            <i class="fa fa-table"></i>
                            View and Manage Courses
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
                                    <th data-filterable="true" data-sortable="true" data-direction="desc">Code</th>
                                    <th data-direction="asc" data-filterable="true" data-sortable="false">Name</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                if (empty($errors) === true) {
                                    foreach ($courses as $course) {
                                        include(ROOT_PATH . "app/views/partials/course-table-data-view.html.php");

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
            <div class="col-md-3 col-sidebar-right">

                <h1>Add a new Course</h1>

                <p class="lead"> You can also add a new course that is not already in the list.</p>
                <a data-toggle="modal" href="#addCourseModal" class="btn btn-danger btn-jumbo btn-block">Add Course</a>

            </div>
        </div>
        <!-- /.row -->


    </div>
    <!-- /#content-container -->

</div>
<!-- /.col -->


<div id="addCourseModal" class="modal modal-styled fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="create-form" action="" class="form">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title">Teaching Courses Form</h3>
                </div>
                <div class="modal-body">
                    <div class="portlet">
                        <?php
                        if (empty($errors) === false) {
                            ?>
                            <div class="alert alert-danger">
                                <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                <strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
                            </div>
                        <?php } ?>

                        <div class="portlet-content">

                            <div class="row">


                                <div class="col-sm-12">

                                    <div class="form-group">

                                        <h5>
                                            <i class="fa fa-tasks"></i>
                                            <label for="teachingCoursesMulti">Courses</label>
                                        </h5>
                                    </div>


                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-tertiary" data-dismiss="modal">Close</button>
                    <input type="hidden" name="hiddenSubmitAddTeachingCourse">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div id="deleteCourse" class="modal modal-styled fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="delete-form" action="" class="form">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title">Remove course
                        <!--                        from --><?php //echo $curUser->getFirstName() . " " . $curUser->getLastName(); ?>
                    </h3>
                </div>
                <div class="modal-body">
                    <div class="portlet">
                        <?php
                        if (empty($errors) === false) {
                            ?>
                            <div class="alert alert-danger">
                                <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                <strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
                            </div>
                        <?php } ?>

                        <div class="portlet-content">

                            <div class="row">
                                <div class="alert alert-warning">
                                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
                                    <strong>Warning!</strong><br/>Tutor will not be able to teach this course anymore.
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-tertiary" data-dismiss="modal">Cancel</button>
                    <input type="hidden" id="delCourseIdModal" name="delCourseIdModal" value=""/>
                    <input type="hidden" name="hiddenSubmitDeleteCourse">
                    <button type="submit" class="btn btn-primary">Delete</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div id="updateCourse" class="modal modal-styled fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="delete-form" action="" class="form">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title">Update Course
                        <!--                        for --><?php //echo $curUser->getFirstName() . " " . $curUser->getLastName(); ?>
                    </h3>
                </div>
                <div class="modal-body">
                    <div class="portlet">

                        <div class="portlet-content">

                            <div class="col-sm-12">

                                <h4 class="heading_a">New Course</h4>

                                <hr>
                                <?php
                                if (empty($errors) === false) {
                                    ?>
                                    <div class="alert alert-danger">
                                        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                        <strong>Oh
                                            snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
                                    </div>
                                <?php } ?>

                                <div class="alert alert-info">
                                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
                                    <strong>Careful!</strong><br/>The previous course will be replaced, and all it's
                                    upcoming
                                    workshop sessions data will be deleted.
                                </div>
                            </div>


                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-tertiary" data-dismiss="modal">Cancel</button>
                    <input type="hidden" name="hiddenSubmitReplaceCourse">
                    <input type="hidden" name="hiddenUpdateCourseOldId" id="hiddenUpdateCourseOldId">
                    <button type="submit" class="btn btn-primary">Replace</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<?php include ROOT_PATH . "app/views/footer.php"; ?>
</div>
<!-- #wrapper -->

<?php include ROOT_PATH . "app/views/assets/footer_common.php"; ?>




<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/select2/select2.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/icheck/jquery.icheck.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/timepicker/bootstrap-timepicker.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/simplecolorpicker/jquery.simplecolorpicker.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/demos/form-extended.js"></script>
</body>
</html>