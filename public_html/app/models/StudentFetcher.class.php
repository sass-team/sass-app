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

    public static function add($firstName, $lastName, $email, $mobileNum, $courses, $ci, $credits)
    {

    }

    /**
     * @throws Exception
     * @internal param $db
     */
    public static function retrieveAll()
    {
        $query =
            "SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_STUDENT_ID . "`, `" . self::DB_TABLE . "`.`" .
            self::DB_COLUMN_ID . "`, `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_EMAIL . "`, `" . self::DB_TABLE . "`.`"
            . self::DB_COLUMN_FIRST_NAME . "`, `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_LAST_NAME . "`, `" .
            self::DB_TABLE . "`.`" . self::DB_COLUMN_MOBILE . "`,  `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_CI . "`,
				`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_CREDITS . "`, `" . MajorFetcher::DB_TABLE . "`.`" .
            MajorFetcher::DB_COLUMN_ID . "` AS `" . self::DB_COLUMN_MAJOR_ID . "`,`" . MajorFetcher::DB_TABLE . "`.`" .
            MajorFetcher::DB_COLUMN_NAME . "` , `" . MajorFetcher::DB_TABLE . "`.`" .
            MajorFetcher::DB_COLUMN_CODE . "` FROM `" . App::getDbName() . "`.`" . self::DB_TABLE . "`, `" . App::getDbName() . "`.`" .
            MajorFetcher::DB_TABLE . "`
			WHERE `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_MAJOR_ID . "` = `" . MajorFetcher::DB_TABLE . "`.`" .
            MajorFetcher::DB_COLUMN_ID . "`;";


        try {
            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->execute();
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            return $rows;
        } catch (PDOException $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Something terrible happened. Could not retrieve students data from database.: ");
        } // end catch
    }

    public static function insert($firstName, $lastName, $email, $studentId, $mobileNum, $majorId, $ci, $credits)
    {

        try {
            $query = "INSERT INTO `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
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


            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':student_id', $studentId, PDO::PARAM_INT);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':first_name', $firstName, PDO::PARAM_STR);
            $query->bindParam(':last_name', $lastName, PDO::PARAM_STR);
            $query->bindParam(':mobile', $mobileNum, PDO::PARAM_INT);
            $query->bindParam(':ci', $ci, PDO::PARAM_STR);
            $query->bindParam(':credits', $credits, PDO::PARAM_INT);
            $query->bindParam(':major_id', $majorId, PDO::PARAM_INT);

            $query->execute();
            return true;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Could not insert student into database.");
        }

    }


    public static function existsMobileNum($newMobileNum)
    {
        try {
            $query = "SELECT COUNT(" . self::DB_COLUMN_MOBILE . ") FROM `" . App::getDbName() . "`.`" .
                self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_MOBILE . "` = :mobileNum";
            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':mobileNum', $newMobileNum, PDO::PARAM_INT);
            $query->execute();

            if ($query->fetchColumn() === '0') return false;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Could not check if new mobile number already exists on database.");
        }

        return true;
    }

    public static function exists($column, $value, $type)
    {
        try {
            $query = "SELECT COUNT(" . self::DB_COLUMN_ID . ") FROM `" . App::getDbName() . "`.`" .
                self::DB_TABLE . "` WHERE `" . $column . "` = :column_value";

            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':column_value', $value, $type);
            $query->execute();

            if ($query->fetchColumn() === '0') return false;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Could not verify data on database.");
        }

        return true;
    }

    public static function updateFirstName($id, $newName)
    {
        $query = "UPDATE `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_FIRST_NAME . "`= :newName
					WHERE `" . self::DB_COLUMN_ID . "`= :id";

        try {
            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':newName', $newName, PDO::PARAM_STR);
            $query->execute();

            return true;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Something terrible happened. Could not update first name");
        }
    }

    public static function updateLastName($id, $newName)
    {
        $query = "UPDATE `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_LAST_NAME . "`= :newName
					WHERE `" . self::DB_COLUMN_ID . "`= :id";

        try {
            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':newName', $newName, PDO::PARAM_STR);
            $query->execute();

            return true;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Something terrible happened. Could not update last name");
        }
    }

    public static function updateMobileNum($id, $mobileNum)
    {
        $query = "UPDATE `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_MOBILE . "`= :mobileNum
					WHERE `" . self::DB_COLUMN_ID . "`= :id";

        try {
            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':mobileNum', $mobileNum, PDO::PARAM_STR);
            $query->execute();

            return true;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Something terrible happened. Could not update mobile number");
        }
    }

    public static function updateStudentId($id, $studentId)
    {
        $query = "UPDATE `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_STUDENT_ID . "`= :newStudentId
					WHERE `" . self::DB_COLUMN_ID . "`= :id";

        try {
            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':newStudentId', $studentId, PDO::PARAM_INT);
            $query->execute();

            return true;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Something terrible happened. Could not update student id.");
        }
    }

    public static function updateMajorId($id, $majorId)
    {
        $query = "UPDATE `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_MAJOR_ID . "`= :majorId
					WHERE `" . self::DB_COLUMN_ID . "`= :id";

        try {
            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':majorId', $majorId, PDO::PARAM_INT);
            $query->execute();

            return true;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Something terrible happened. Could not update major id.");
        }
    }

    public static function updateCi($id, $newCi)
    {
        $query = "UPDATE `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_CI . "`= :ci
					WHERE `" . self::DB_COLUMN_ID . "`= :id";

        try {
            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':ci', $newCi, PDO::PARAM_BOOL);
            $query->execute();

            return true;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Something terrible happened. Could not update CI.");
        }
    }

    public static function updateCredits($id, $credits)
    {
        $query = "UPDATE `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_CREDITS . "`= :credits
					WHERE `" . self::DB_COLUMN_ID . "`= :id";

        try {
            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':credits', $credits, PDO::PARAM_INT);
            $query->execute();

            return true;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Something terrible happened. Could not update credits.");
        }
    }


    public static function existsEmail($email)
    {
        try {
            $email = trim($email);
            $query = "SELECT COUNT(`" . self::DB_COLUMN_ID . "`) FROM `" . App::getDbName() . "`.`" . self::DB_TABLE . "` WHERE `" .
                self::DB_COLUMN_EMAIL . "` = :email";

            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':email', $email, PDO::PARAM_STR);

            $query->execute();

            if ($query->fetchColumn() === '0') return false;

        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Something terrible happened. Could not access database.");
        } // end catch

        return true;

    } // end function get_data

    public static function updateEmail($id, $email)
    {
        try {

            $query = "UPDATE `" . App::getDbName() . "`.`" . self::DB_TABLE . "` SET `" . self::DB_COLUMN_EMAIL . "` = :email WHERE `" .
                self::DB_COLUMN_ID . "`= :id";

            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->execute();
            return true;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception($e->getMessage());
        }

    }


    public static function existsStudentId($studentId)
    {
        try {
            $query = "SELECT COUNT(" . self::DB_COLUMN_STUDENT_ID . ") FROM `" . App::getDbName() . "`.`" .
                self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_STUDENT_ID . "` = :studentId";

            $dbConnection = DatabaseManager::getConnection();
            $query = $dbConnection->prepare($query);
            $query->bindParam(':studentId', $studentId, PDO::PARAM_INT);
            $query->execute();

            if ($query->fetchColumn() === '0') return false;
        } catch (Exception $e) {
            Mailer::sendDevelopers($e->getMessage(), __FILE__);
            throw new Exception("Could not check if stuent id already exists on database.");
        }

        return true;
    }
}