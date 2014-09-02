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
    const DB_COLUMN_EMAIL = "email";
    const DB_COLUMN_FIRST_NAME = "f_name";
    const DB_COLUMN_LAST_NAME = "l_name";
    const DB_COLUMN_MOBILE = "mobile";
    const DB_COLUMN_CI = "ci";
    const DB_COLUMN_CREDITS = "credits";

    public static function add($db, $firstName, $lastName, $email, $mobileNum, $courses, $ci, $credits) {

    }

    /**
     * @return mixed
     */
    public static function retrieve($db) {
        $query = "SELECT id, email, f_name, l_name, mobile, ci, credits
		         FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`";
        $query = $db->getConnection()->prepare($query);

        try {
            $query->execute();
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            return $rows;
        } catch (PDOException $e) {
            throw new Exception("Something terrible happened. Could not retrieve users data from database.: " . $e->getMessage());
        } // end catch
    }

    public static function insert($db, $firstName, $lastName, $email, $courseId, $mobileNum, $majorId, $ci, $credits) {

        try {
            $query = "INSERT INTO `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			    (`" . self::DB_COLUMN_EMAIL . "`, `" . self::DB_COLUMN_FIRST_NAME . "`, `" . self::DB_COLUMN_LAST_NAME .
                "`, `" . self::DB_COLUMN_MOBILE . "`,  `" . self::DB_COLUMN_CI . "`, `" . self::DB_COLUMN_CREDITS . "`
                )
				VALUES
				(
					:email,
					:first_name,
					:last_name,
					:mobile,
					:ci,
					:credits
				)";


            $query = $db->getConnection()->prepare($query);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':first_name', $firstName, PDO::PARAM_STR);
            $query->bindParam(':last_name', $lastName, PDO::PARAM_STR);
            $query->bindParam(':mobile', $mobileNum, PDO::PARAM_INT);
            $query->bindParam(':ci', $ci, PDO::PARAM_INT);
            $query->bindParam(':credits', $credits, PDO::PARAM_INT);

            $query->execute();
            return true;
        } catch (Exception $e) {
            throw new Exception("Could not insert user into database." . $e->getMessage());
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
}