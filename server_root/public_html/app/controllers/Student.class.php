<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 8/23/14
 * Time: 2:15 PM
 */
class Student extends Person
{
    private $ci, $credits, $major;

    public function  __construct($db, $id, $firstName, $lastName, $email, $mobileNum, $ci, $credits, $major)
    {
        parent::__construct($id, $firstName, $lastName, $email, $mobileNum);

        $this->setCi($ci);
        $this->setCredits($credits);
        $this->setMajor($major);
    }

    /**
     * @param mixed $credits
     */
    public function setCredits($credits)
    {
        $this->credits = $credits;
    }

    /**
     * @param mixed $major
     */
    public function setMajor($major)
    {
        $this->major = $major;
    }

    public static function add($db, $firstName, $lastName, $email, $mobileNum, $courses, $ci, $credits)
    {

    }

    /**
     * @param $db
     * @throws Exception
     */
    public static function retrieve($db)
    {
        $query = "SELECT id, email, f_name, l_name, mobile, ci, credits
		         FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.student";
        $query = $db->getConnection()->prepare($query);

        try {
            $query->execute();
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            return $rows;
        } catch (PDOException $e) {
            throw new Exception("Something terrible happened. Could not retrieve students data from database.: ");
        } // end catch
    }


    public static function create($db, $firstName, $lastName, $email, $studentId, $mobileNum, $majorId, $ci, $credits)
    {
        // Validate data
        Person::validateName($firstName);
        Person::validateName($lastName);
        Person::validateNewEmail($email, StudentFetcher::DB_TABLE);
        self::validateStudentId($db, $studentId);
        self::validateMobileNumber($db, $mobileNum);
        Major::validateId($majorId);

        $ci = Student::validateCi($ci);
        $credits = Student::validateCredits($credits);

        // Insert into database
        StudentFetcher::insert($db, $firstName, $lastName, $email, $studentId, $mobileNum, $majorId, $ci, $credits);
    }

    public static function validateStudentId($db, $studentId)
    {
        if (!preg_match("/^[0-9]{6,7}$/", $studentId)) {
            throw new Exception("Student id can contain only numbers of length 6 to 7");
        }

        if (StudentFetcher::existsStudentId($db, $studentId)) {
            throw new Exception("Student id entered already exists in database."); // the array of errors messages
        }
    }

    /**
     * @param $db
     * @param $newMobileNum
     * @return null
     * @throws Exception
     */
    public static function validateMobileNumber($db, $newMobileNum)
    {
        if (empty($newMobileNum) === TRUE) {
            throw new Exception('Mobile number is required.');
        }

        if (!preg_match('/^[0-9]{10}$/', $newMobileNum)) {
            throw new Exception('Mobile number should contain only digits of total length 10');
        }

        if (StudentFetcher::existsMobileNum($db, $newMobileNum)) {
            throw new Exception("Mobile entered number already exists in database."); // the array of errors messages
        }
    }

    public static function validateCi($ci)
    {
        if (empty($ci) === TRUE) return NULL;
        if (!preg_match('/^[0-4](\.[0-9]+)?$/', $ci)) throw new Exception('CI must similar to 3.5; 0 =< CI <= 4');

        return $ci;
    }

    public static function validateCredits($credits)
    {
        if (empty($credits) === TRUE) return null;
        if (!preg_match('/^[0-9]{1,3}$/', $credits) || floatval($credits) > 200) throw new Exception('Credits should be in the range of 0 - 200');
    }

    public static function updateFirstName($db, $id, $newName, $oldName)
    {

        if (strcmp($newName, $oldName) === 0) return;

        self::validateName($newName);
        StudentFetcher::updateFirstName($db, $id, $newName);
    }

    public static function updateLastName($db, $id, $newName, $oldName)
    {
        if (strcmp($newName, $oldName) === 0) return;

        self::validateName($newName);
        StudentFetcher::updateLastName($db, $id, $newName);
    }

    public static function updateMobileNum($db, $id, $newMobileNum, $oldMobileNum)
    {
        if (strcmp($newMobileNum, $oldMobileNum) === 0) return;

        self::validateMobileNumber($db, $newMobileNum);
        StudentFetcher::updateMobileNum($db, $id, $newMobileNum);
    }

    public static function updateMajorId($db, $id, $newMajorId, $oldMajorId)
    {
        if (!isset($newMajorId) || empty($newMajorId)) throw new Exception("Data tempering detected. Aborting.");
        if (strcmp($newMajorId, $oldMajorId) === 0) return;

        Major::validateId($newMajorId);
        StudentFetcher::updateMajorId($db, $id, $newMajorId);
    }

    public static function updateCi($db, $id, $newCi, $oldCi)
    {
        if (strcmp($newCi, $oldCi) === 0) return;

        $newCi = self::validateCi($newCi);
        StudentFetcher::updateCi($db, $id, $newCi);
    }

    public static function updateEmail($db, $id, $newEmail, $oldEmail)
    {
        Student::validateNewEmail($newEmail, $oldEmail);
        StudentFetcher::updateEmail($db, $id, $newEmail);
    }

    public static function validateNewEmail($newEmail, $table)
    {
        if (!isset($newEmail) || empty($newEmail)) throw new Exception("Email is required");
        if (strcmp($newEmail, $table) === 0) return;

        if (filter_var($newEmail, FILTER_VALIDATE_EMAIL) === false) {
            throw new Exception("Please enter a valid email address");
        } else if (StudentFetcher::existsEmail($db, $newEmail)) {
            throw new Exception('That email already exists. Please use another one.');
        } // end else if
    }

    public static function validateIds($ids)
    {
        $counts = array_count_values($ids);
        foreach ($counts as $name => $count) {
            if ($count > 1) throw new Exception("Duplicate students inserted.");

        }

        foreach ($ids as $id) {
            self::validateId($id);
        }
    }

    public static function validateId($id)
    {
        if (is_null($id) || (!preg_match("/^[0-9]+$/", $id))) {
            throw new Exception("Data has been tempered. Aborting process.");
        }

        if (!StudentFetcher::exists(StudentFetcher::DB_COLUMN_ID, $id, PDO::PARAM_INT)) {
            // TODO: sent email to developer relevant to this error.
            throw new Exception("Either something went wrong with a database query, or you're trying to hack this app. In either case, the developers were just notified about this.");
        }
    }

    public static function updateStudentId($db, $id, $newStudentId, $oldStudentId)
    {
        if (strcmp($newStudentId, $oldStudentId) === 0) return;
        Student::validateStudentId($db, $newStudentId);

        StudentFetcher::updateStudentId($db, $id, $newStudentId);
    }


    public static function updateCredits($db, $id, $newCreditsNum, $oldCredits)
    {
        if (strcmp($newCreditsNum, $oldCredits) === 0) return;
        Student::validateCredits($newCreditsNum);

        StudentFetcher::updateCredits($db, $id, $newCreditsNum);
    }

    /**
     * @return mixed
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * @return mixed
     */
    public function getCredits()
    {
        return $this->credits;
    }

    /**
     * @return mixed
     */
    public function getCi()
    {
        return $this->ci;
    }

    /**
     * @param mixed $ci
     */
    public function setCi($ci)
    {
        $this->ci = $ci;
    }


}