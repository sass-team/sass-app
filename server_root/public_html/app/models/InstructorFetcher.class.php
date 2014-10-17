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
	const DB_COLUMN_ID = "id";
	const DB_COLUMN_FIRST_NAME = "f_name";
	const DB_COLUMN_LAST_NAME = "l_name";

	/**
	 * @return mixed
	 */
	public static function retrieveAll() {
		$query = "SELECT `" . self::DB_COLUMN_ID . "`, `" . self::DB_COLUMN_FIRST_NAME . "`, `" .
			self::DB_COLUMN_LAST_NAME . "` FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->execute();
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);

			return $rows;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Something terrible happened. Could not retrieve instructors data from database.: ");
		} // end catch
	}

	public static function idExists($instructorId) {
		try {
			$query = "SELECT COUNT(" . self::DB_COLUMN_ID . ") FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :instructorId";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':instructorId', $instructorId, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === 0) return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if instructor id already exists on database. <br/> Aborting process.");
		}

		return true;
	}

	public static function create($firstName, $lastName) {
		self::validateName($firstName);
		self::validateName($lastName);

		// validate courses -- only numbers
		//$this->validate_user_major($user_major_ext);
		//$this->validate_teaching_course($teaching_courses);

		try {
			$query = "INSERT INTO `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "` (`f_name`, `l_name`) VALUES
			(:firstName, :lastName)";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':firstName', $firstName, PDO::PARAM_STR);
			$query->bindParam(':lastName', $lastName, PDO::PARAM_STR);
			$query->execute();
			return true;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not insert instructor into database.");
		}
	}

	public static function update($instructorId, $newInstructorFirstname, $newInstructorLastname) {
		$newInstructorFirstname = trim($newInstructorFirstname);
		$newInstructorLastname = trim($newInstructorLastname);

		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . InstructorFetcher::DB_TABLE . "`
					SET `" . self::DB_COLUMN_FIRST_NAME . "`= :newInstructorFirstname,
						`" . self::DB_COLUMN_LAST_NAME . "`= :newInstructorLastname
					WHERE `" . self::DB_COLUMN_ID . "`= :instructorId";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':newInstructorFirstname', $newInstructorFirstname, PDO::PARAM_STR);
			$query->bindParam(':newInstructorLastname', $newInstructorLastname, PDO::PARAM_STR);
			$query->execute();

			return true;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Something terrible happened. Could not update instructor.");}

	}

	public static function updateLname($id, $newLname) {
		$newLname = trim($newLname);

		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . InstructorFetcher::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_LAST_NAME . "`= :newInstructorLastname
					WHERE  `" . self::DB_COLUMN_ID . "`= :instructorId";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':instructorId', $id, PDO::PARAM_INT);
			$query->bindParam(':newInstructorLastname', $newLname, PDO::PARAM_STR);
			$query->execute();

			return true;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Something terrible happened. Could not update instructor last name");}

	}

	public static function updateFname($id, $newFname) {
		$newFname = trim($newFname);

		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . InstructorFetcher::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_FIRST_NAME . "`= :newFname
					WHERE  `" . self::DB_COLUMN_ID . "`= :instructorId";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':instructorId', $id, PDO::PARAM_INT);
			$query->bindParam(':newFname', $newFname, PDO::PARAM_STR);
			$query->execute();

			return true;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Something terrible happened. Could not update instructor first name");}

	}

	public static function delete($id) {
		try {
			$query = "DELETE FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :id";
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();
			return true;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not delete instructor from database.");
		}
	}


} 