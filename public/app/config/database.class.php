<?php

/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/12/14
 * Time: 11:49 PM
 */
class Database
{

	private $connection;

	/**
	 * @param mysql :host=localhost
	 *   1st part of string: type of database
	 *   2nd depending on type of db, on this case host=.
	 *   3rd name of database
	 *   4th if sql uses default port, this is not need. else you need to specify it like:
	 * Probably this exception should be emailed to programmers.
	 *
	 * @param $database
	 * @throws Exception error message -- could not connect to database.
	 */
	public function __construct() {
		try { // connects to database
			$this->setConnection(new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=" . DB_PORT, DB_USER, DB_PASS));
			$this->getConnection()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // CHANGE THE ERROR MODE, THROW AN EXCEPTION WHEN AN ERROR IS FOUND
			$this->getConnection()->exec("SET NAMES 'utf8'");
		} catch (PDOException $e) { // program ends if exception is found
			throw new Exception("Could not connect to the database.");
		} // end

	}

	/**
	 * @return \PDO
	 */
	public function getConnection() {
		return $this->connection;
	}

	/**
	 * @param \PDO $db
	 */
	public function setConnection($db) {
		$this->connection = $db;
	} // end __construct


	/**
	 * Verifies given credentials are correct. If login successfuly, returns true
	 * else return the error message.
	 *
	 * Dependancies:
	 * require_once ROOT_PATH . "inc/models/bcrypt.php";
	 * $bcrypt = new Bcrypt(12);
	 *
	 * @param $email $email of user
	 * @param $password $password of user
	 *
	 * @return bool|string
	 */
	public function login($email, $password) {

		if (empty($email) === true || empty($password) === true) {
			throw new Exception('Sorry, but we need both your email and password.');
		} else if ($this->emailExists($email) === false) {
			throw new Exception('Sorry that email doesn\'t exists.');
		}
		$query = "SELECT id, password, email FROM `" . DB_NAME . "`.user WHERE email = :email";
		$query = $this->connection->prepare($query);
		$query->bindParam(':email', $email);

		try {

			$query->execute();
			$data = $query->fetch();
			$hash_password = $data['password'];

			// using the verify method to compare the password with the stored hashed password.
			if (!password_verify($password, $hash_password)) {
				throw new Exception('Sorry, that email/password is invalid.');
			}

			return $data['id'];
		} catch (PDOException $e) {
			// "Sorry could not connect to the database."
			throw new Exception("Sorry could not connect to the database: ");
		}
	} // end __construct

	/**
	 * Verifies a user with given email exists.
	 * returns true if found; else false
	 *
	 * @param $email $email given email
	 * @return bool true if found; else false
	 */
	public function emailExists($email) {
		$email = trim($email);
		$query = "SELECT COUNT(id) FROM `" . DB_NAME . "`.user WHERE email = :email";

		$query = $this->getConnection()->prepare($query);
		$query->bindParam(':email', $email, PDO::PARAM_STR);

		try {
			$query->execute();
			$rows = $query->fetchColumn();

			if ($rows == 1) {
				return true;
			} else {
				return false;
			} // end else if

		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not access database.");
		} // end catch
	}

	/**
	 * Returns all information of a user given his email.
	 * @param $id $email of user
	 * @return mixed If
	 */
	function getData($id) {
		$query = "SELECT user.email, user.id, user.`f_name`, user.`l_name`, user.`img_loc`,
						user.date, user.`profile_description`, user.mobile, user_types.type
					FROM `" . DB_NAME . "`.user
						LEFT OUTER JOIN user_types ON user.`user_types_id` = `user_types`.id
					WHERE user.id = :id";

		$query = $this->getConnection()->prepare($query);
		$query->bindValue(':id', $id, PDO::PARAM_INT);

		try {
			$query->execute();
			return $query->fetch();
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not retrieve database." . $e->getMessage());
		} // end try
	}

	/**
	 * Returns a single column from the next row of a result set or FALSE if there are no more rows.
	 *
	 * @param $what
	 * @param $field
	 * @param $value
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public function fetchInfo($what, $field, $where, $value) {
		// I have only added few, but you can add more. However do not add 'password' even though the parameters will only be given by you and not the user, in our system.
		$allowed = array('id', 'username', 'f_name', 'l_name', 'email', 'COUNT(mobile)',
			 'mobile', 'user', 'gen_string', 'COUNT(gen_string)', 'COUNT(id)', 'img_loc', 'user_types', 'type');
		if (!in_array($what, $allowed, true) || !in_array($field, $allowed, true)) {
			throw new InvalidArgumentException;
		} else {
			try {
				$sql = "SELECT $what FROM `" . DB_NAME . "`.`" . $field . "` WHERE $where = ?";
				$query = $this->getConnection()->prepare($sql);
				$query->bindValue(1, $value, PDO::PARAM_STR);
				$query->execute();
				return $query->fetchColumn();
				//return $sql;
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}

		}
	} // end getAllData


	public function confirmRecover($email, $id) {

		try {
			$unique = uniqid('', true); // generate a unique string
			$random = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10); // generate a more random string
			$generated_string = $unique . $random; // a random and unique string
			$query = $this->getConnection()->prepare("UPDATE `" . DB_NAME . "`.`user` SET `gen_string` = ? WHERE `id` = ?");
			$query->bindValue(1, $generated_string);
			$query->bindValue(2, $id);

			$query->execute();
		} catch (PDOException $e) {
			throw new Exception("We could not send your email. Please retry.");
		}

		$email = trim($email);
		$message = "We heard that you lost your SASS password. Sorry about that!<br/><br/>";
		$message .= "But don't worry! You can use the following link within the next day to reset your password:<br/><br/>";
		$message .= "<a href='http://" . $_SERVER['SERVER_NAME'] . "/login/recover/" . $id . "/" . $generated_string . "' target='_blank' >Reset Password</a><br/><br/>";
		$message .= "If you don&#39;t use this link within 24 hours, it will expire. To get a new password reset link, visit: ";
		$message .= "<a href='http://" . $_SERVER['SERVER_NAME'] . "/login/confirm-password' target='_blank'>Request New Password Reset Link</a><br/><br/>";
		$message .= "Thanks,<br/>SASS Automatic System";

		// mailer process
		require_once(ROOT_PATH . "app/plugins/PHPMailer/class.phpmailer.php");
		//Create a new PHPMailer instance
		$mail = new PHPMailer();

		$email_body = "";
		$email_body = $message;

		//Set who the message is to be sent from
		$mail->setFrom($email, "no-reply@" . $_SERVER['SERVER_NAME']);
		//Set who the message is to be sent to
		$address = $email;
		$mail->addAddress($address, "SASS Automatic System");
		//Set the subject line
		$mail->Subject = 'SASS | ' . 'Recovery System';
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML($email_body);
		//Attach an image file
		//$mail->addAttachment('images/phpmailer_mini.gif');

		//send the message, check for errors
		if ($mail->send()) { // successful mail sender
			return true;
		} else {
			throw new Exception("There was a problem sending the email: " . $mail->ErrorInfo);
		} // end else
	} // end confirm_recover

	public function add_new_password($id, $new_password_1, $new_password_2) {
		if ($new_password_1 !== $new_password_2) {
			throw new Exception("There was a mismatch with the new passwords");
		}

		try {
			$new_password_hashed = password_hash($new_password_1, PASSWORD_DEFAULT);

			$query = "UPDATE `" . DB_NAME . "`.`user` SET `password`= :password, `gen_string`='' WHERE `id`= :id";
			$query = $this->getConnection()->prepare($query);
			$query->bindParam(':id', $id);
			$query->bindParam(':password', $new_password_hashed);

			$query->execute();
		} catch (Exception $e) {
			throw new Exception("Could not connect to database.");
		}
	}
}

?>