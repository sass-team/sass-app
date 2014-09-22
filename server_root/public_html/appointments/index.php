<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();

// redirect if user elevation is not that of secretary or admin
if ($user->isTutor()) {
    header('Location: ' . BASE_URL . "error-403");
    exit();
}
$pageTitle = "All Appointments";
$section = "appointments";

try {
    if (isUrlRequestingSingleAppointment()) {
        $appointmentId = $_GET['appointmentId'];
        $students = Appointment::getAllStudentsWithAppointment($db, $appointmentId);
        $course = Course::get($db, $students[0][AppointmentFetcher::DB_COLUMN_COURSE_ID]);
        var_dump($course);
//        $appointment =
        if (!$user->isTutor()) {

        }
    } else if (isUrlRequestingAllAppointments()) {
        $curTerms = TermFetcher::retrieveCurrTerm($db);
        $courses = CourseFetcher::retrieveAll($db);
        $instructors = InstructorFetcher::retrieveAll($db);
        $appointments = AppointmentFetcher::retrieveAll($db);
    }


} catch (Exception $e) {
    $errors[] = $e->getMessage();
}


function isUrlRequestingSingleAppointment()
{
    return isset($_GET['appointmentId']) && preg_match("/^[0-9]+$/", $_GET['appointmentId']);
}


function isUrlRequestingAllAppointments()
{
    return !isset($_GET) && !isset($_POST);
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
        Registered Workshop Session

    </h1>


</div>
<!-- #content-header -->

<div id="content-container">

    <div class="portlet">

        <?php if (isUrlRequestingSingleAppointment()) { ?>

        <div class="col-md-12">
            <div class="portlet-header">

                <h3>
                    <i class="fa fa-calendar"></i>
                    Details
                </h3>

            </div>
            <!-- /.portlet-header -->

            <div class="portlet-content">

                <div class="form-group">


                </div>


                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <h4>Students</h4>

                        <table class="table">
                            <tbody>
                            <?php foreach ($students as $student):
                                include(ROOT_PATH . "app/views/partials/student/name-table-data-view.html.php");
                            endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.col -->

                    <div class="col-md-6 col-sm-6">

                        <h4>Instructors</h4>

                        <table class="table">
                            <tbody>
                            <tr>
                                <td>
                                    <strong>Maira Kotsovoulou</strong>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.col -->


                </div>
                <!-- /.row -->

                <div class="row">

                    <div class="form-group">
                        <div class="col-md-6 col-sm-6">
                            <span class="input-group-addon"><label for="courseId">Course</label></span>
                            <?php echo $course[CourseFetcher::DB_COLUMN_CODE] . " " . $course[CourseFetcher::DB_COLUMN_NAME]; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-sm-6">
										<span class="input-group-addon"><label id="label-instructor-text"
                                                                               for="tutorId">Tutors</label></span>
                            <select id="tutorId" name="tutorId" class="form-control" required>
                                <option></option>
                            </select>
                            <input id="value" type="hidden" style="width:300px"/>
                        </div>
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
            </div>
            <!-- /.form-group -->

        </div>
    </div>

    <?php } else { ?>

        <div class="col-md-12">
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

    <?php } ?>


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