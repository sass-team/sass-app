<?php
require '../app/init.php';
$general->loggedOutProtect();

$page_title = "Manage Students";
$section = "academia";

try {
    $students = StudentFetcher::retrieve($db);
    $majors = CourseFetcher::retrieveMajors($db);
    $courses = CourseFetcher::retrieveAll($db);
    $instructors = InstructorFetcher::retrieve($db);


    if (isBtnAddStudentPrsd()) {
        Student::create($db, $_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['courseId'],
            $_POST['mobileNum'], $_POST['userMajorId'], $_POST['ciInput'], $_POST['creditsInput']);
        header('Location: ' . BASE_URL . "academia/students/success");
        exit();
    }


} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

function isEditBttnPressed() {
    return isset($_GET['id']) && preg_match('/^[0-9]+$/', $_GET['id']);
}

function isBtnAddStudentPrsd() {
    return isset($_POST['hiddenSubmitPressed']) && empty($_POST['hiddenSubmitPressed']);
}

function isStudentAddedSuccessful() {
    return isset($_GET['success']) && strcmp($_GET['success'], 'y1!q' === 0);
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
                <strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>';
                ?>
            </div>
        <?php
        } else if (isStudentAddedSuccessful()) {
            ?>
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                <strong>Student successfully created!</strong> <br/>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-12">
                <a data-toggle="modal" id="bttn-styledModal" href="#addStudentModal"
                   class="btn btn-primary navbar-right">
                    Add Student</a>
            </div>
        </div>
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

                                <th data-filterable="false" class="hidden-xs hidden-sm">Courses</th>

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

<div id="addStudentModal" class="modal modal-styled fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="add-student-form" action="" class="form">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title">Add Student Form</h3>
                </div>
                <div class="modal-body">
                    <div class="portlet">

                        <?php
                        if (empty($errors) === false) {
                            ?>
                            <div class="alert alert-danger">
                                <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                <strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>';
                                ?>
                            </div>
                        <?php
                        } else if (isBtnAddStudentPrsd()) {
                            ?>
                            <div class="alert alert-success">
                                <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                <strong>Student successfully created!</strong> <br/>
                            </div>
                        <?php } ?>

                        <div class="portlet-content">

                            <div class="row">


                                <div class="col-sm-12">

                                    <div class="form-group">
                                        <h5>
                                            <i class="fa fa-edit"></i>
                                            <label for="firstName">First Name</label>
                                        </h5>
                                        <input type="text" id="firstName" name="firstName" class="form-control"
                                               value="<?php if (isset($_POST['firstName'])) echo htmlentities($_POST['firstName']); ?>"
                                               autofocus="on" placeholder="Required" required>
                                    </div>

                                    <div class="form-group">
                                        <h5>
                                            <i class="fa fa-edit"></i>
                                            <label for="lastName">Last Name</label>
                                        </h5>
                                        <input type="text" id="lastName" name="lastName" class="form-control"
                                               value="<?php if (isset($_POST['lastName'])) echo htmlentities($_POST['lastName']); ?>"
                                               required placeholder="Required">
                                    </div>

                                    <div class="form-group">

                                        <i class="fa fa-envelope"></i>
                                        <label for="email">Email</label>
                                        <input type="email" required id="email" name="email" class="form-control"
                                               value="<?php if (isset($_POST['email'])) echo htmlentities($_POST['email']); ?>"
                                               placeholder="Required">
                                    </div>


                                    <div class="form-group">

                                        <h5>
                                            <i class="fa fa-tasks"></i>
                                            <label for="courseId">Course</label>
                                        </h5>

                                        <select id="courseId" name="courseId" class="form-control">

                                            <option value="null">Required</option>
                                            <?php foreach ($courses as $course) {
                                                include(ROOT_PATH . "app/views/partials/course-select-options-view.html.php");
                                            }
                                            ?>

                                        </select>
                                        <span class="input-group-addon">Taught By Instructor:</span>

                                        <select id="instructorId" name="instructorId"
                                                class="form-control">

                                            <option
                                                value="null">I don&#39;t know.
                                            </option>
                                            <?php foreach ($instructors as $instructor) {
                                                include(ROOT_PATH . "app/views/partials/instructor-select-options-view.html.php");
                                            }
                                            ?>

                                        </select>


                                    </div>


                                    <div class="form-group">
                                        <h5>
                                            <i class="fa fa-tasks"></i>
                                            <label for="mobileNum">Mobile Number</label>
                                            <input class="form-control" id="mobileNum" name="mobileNum" type="text"
                                                   placeholder="123457890">

                                        </h5>
                                    </div>


                                    <div class="form-group">
                                        <h5>
                                            <i class="fa fa-tasks"></i>
                                            <label for="userMajorId">Major</label>
                                        </h5>
                                        <select id="userMajorId" name="userMajorId" class="form-control">
                                            <option value="">I don&#39;t know.</option>
                                            <?php foreach ($majors as $major) {
                                                include(ROOT_PATH . "app/views/partials/major-select-options-view.html.php");
                                            }
                                            ?>
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-sm-6">

                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">CI</span>
                                                    <input class="form-control" id="ciInput" name="ciInput" type="text"
                                                           placeholder="3.5">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">

                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input class="form-control" id="creditsInput" name="creditsInput"
                                                           type="text"
                                                           placeholder="100">
                                                    <span class="input-group-addon">Credits</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-tertiary" data-dismiss="modal">Close</button>
                    <input type="hidden" name="hiddenSubmitPressed" value="">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


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


        $("#userMajorId").select2();

        $("#courseId").select2({
            placeholder: "Required",
            allowClear: false
        });

        $("#instructorId").select2({
            placeholder: "Required",
            allowClear: false
        });

    });


</script>

</body>
</html>

