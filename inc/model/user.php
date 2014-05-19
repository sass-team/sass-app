<?php

/**
 * Class User will contain prototype for users; tutors, secretary and admin.
 * Log In, Log Out.
 */
class User {

	// connection to db.
	private $db;

	/**
	 * Constructor
	 * @param $database
	 */
	public function __construct($database) {
		$this->db = $database;
	} // end __construct

	/**
	 * Returns all information of a user given his email.
	 * @param $email $email of user
	 * @return mixed If
	 */
	function get_data($email) {

		$query = $this->db->prepare("SELECT * FROM user
                                    LEFT OUTER JOIN user_types ON user.user_types_id = user_types.id
                                    LEFT OUTER JOIN major ON user.major_id = major.id
                                    WHERE email = ?
                                ");
		$query->bindValue(1, $email);

		try {
			$query->execute();
			return $query->fetch();
		} catch (PDOException $e) {
			die($e->getMessage());
		} // end try
	} // end function get_data

}

?>
