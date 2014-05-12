<?php
/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/13/14
 * Time: 1:26 AM
 */

class Admins {

	private $db;

	public function __construct($db) {
		$this->db = $db;
	} // end __construct


	/**
	 * return true if user applied correct username/pass
	 */
	public function login($email, $password) {
		//global $bcrypt; // global bcry variable

		$query = $this->db->prepare("SELECT * FROM `sass-ms_db`.admin
			WHERE admin.email = '?' AND
			admin.password = ?;");
		$query->bindValue(1, $email);
		$query->bindValue(2, $password);

		try {

			$query->execute();
			$data = $query->fetch();
//			$stored_password = $data['password'];
			$id = $data['id'];
			return $data;
//			$is_admin = $data['is_admin'];

//			// using the verify method to compare the password with the stored hashed password.
//			if ($bcrypt->verify($password, $stored_password) === true) {
//				$output[] = array('id' => $id, 'is_admin' => $is_admin);
//				return $output;
//			} else {
//				return false;
//			}

		} catch (PDOException $e) {
			exit($e->getMessage());
		}
	}// end function login
}