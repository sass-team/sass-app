<?php
require __DIR__ . '/../app/init.php';

echo Appointment::getCalendarSingleTutorAppointments(19, 7);
