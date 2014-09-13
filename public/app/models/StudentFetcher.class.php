<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 8/23/14
 * Time: 2:15 PM
 */
class StudentFetcher extends Person
{
	const DB_TABLE = "student";
	const DB_COLUMN_ID = "id";
	const DB_COLUMN_EMAIL = "email";
	const DB_COLUMN_FIRST_NAME = "f_name";
	const DB_COLUMN_LAST_NAME = "l_name";
	const DB_COLUMN_MOBILE = "mobile";
	const DB_COLUMN_STUDENT_ID = "studentId";
	const DB_COLUMN_CI = "ci";
	const DB_COLUMN_CREDITS = "credits";
	const DB_COLUMN_MAJOR_ID = "major_id";

	public static function add($db, $firstName, $lastName, $email, $mobileNum, $courses, $ci, $credits) {

	}

	/**
	 * @param $db
	 * @throws Exception
	 */
	public static function retrieve($db) {
		$query = "SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`, `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_EMAIL . "`, `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_FIRST_NAME . "`,
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_LAST_NAME . "`, `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_MOBILE . "`,  `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_CI . "`,  `" . self::DB_TABLE .
			"`.`" . self::DB_COLUMN_CREDITS . "`, `" . MajorFetcher::DB_TABLE . "`.`" . MajorFetcher::DB_COLUMN_NAME . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`, `" . DB_NAME . "`.`" . MajorFetcher::DB_TABLE . "`
			WHERE `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_MAJOR_ID . "` = `" . MajorFetcher::DB_TABLE . "`.`" .
			MajorFetcher::DB_COLUMN_ID . "`;";
		$query = $db->getConnection()->prepare($query);

		try {
			$query->execute();
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);

			return $rows;
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not retrieve users data from database.: ");
		} // end catch
	}

	public static function insert($db, $firstName, $lastName, $email, $studentId, $mobileNum, $majorId, $ci, $credits) {

		try {
			$query = "INSERT INTO `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			(`" . self::DB_COLUMN_STUDENT_ID . "`,	`" . self::DB_COLUMN_EMAIL . "`, `" . self::DB_COLUMN_FIRST_NAME . "`,
			`" . self::DB_COLUMN_LAST_NAME . "`, `" . self::DB_COLUMN_MOBILE . "`,  `" . self::DB_COLUMN_CI . "`,
			`" . self::DB_COLUMN_CREDITS . "`, `" . self::DB_COLUMN_MAJOR_ID . "`
                )
				VALUES
				(
					:student_id,
					:email,
					:first_name,
					:last_name,
					:mobile,
					:ci,
					:credits,
					:major_id
				)";


			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':student_id', $studentId, PDO::PARAM_STR);
			$query->bindParam(':email', $email, PDO::PARAM_STR);
			$query->bindParam(':first_name', $firstName, PDO::PARAM_STR);
			$query->bindParam(':last_name', $lastName, PDO::PARAM_STR);
			$query->bindParam(':mobile', $mobileNum, PDO::PARAM_INT);
			$query->bindParam(':ci', $ci, PDO::PARAM_INT);
			$query->bindParam(':credits', $credits, PDO::PARAM_INT);
			$query->bindParam(':major_id', $majorId, PDO::PARAM_STR);

			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not insert student into database." . $e->getMessage());
		}
	}


	public static function existsMobileNum($db, $newMobileNum) {
		try {
			$sql = "SELECT COUNT(" . StudentFetcher::DB_COLUMN_MOBILE . ") FROM `" . DB_NAME . "`.`" .
				StudentFetcher::DB_TABLE . "` WHERE `" . StudentFetcher::DB_COLUMN_MOBILE . "` = :mobileNum";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':mobileNum', $newMobileNum, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if new mobile number already exists on database.");
		}

		return true;
	}

	public static function existsStudentId($db, $newMobileNum) {
		try {
			$sql = "SELECT COUNT(" . StudentFetcher::DB_COLUMN_STUDENT_ID . ") FROM `" . DB_NAME . "`.`" .
				StudentFetcher::DB_TABLE . "` WHERE `" . StudentFetcher::DB_COLUMN_STUDENT_ID . "` = :mobileNum";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':mobileNum', $newMobileNum, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if new mobile number already exists on database.");
		}

		return true;
	}
}