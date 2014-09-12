<?php
require '../app/init.php';
$general->loggedOutProtect();

// redirect if user elevation is not that of secretary or admin
if ($user->isTutor()) {
    header('Location: ' . BASE_URL . "error-403");
    exit();
}

function is_create_bttn_Pressed() {
    return isset($_POST['hidden_submit_pressed']) && empty($_POST['hidden_submit_pressed']);
}

try {
    $courses = CourseFetcher::retrieveAll($db);

    if (isBtnUpdatePrsd()) {
        $updateDone = false;
        $courseId = trim($_POST['updateCourseIdModal']);

        $newCourseCode = trim($_POST['courseCodeUpdate']);
        $newCourseName = trim($_POST['courseNameUpdate']);
        $updateDone = false;

        if (($course = getCourse($courseId, $courses)) !== false) {
            $oldCourseCodeName = $course[CourseFetcher::DB_COLUMN_CODE];
            $oldCourseName = $course[CourseFetcher::DB_COLUMN_NAME];

            if (strcmp($newCourseName, $oldCourseName) !== 0) {
                $updateDone = true;
                Course::updateName($db, $courseId, $newCourseName);
            }

            if (strcmp($newCourseCode, $oldCourseCodeName) !== 0) {
                $updateDone = true;
                Course::updateCode($db, $courseId, $newCourseCode);
            }

            if (!$updateDone) {
                throw new Exception("No new data inputted. Process aborted.");
            }

            //
            header('Location: ' . BASE_URL . 'academia/courses/success');

        } else {
            throw new Exception("Either you're trying to hack this app or something wrong went. In either case the
            developers we just notified about this");
        }

    } else if (isBtnSavePrsd()) {
        $newCourseCode = trim($_POST['course_code']);
        $newCourseName = trim($_POST['course_name']);


        Course::create($db, $newCourseCode, $newCourseName);
        header('Location: ' . BASE_URL . 'academia/courses/success');
        exit();
    } else if (isBtnDeletePrsd()) {
        Course::delete($db, $_POST['delCourseIdModal']);
        header('Location: ' . BASE_URL . 'academia/courses/success');
        exit();
    }

} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

/**
 * http://stackoverflow.com/a/4128377/2790481
 *
 * @param $needle
 * @param $courses
 * @param bool $strict
 * @return bool
 */
function getCourse($needle, $courses, $strict = false) {
    foreach ($courses as $course) {
        if (($strict ? $course === $needle : $course == $needle) ||
            (is_array($course) && getCourse($needle, $course, $strict))
        ) {
            return $course;
        }
    }

    return false;
}

function isBtnSavePrsd() {
    return isset($_POST['hidden_submit_pressed']) && empty($_POST['hidden_submit_pressed']);
}

function isModificationSuccessful() {
    return isset($_GET['success']) && strcmp($_GET['success'], 'y1!qp' === 0);
}

function isBtnDeletePrsd() {
    return isset($_POST['hiddenSubmitDeleteCourse']) && empty($_POST['hiddenSubmitDeleteCourse']);
}

function isBtnUpdatePrsd() {
    return isset($_POST['hiddenUpdatePrsd']) && empty($_POST['hiddenUpdatePrsd']);
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
        if (empty($errors) === false): ?>
            <div class="alert alert-danger">
                <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                <strong>Oh snap!</strong>
                <?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
            </div>
        <?php elseif (isModificationSuccessful()): ?>
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                <strong>Data successfully modified</strong> <br/>
            </div>
        <?php endif; ?>
        <div class="row">

            <div class="col-md-8">

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
                                    <th class="text-center" data-filterable="true" data-sortable="true">Code</th>
                                    <th class="text-center" data-filterable="true" data-sortable="false">Name</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                foreach ($courses as $course) {
                                    include(ROOT_PATH . "app/views/partials/course-table-data-view.html.php");
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
            <div class="col-md-4 col-sidebar-right">
                <h2>Add a new Course</h2>

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
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="post" id="create-form" action="<?php echo BASE_URL . 'academia/courses'; ?>" class="form">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title">Create Course</h3>
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
                        } else if (isModificationSuccessful()) {
                            ?>
                            <div class="alert alert-success">
                                <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                <strong>Course successfully created!</strong> <br/>
                            </div>
                        <?php } ?>
                        <div class="portlet-content">
                            <div class="row">
                                <div class="col-sm-12">

                                    <div class="form-group">
                                        <h5>
                                            <i class="fa fa-edit"></i>
                                            <label for="course_code">Course Code</label>
                                        </h5>
                                        <input type="text" id="course_code" name="course_code" class="form-control"
                                               value="<?php if (isset($_POST['course_code'])) echo
                                               htmlentities($_POST['course_code']); ?>"
                                               autofocus="on" required>
                                    </div>

                                    <div class="form-group">
                                        <h5>
                                            <i class="fa fa-edit"></i>
                                            <label for="course_name">Course Name</label>
                                        </h5>
                                        <input type="text" id="course_name" name="course_name" class="form-control"
                                               value="<?php if (isset($_POST['course_name'])) echo
                                               htmlentities($_POST['course_name']); ?>"
                                               required>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-tertiary" data-dismiss="modal">Close</button>
                    <input type="hidden" name="hidden_submit_pressed">
                    <button type="submit" class="btn btn-primary">Create</button>
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
            <form method="post" id="delete-form" action="<?php echo BASE_URL . 'academia/courses'; ?>" class="form">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title">Remove Course
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
                                    <strong>Warning!</strong><br/>Are you sure you want to delete this course?
                                    <br/><i>If a tutor is already teaching this course, then you'll have to first remove
                                        it from his profile.</i>
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
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="post" id="create-form" action="<?php echo BASE_URL . 'academia/courses'; ?>" class="form">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title">Update Course</h3>
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
                        } else if (isModificationSuccessful()) {
                            ?>
                            <div class="alert alert-success">
                                <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                <strong>Course successfully updated!</strong> <br/>
                            </div>
                        <?php } ?>
                        <div class="portlet-content">
                            <div class="row">
                                <div class="col-sm-12">

                                    <div class="form-group">
                                        <h5>
                                            <i class="fa fa-edit"></i>
                                            <label for="courseCodeUpdate">Edit Code</label>
                                        </h5>
                                        <input type="text" id="courseCodeUpdate" name="courseCodeUpdate"
                                               class="form-control"
                                               value="<?php if (isset($_POST['courseCodeUpdate'])) echo
                                               htmlentities($_POST['courseCodeUpdate']); ?>"
                                               autofocus="on" required>
                                    </div>

                                    <div class="form-group">
                                        <h5>
                                            <i class="fa fa-edit"></i>
                                            <label for="courseNameUpdate">Edit Name</label>
                                        </h5>
                                        <input type="text" id="courseNameUpdate" name="courseNameUpdate"
                                               class="form-control"
                                               value="<?php if (isset($_POST['courseNameUpdate'])) echo
                                               htmlentities($_POST['courseNameUpdate']); ?>"
                                               required>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-tertiary" data-dismiss="modal">Close</button>
                    <input type="hidden" name="hiddenUpdatePrsd">
                    <input type="hidden" id="updateCourseIdModal" name="updateCourseIdModal" value=""/>

                    <button type="submit" class="btn btn-primary">Update</button>
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

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datatables/DT_bootstrap.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/timepicker/bootstrap-timepicker.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/simplecolorpicker/jquery.simplecolorpicker.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/demos/form-extended.js"></script>

<script type="text/javascript">
    jQuery(function () {
        // prepare course id for delete on modal
        $(".btnDeleteCourse").click(function () {
            $inputVal = $(this).next('input').val();
            $("#delCourseIdModal").val($inputVal);
        });

        $(".btnUpdateCourse").click(function () {
            $courseId = $(this).next().next('input').val();
            $courseName = ($(this).parent().prev().text());
            $courseCode = ($(this).parent().prev().prev().text());

            $("#updateCourseIdModal").val($courseId);
            $("#courseCodeUpdate").val($courseCode);
            $("#courseNameUpdate").val($courseName);

        });


    });

</script>

</body>
</html>