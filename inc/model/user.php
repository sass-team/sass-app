<?php
/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/13/14
 * Time: 1:38 AM
 */

class User {
	/**
	 * Verifies email exists.
	 * returns true if found; else false
	 */
	public function user_exists($username) {
		$username = trim($username);

		$query = $this->db->prepare("SELECT COUNT(`id`) FROM `users` WHERE `username`= ?");
		$query->bindValue(1, $username);

		try {
			$query->execute();
			$rows = $query->fetchColumn();

			if ($rows == 1) {
				return true;
			} else {
				return false;
			} // end else if

		} catch (PDOException $e) {
			die($e->getMessage());
		} // end catch
	} // end function user_exists
}