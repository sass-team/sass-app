<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/14/2014
 * Time: 1:37 PM
 */
require __DIR__ . '/../app/init.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>PHPMailer - mail() test</title>
</head>
<body>

<?php
try {
	Mailer::sendNewAccount("senderEmail", "senderName", "receiverEmail", "receiverName", "link");
	echo "Mail sent";
} catch (Exception $e) {
	echo $e->getMessage();
}
?>

</body>
</html>