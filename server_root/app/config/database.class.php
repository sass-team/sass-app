<?php
//
///**
// * Created by PhpStorm.
// * User: Riza
// * Date: 5/12/14
// * Time: 11:49 PM
// */
//class Database
//{
//
//	private $connection;
//	const DB_HOST = "localhost";
//	const DB_NAME = "sass-ms_db";
//	const DB_PORT = "3306";
////
////
////		define("DB_HOST", "mysql.hostinger.gr");
////define("DB_NAME", "u110998101_sassd");
////define("DB_PORT", "3306"); // default port.
////define("DB_USER", "u110998101_sassu");
////define("DB_PASS", "#UUQ|39R}{lKUDZR");
//
//
//	/**
//	 * @param mysql :host=localhost
//	 *   1st part of string: type of database
//	 *   2nd depending on type of db, on this case host=.
//	 *   3rd name of database
//	 *   4th if sql uses default port, this is not need. else you need to specify it like:
//	 * Probably this exception should be emailed to programmers.
//	 *
//	 * @param $database
//	 * @throws Exception error message -- could not connect to database.
//	 */
//	public function __construct() {
//		try { // connects to database
//			$this->setConnection(new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=" . DB_PORT, DB_USER, DB_PASS));
//			$this->getConnection()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // CHANGE THE ERROR MODE, THROW AN EXCEPTION WHEN AN ERROR IS FOUND
//			$this->getConnection()->exec("SET NAMES 'utf8'");
//		} catch (PDOException $e) { // program ends if exception is found
//			throw new Exception("Could not connect to the database.");
//		} // end
//
//	}
//
//	/**
//	 * @return \PDO
//	 */
//	public function getConnection() {
//		return $this->connection;
//	}
//
//	/**
//	 * @param \PDO $db
//	 */
//	public function setConnection($db) {
//		$this->connection = $db;
//	} // end __construct
//
//
//	public function confirmRecover($db, $email, $id) {
//
//		try {
//			$unique = uniqid('', true); // generate a unique string
//			$random = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10); // generate a more random string
//			$generated_string = $unique . $random; // a random and unique string
//			$query = $this->getConnection()->prepare("UPDATE `" . DB_NAME . "`.`user` SET `gen_string` = ? WHERE `id` = ?");
//			$query->bindValue(1, $generated_string);
//			$query->bindValue(2, $id);
//
//			$query->execute();
//
//			$generated_string = User::generateNewPasswordString($db, $id);
//
//			$email = trim($email);
//			$message = "We heard that you lost your SASS password. Sorry about that!<br/><br/>";
//			$message .= "But don't worry! You can use the following link within the next day to reset your password:<br/><br/>";
//			$message .= "<a href='http://" . $_SERVER['SERVER_NAME'] . "/login/recover/" . $id . "/" . $generated_string . "' target='_blank' >Reset Password</a><br/><br/>";
//			$message .= "If you don&#39;t use this link within 1 hour, it will expire. To get a new password reset link, visit: ";
//			$message .= "<a href='http://" . $_SERVER['SERVER_NAME'] . "/login/confirm-password' target='_blank'>Request New Password Reset Link</a><br/><br/>";
//			$message .= "Thanks,<br/>SASS Automatic System";
//
//			// mailer process
//			require_once(ROOT_PATH . "plugins/PHPMailer/class.phpmailer.php");
//			//Create a new PHPMailer instance
//			$mail = new PHPMailer();
//
//			$email_body = "";
//			$email_body = $message;
//
//			//Set who the message is to be sent from
//			$mail->setFrom($email, "no-reply@" . $_SERVER['SERVER_NAME']);
//			//Set who the message is to be sent to
//			$address = $email;
//			$mail->addAddress($address, "SASS Automatic System");
//			//Set the subject line
//			$mail->Subject = 'SASS | ' . 'Recovery System';
//			//Read an HTML message body from an external file, convert referenced images to embedded,
//			//convert HTML into a basic plain-text alternative body
//			$mail->msgHTML($email_body);
//			//Attach an image file
//			//$mail->addAttachment('images/phpmailer_mini.gif');
//
//			//send the message, check for errors
//			if ($mail->send()) { // successful mail sender
//				return true;
//			} else {
//				throw new Exception("There was a problem sending the email: " . $mail->ErrorInfo);
//			} // end else
//
//		} catch (Exception $e) {
//			throw new Exception("We could not send your email. Please retry.");
//		}
//	} // end confirm_recover
//
//	public function addNewPassword($id, $new_password_1, $new_password_2) {
//		if ($new_password_1 !== $new_password_2) {
//			throw new Exception("There was a mismatch with the new passwords");
//		}
//
//		User::validatePassword($new_password_1);
//
//		try {
//			$new_password_hashed = password_hash($new_password_1, PASSWORD_DEFAULT);
//
//			$query = "UPDATE `" . DB_NAME . "`.`user` SET `password`= :password, `gen_string`='' WHERE `id`= :id";
//			$query = $this->getConnection()->prepare($query);
//			$query->bindParam(':id', $id);
//			$query->bindParam(':password', $new_password_hashed);
//
//			$query->execute();
//		} catch (Exception $e) {
//			throw new Exception("Could not connect to database.");
//		}
//	}
//}
//
//?>