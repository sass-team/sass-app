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

    public function  __construct( $id, $firstName, $lastName, $email, $mobileNum, $ci, $credits, $major)
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

    public static function add( $firstName, $lastName, $email, $mobileNum, $courses, $ci, $credits)
    {

    }

    /**
     * @param $db
     * @throws Exception
     */
    public static function retrieve()
    {
        $query = "SELECT id, email, f_name, l_name, mobile, ci, credits
		         FROM `" . App::$dsn[App::DB_NAME] . "`.student";


        try {
	        $dbConnection = DatabaseManager::getConnection();
	        $query = $dbConnection->prepare($query);
            $query->execute();
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);

            return $rows;
        } catch (PDOException $e) {
            throw new Exception("Something terrible happened. Could not retrieve students data from database.: ");
        } // end catch
    }


    public static function create( $firstName, $lastName, $email, $studentId, $mobileNum, $majorId, $ci, $credits)
    {
        // Validate data
        Person::validateName($firstName);
        Person::validateName($lastName);
        Person::validateNewEmail($email, StudentFetcher::DB_TABLE);
        self::validateStudentId( $studentId);
        self::validateMobileNumber( $mobileNum);
        Major::validateId($majorId);

        $ci = Student::validateCi($ci);
        $credits = Student::validateCredits($credits);

        // Insert into database
        StudentFetcher::insert($firstName, $lastName, $email, $studentId, $mobileNum, $majorId, $ci, $credits);
    }

    public static function validateStudentId( $studentId)
    {
        if (!preg_match("/^[0-9]{6,7}$/", $studentId)) {
            throw new Exception("Student id can contain only numbers of length 6 to 7");
        }

        if (StudentFetcher::existsStudentId($studentId)) {
            throw new Exception("Student id entered already exists in database."); // the array of errors messages
        }
    }

    /**
     * @param $db
     * @param $newMobileNum
     * @return null
     * @throws Exception
     */
    public static function validateMobileNumber( $newMobileNum)
    {
        if (empty($newMobileNum) === TRUE) {
            throw new Exception('Mobile number is required.');
        }

        if (!preg_match('/^[0-9]{10}$/', $newMobileNum)) {
            throw new Exception('Mobile number should contain only digits of total length 10');
        }

        if (StudentFetcher::existsMobileNum($newMobileNum)) {
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

    public static function updateFirstName( $id, $newName, $oldName)
    {

        if (strcmp($newName, $oldName) === 0) return;

        self::validateName($newName);
        StudentFetcher::updateFirstName($id, $newName);
    }

    public static function updateLastName( $id, $newName, $oldName)
    {
        if (strcmp($newName, $oldName) === 0) return;

        self::validateName($newName);
        StudentFetcher::updateLastName($id, $newName);
    }

    public static function updateMobileNum( $id, $newMobileNum, $oldMobileNum)
    {
        if (strcmp($newMobileNum, $oldMobileNum) === 0) return;

        self::validateMobileNumber( $newMobileNum);
        StudentFetcher::updateMobileNum($id, $newMobileNum);
    }

    public static function updateMajorId( $id, $newMajorId, $oldMajorId)
    {
        if (!isset($newMajorId) || empty($newMajorId)) throw new Exception("Data tempering detected. Aborting.");
        if (strcmp($newMajorId, $oldMajorId) === 0) return;

        Major::validateId($newMajorId);
        StudentFetcher::updateMajorId($id, $newMajorId);
    }

    public static function updateCi( $id, $newCi, $oldCi)
    {
        if (strcmp($newCi, $oldCi) === 0) return;

        $newCi = self::validateCi($newCi);
        StudentFetcher::updateCi($id, $newCi);
    }

    public static function updateEmail( $id, $newEmail, $oldEmail)
    {
        Student::validateNewEmail($newEmail, $oldEmail);
        StudentFetcher::updateEmail($id, $newEmail);
    }

    public static function validateNewEmail($newEmail, $table)
    {
        if (!isset($newEmail) || empty($newEmail)) throw new Exception("Email is required");
        if (strcmp($newEmail, $table) === 0) return;

        if (filter_var($newEmail, FILTER_VALIDATE_EMAIL) === false) {
            throw new Exception("Please enter a valid email address");
        } else if (StudentFetcher::existsEmail($newEmail)) {
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

    public static function updateStudentId( $id, $newStudentId, $oldStudentId)
    {
        if (strcmp($newStudentId, $oldStudentId) === 0) return;
        Student::validateStudentId( $newStudentId);

        StudentFetcher::updateStudentId($id, $newStudentId);
    }


    public static function updateCredits( $id, $newCreditsNum, $oldCredits)
    {
        if (strcmp($newCreditsNum, $oldCredits) === 0) return;
        Student::validateCredits($newCreditsNum);

        StudentFetcher::updateCredits($id, $newCreditsNum);
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