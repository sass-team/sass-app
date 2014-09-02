<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 8/23/14
 * Time: 2:15 PM
 */
class Student extends Person
{
    private $ci, $credits;


    public function  __construct($db, $id, $firstName, $lastName, $email, $mobileNum, $ci, $credits) {
        parent::__construct($db, $id, $firstName, $lastName, $email, $mobileNum);

        $this->setCi($ci);
        $this->setCredits($credits);
    }

    /**
     * @param mixed $credits
     */
    public function setCredits($credits) {
        $this->credits = $credits;
    }

    public static function add($db, $firstName, $lastName, $email, $mobileNum, $courses, $ci, $credits) {

    }

    /**
     * @return mixed
     */
    public static function retrieve($db) {
        $query = "SELECT id, email, f_name, l_name, mobile, ci, credits
		         FROM `" . DB_NAME . "`.student";
        $query = $db->getConnection()->prepare($query);

        try {
            $query->execute();
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            return $rows;
        } catch (PDOException $e) {
            throw new Exception("Something terrible happened. Could not retrieve users data from database.: " . $e->getMessage());
        } // end catch
    }

    public static function create($db, $firstName, $lastName, $email, $courseId, $mobileNum, $majorId, $ci, $credits) {
        // Validate data
        Person::validateName($firstName);
        Person::validateName($lastName);
        Person::validateEmail($db, $email, StudentFetcher::DB_TABLE);
        Course::validateSingle($db, $courseId);
        $mobileNum = self::validateMobileNumber($db, $mobileNum);
        if (!empty($majorId)) Major::validate($db, $majorId);
        $ci = Student::validateCi($ci);
        $credits = Student::validateCredits($credits);

        // Insert into database
        StudentFetcher::insert($db, $firstName, $lastName, $email, $courseId, $mobileNum, $majorId, $ci, $credits);
    }

    /**
     * @param $db
     * @param $newMobileNum
     * @throws Exception
     */
    public static function validateMobileNumber($db, $newMobileNum) {
        if (empty($newMobileNum) === TRUE) {
            return NULL; // no mobilenumber
        }
        if (!preg_match('/^[0-9]{10}$/', $newMobileNum)) {
            throw new Exception('Mobile number should contain only digits of total length 10');
        }

        if (self::existsMobileNum($db, $newMobileNum)) {
            throw new Exception("Mobile entered number already exists in database."); // the array of errors messages
        }

        return $newMobileNum;
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

    public static function validateCi($ci) {
        if (empty($ci) === TRUE) return NULL;
        $ci = trim($ci);
        if (!preg_match('/^[0-4](\.[0-9]+)?$/', $ci)) throw new Exception('CI must similar to 3.5; 0 =< CI <= 4');

        return $ci;
    }

    public static function validateCredits($credits) {
        if (empty($credits) === TRUE) return null;
        $credits = trim($credits);
        if (!preg_match('/^[0-9]+$/', $credits)) throw new Exception('CI must similar to 3.5; 0 =< CI <= 4');

        return $credits;
    }

    /**
     * @return mixed
     */
    public function getCredits() {
        return $this->credits;
    }

    /**
     * @return mixed
     */
    public function getCi() {
        return $this->ci;
    }

    /**
     * @param mixed $ci
     */
    public function setCi($ci) {
        $this->ci = $ci;
    }


}