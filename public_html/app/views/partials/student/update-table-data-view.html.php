<?php
/**
 * This file displays a single user  in a table data views. It needs to receive
 * the following tutor details (as elements of an array named $jobs):
 *
 *
 * Created by PhpStorm.
 * Date: 4/1/14
 * Time: 3:24 AM
 *
 */

$id = $student[StudentFetcher::DB_COLUMN_ID];
$first_name = $student[StudentFetcher::DB_COLUMN_FIRST_NAME];
$last_name = $student[StudentFetcher::DB_COLUMN_LAST_NAME];
$studentId = $student[StudentFetcher::DB_COLUMN_STUDENT_ID];
$email = $student[StudentFetcher::DB_COLUMN_EMAIL];
$mobile = $student[StudentFetcher::DB_COLUMN_MOBILE];
$ci = $student[StudentFetcher::DB_COLUMN_CI];
$credits = $student[StudentFetcher::DB_COLUMN_CREDITS];
$majorName = $student[MajorFetcher::DB_COLUMN_NAME];
$majorCode = $student[MajorFetcher::DB_COLUMN_CODE];
$majorId = $student[StudentFetcher::DB_COLUMN_MAJOR_ID];
// $studentAppointments received externally: $startDate, $course code
?>
<tr>
    <td class="text-center"><span><?php echo $first_name; ?></span>&#32;<span><?php echo $last_name; ?></span></td>
    <td class="text-center"><?php echo $studentId; ?></td>
    <td class="text-center"><?php echo $email; ?></td>
    <td class="text-center">
        <a class="btn btn-default btn-sm center-block ui-popover" data-toggle="tooltip" data-placement="top"
           data-trigger="hover"
           data-html="true"
           data-content=
           "<?php Appointment::printAppointmentsForHover($studentAppointments); ?>" title="Pending Appointments">
            <i class="fa fa-book"></i> View
        </a>
    </td>
    <td class="text-center"><?php echo $mobile; ?></td>
    <td class="text-center"><?php echo $majorCode . " " . $majorName; ?>
        <input type="hidden" value="<?php echo $majorId; ?>" id="majorId"/>

    </td>
    <td class="text-center"><?php echo $ci; ?></td>
    <td class="text-center"><?php echo $credits; ?></td>

    <!--    --><?php //if (!$user->isTutor()): ?>
    <!--        <td class="text-center">-->
    <!--            <a data-toggle="modal" href="#" class="btn btn-default btn-sm center-block">-->
    <!--                <i class="fa fa-calendar"></i> View-->
    <!--            </a>-->
    <!--        </td>-->
    <!--    --><?php //endif; ?>

    <?php if ($user->isAdmin()): ?>
        <td class="text-center">
            <a href="#updateStudent" data-toggle="modal" class="btn btn-xs btn-primary btnUpdateStudent"><i
                    class="fa fa-pencil fa-lg"></i></a>
            <input type="hidden" value="<?php echo $id; ?>" id="id" name="id"/>
        </td>
    <?php endif; ?>

</tr>