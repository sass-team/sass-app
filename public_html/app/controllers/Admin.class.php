<?php

class Admin extends User
{
    public function __construct($id, $firstName, $lastName, $email, $mobileNum, $avatarImgLoc, $profileDescription, $dateAccountCreated, $userType, $accountActiveStatus)
    {
        parent::__construct($id, $firstName, $lastName, $email, $mobileNum, $avatarImgLoc, $profileDescription, $dateAccountCreated, $userType, $accountActiveStatus);
    }

    public static function createUser($first_name, $last_name, $email, $user_type, $majorId, $coursesIds, $termId)
    {
        self::validateName($first_name);
        self::validateName($last_name);
        self::validateNewEmail($email, self::DB_TABLE);
        self::validateUserType($user_type);
        //$this->validate_teaching_course($teaching_courses);

        try {
            $queryInsertUser = "INSERT INTO `" . App::getDbName() . "`.`" . User::DB_TABLE . "` (`" . User::DB_COLUMN_EMAIL . "`,
			`" . User::DB_COLUMN_FIRST_NAME . "`, `" . User::DB_COLUMN_LAST_NAME . "`, `" . User::DB_COLUMN_USER_TYPES_ID . "`)
				VALUES(
					:email,
					:first_name,
					:last_name,
					(SELECT `" . UserTypesFetcher::DB_COLUMN_ID . "` FROM `" . UserTypesFetcher::DB_TABLE . "` WHERE `" .
                UserTypesFetcher::DB_COLUMN_TYPE . "`=:user_type )
				)";

            $dbConnection = DatabaseManager::getConnection();
            $dbConnection->beginTransaction();
            $queryInsertUser = $dbConnection->prepare($queryInsertUser);
            $queryInsertUser->bindParam(':email', $email, PDO::PARAM_STR);
            $queryInsertUser->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $queryInsertUser->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $queryInsertUser->bindParam(':user_type', $user_type, PDO::PARAM_STR);

            $queryInsertUser->execute();

            // last inserted if of THIS connection
            $userId = $dbConnection->lastInsertId();

            if (strcmp($user_type, User::TUTOR) === 0) {
                Major::validateId($majorId);
                Tutor::insertMajor($userId, $majorId);
                if ($coursesIds !== NULL) Tutor_has_course_has_schedule::addCourses($userId, $coursesIds, $termId);
            }

            $dbConnection->commit();

            return $userId;
        } catch (Exception $e) {
            $dbConnection->rollback();
            throw new Exception("Could not insert user into database.");
        }
    }

    public static function findByEmail($email)
    {
        $userInfo = UserFetcher::retrieveUsingEmail($email);

        return new Admin(
            $userInfo['id'],
            $userInfo['f_name'],
            $userInfo['l_name'],
            $userInfo['email'],
            $userInfo['mobile'],
            $userInfo['img_loc'],
            $userInfo['profile_description'],
            $userInfo['date'],
            $userInfo['user_types_id'],
            $userInfo['active']
        );
    }

    public function isAdmin()
    {
        return true;
    }

    /**
     * Returns a single column from the next row of a result set or FALSE if there are no more rows.
     *
     * @param $what
     * @param $field
     * @param $value
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function updateInfo($what, $field, $value, $id)
    {
        // I have only added few, but you can add more. However do not add 'password' even though the parameters will only be given by you and not the user, in our system.
        $allowed = ['id', 'username', 'f_name', 'l_name', 'email', 'COUNT(mobile)',
            'mobile', 'user', 'gen_string', 'COUNT(gen_string)', 'COUNT(id)', 'img_loc'];
        if (!in_array($what, $allowed, true) || !in_array($field, $allowed, true)) {
            throw new InvalidArgumentException;
        } else {
            try {

                $query = "UPDATE `" . App::getDbName() . "`.`" . $field . "` SET `$what` = ? WHERE `id`= ?";

                $dbConnection = DatabaseManager::getConnection();
                $query = $dbConnection->prepare($query);
                $query->bindValue(1, $value, PDO::PARAM_STR);
                $query->bindValue(2, $id, PDO::PARAM_INT);
                $query->execute();
                return true;
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }

        }
    }
}