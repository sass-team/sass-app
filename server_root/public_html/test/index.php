<?php

echo "start test<br/>";

require "../app/config/app.php";
date_default_timezone_set('Europe/Athens');


// run script only during working hours  every two hours
if ( ! App::isWorkingDateTimeOn())
{
	echo "Currently is not working date";
	exit();
}

echo "Currently is working date";
