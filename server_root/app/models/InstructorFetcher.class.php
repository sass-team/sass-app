<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 8/24/14
 * Time: 12:38 AM
 */
class InstructorFetcher extends Person
{
	const DB_TABLE = "instructor";
	const DB_ID = "id";
	const DB_FIRST_NAME = "f_name";
	const DB_LAST_NAME = "l_name";

	/**
	 * @return mixed
	 */
	public static function retrieveAll($db) {
		$query = "SELECT `" . self::DB_ID . "`, `" . self::DB_FIRST_NAME . "`, `" .
			self::DB_LAST_NAME . "` FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`";
		$query = $db->getConnection()->prepare($query);

		try {
			$query->execute();
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);

			return $rows;
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not retrieve instructors data from database.: ");
		} // end catch
	}

	public static function idExists($db, $instructorId) {
		try {
			$sql = "SELECT COUNT(" . self::DB_ID . ") FROM `" . DB_NAME . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_ID . "` = :instructorId";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':instructorId', $instructorId, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === 0) return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if instructor id already exists on database. <br/> Aborting process.");
		}

		return true;
	}

	public static function create($db, $firstName, $lastName) {
		self::validateName($firstName);
		self::validateName($lastName);

		// validate courses -- only numbers
		//$this->validate_user_major($user_major_ext);
		//$this->validate_teaching_course($teaching_courses);

		try {
			$query = "INSERT INTO `" . DB_NAME . "`.`" . self::DB_TABLE . "` (`f_name`, `l_name`) VALUES
			(:firstName, :lastName)";

			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':firstName', $firstName, PDO::PARAM_STR);
			$query->bindParam(':lastName', $lastName, PDO::PARAM_STR);
			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not insert instructor into database.");
		}
	}

	public static function update($db, $instructorId, $newInstructorFirstname, $newInstructorLastname) {
		$newInstructorFirstname = trim($newInstructorFirstname);
		$newInstructorLastname = trim($newInstructorLastname);

		$query = "UPDATE `" . DB_NAME . "`.`" . InstructorFetcher::DB_TABLE . "`
					SET `" . self::DB_FIRST_NAME . "`= :newInstructorFirstname, 
						`" . self::DB_LAST_NAME . "`= :newInstructorLastname
					WHERE `" . self::DB_ID . "`= :instructorId";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':newInstructorFirstname', $newInstructorFirstname, PDO::PARAM_STR);
			$query->bindParam(':newInstructorLastname', $newInstructorLastname, PDO::PARAM_STR);
			$query->execute();

			return true;
		} catch (Exception $e) {
			throw new Exception("Something terrible happened. Could not update instructor.");}

	}

	public static function updateLname($db, $id, $newLname) {
		$newLname = trim($newLname);

		$query = "UPDATE `" . DB_NAME . "`.`" . InstructorFetcher::DB_TABLE . "`
					SET	`" . self::DB_LAST_NAME . "`= :newInstructorLastname
					WHERE  `" . self::DB_ID . "`= :instructorId";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':instructorId', $id, PDO::PARAM_INT);
			$query->bindParam(':newInstructorLastname', $newLname, PDO::PARAM_STR);
			$query->execute();

			return true;
		} catch (Exception $e) {
			throw new Exception("Something terrible happened. Could not update instructor last name");}

	}

	public static function updateFname($db, $id, $newFname) {
		$newFname = trim($newFname);

		$query = "UPDATE `" . DB_NAME . "`.`" . InstructorFetcher::DB_TABLE . "`
					SET	`" . self::DB_FIRST_NAME . "`= :newFname
					WHERE  `" . self::DB_ID . "`= :instructorId";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':instructorId', $id, PDO::PARAM_INT);
			$query->bindParam(':newFname', $newFname, PDO::PARAM_STR);
			$query->execute();

			return true;
		} catch (Exception $e) {
			throw new Exception("Something terrible happened. Could not update instructor first name");}

	}

	public static function delete($db, $id) {
		try {
			$query = "DELETE FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "` WHERE `" . self::DB_ID . "` = :id";
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not delete instructor from database.");
		}
	}


} 