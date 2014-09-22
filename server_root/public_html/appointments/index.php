<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();

// redirect if user elevation is not that of secretary or admin
if ($user->isTutor()) {
    header('Location: ' . BASE_URL . "error-403");
    exit();
}


// viewers
$pageTitle = "New Workshop";
$section = "appointments";

try {
    if (!$user->isTutor()) {
        $curTerms = TermFetcher::retrieveCurrTerm($db);
        $courses = CourseFetcher::retrieveAll($db);
        $instructors = InstructorFetcher::retrieveAll($db);
        $students = StudentFetcher::retrieveAll($db);
        $appointments = AppointmentFetcher::retrieveAll($db);
    }

} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

function isBtnAddStudentPrsd()
{
    return isset($_POST['hiddenSubmitPrsd']) && empty($_POST['hiddenSubmitPrsd']);
}


function isModificationSuccess()
{
    return isset($_GET['success']) && strcmp($_GET['success'], 'y1!q' === 0);
}

/**
 * http://stackoverflow.com/a/4128377/2790481
 *
 * @param $findId
 * @param $objects
 * @return bool
 */
function get($objects, $findId, $column)
{
    foreach ($objects as $object) {
        if ($object[$column] === $findId) return $object;
    }

    return false;
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

    <h1>
        <i class="fa fa-calendar"></i>
        New Workshop Session

    </h1>


</div>
<!-- #content-header -->

<div id="content-container">

    <div class="portlet">
        <div class="row">

            <div class="col-md-5">
                <div class="portlet-header">

                    <h3>
                        <i class="fa fa-calendar"></i>
                        New Workshop Session
                    </h3>

                </div>
                <!-- /.portlet-header -->

                <div class="portlet-content">

                    <div class="form-group">
                        <form method="post" id="add-student-form"
                              action="<?php echo BASE_URL . 'appointments/add'; ?>"
                              class="form">


                            <div class="form-group" id="student-instructor">
                                <div class="input-group">
                                    <span class="input-group-addon"><label for="studentId1">Students</label></span>
                                    <select id="studentId1" name="studentsIds[]" class="form-control" required>
                                        <option></option>
                                        <?php
                                        foreach ($students as $student):
                                            include(ROOT_PATH . "app/views/partials/student/select-options-view.html.php");
                                        endforeach;
                                        ?>
                                    </select>
                                        <span class="input-group-addon"><label
                                                for="instructorId1">Instructor</label></span>
                                    <select id="instructorId1" name="instructorIds[]" class="form-control" required>
                                        <option></option>
                                        <?php foreach ($instructors as $instructor) {
                                            include(ROOT_PATH . "app/views/partials/instructor/select-options-view.html.php");
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><label for="courseId">Course</label></span>
                                    <select id="courseId" name="courseId" class="form-control" required>
                                        <option></option>
                                        <?php foreach ($courses as $course) {
                                            include(ROOT_PATH . "app/views/partials/course/select-options-view.html.php");
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="input-group">
										<span class="input-group-addon"><label id="label-instructor-text"
                                                                               for="tutorId">Tutors</label></span>
                                    <select id="tutorId" name="tutorId" class="form-control" required>
                                        <option></option>
                                    </select>
                                    <input id="value" type="hidden" style="width:300px"/>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class='input-group date' id='dateTimePickerStart'>
											<span class="input-group-addon"><label for="dateTimePickerStart">
                                                    Starts At</label></span>
                                    <input type='text' name='dateTimePickerStart' class="form-control" required/>
                                 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class='input-group date' id='dateTimePickerEnd'>
                                        <span class="input-group-addon"><label for="dateTimePickerEnd">Ends
                                                At</label></span>
                                    <input type='text' name='dateTimePickerEnd' class="form-control" required/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar">
										</span>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="input-group">
                                    <button type="button" class="btn btn-default btn-sm addButton"
                                            data-template="textbox">
                                        Add One More Student
                                    </button>
                                </div>
                            </div>

                            <div class="form-group hide" id="textboxTemplate">
                                <div class="input-group">
                                    <button type="button" class="btn btn-default btn-sm removeButton">Remove
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><label for="termId">Term</label></span>
                                    <select id="termId" name="termId" class="form-control" required>
                                        <?php
                                        foreach ($curTerms as $term) {
                                            include(ROOT_PATH . "app/views/partials/term/select-options-view.html.php");
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php
                                if (empty($errors) === false) {
                                    ?>
                                    <div class="alert alert-danger">
                                        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                        <strong>Oh
                                            snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>';
                                        ?>
                                    </div>
                                <?php
                                } else if (isModificationSuccess()) {
                                    ?>
                                    <div class="alert alert-success">
                                        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                        <strong>Workshop successfully created!</strong> <br/>
                                    </div>
                                <?php } ?>

                                <button type="submit" class="btn btn-block btn-primary">Add</button>
                                <input type="hidden" name="hiddenSubmitPrsd" value="">
                            </div>
                        </form>
                    </div>
                    <!-- /.form-group -->

                </div>
            </div>


            <div class="col-md-7">
                <div class="portlet-header">

                    <h3>
                        <i class="fa fa-calendar"></i>
							<span id="calendar-title">
								<i class='fa fa-circle-o-notch fa-spin'></i>
							</span>

                        <div class="external-event label ui-draggable fc-yellow" data-category="fc-yellow"
                             style="position: relative;">Working Hours
                        </div>
                        <div class="external-event label ui-draggable fc-red" data-category="fc-red"
                             style="position: relative;">Appointments
                        </div>
                    </h3>

                </div>
                <!-- /.portlet-header -->

                <div class="portlet-content">

                    <div id="appointments-schedule-calendar"></div>
                </div>
            </div>

        </div>
        <!-- /.row -->


    </div>
    <!-- /.portlet -->

</div>
<!-- /#content-container -->

<div id="push"></div>
</div>
<!-- #content -->

<?php include ROOT_PATH . "app/views/footer.php"; ?>
</div>
<!-- #wrapper<!-- #content -->


<?php include ROOT_PATH . "app/views/assets/footer_common.php"; ?>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/select2/select2.js"></script>


<script
    src="<?php echo BASE_URL; ?>assets/js/plugins/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js">
</script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script type="text/javascript"
        src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.1/js/bootstrapValidator.min.js"></script>

<script type="text/javascript">
    $(function () {
        // http://momentjs.com/docs/#/manipulating/add/
        // http://eonasdan.github.io/bootstrap-datetimepicker
        $("#appointments-schedule-calendar").fullCalendar({
            header: {
                left: 'prev,next',
                center: 'title',
                right: 'agendaWeek,month,agendaDay'
            },
            weekends: false, // will hide Saturdays and Sundays
            defaultView: "agendaWeek",
            editable: false,
            droppable: false,
            eventSources: [<?php AppointmentFetcher::printTutorsAppointments($db, $curTerms[0]); ?>],
            timeFormat: 'H(:mm)' // uppercase H for 24-hour clock
        });
    });
</script>

</body>
</html>