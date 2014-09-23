<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/14/2014
 * Time: 4:57 AM
 */
class TutorFetcher
{
    const DB_TABLE = "tutor";
    const DB_COLUMN_MAJOR_ID = "major_id";
    const DB_COLUMN_USER_ID = "user_id";

    public static function insertMajor($db, $id, $majorId)
    {

        try {
            $query = "INSERT INTO `" . DB_NAME . "`.`" . self::DB_TABLE . "`
				(`" . self::DB_COLUMN_USER_ID . "`, `" . self::DB_COLUMN_MAJOR_ID . "`) VALUES (:user_id, :major_id)";
            $query = $db->getConnection()->prepare($query);
            $query->bindParam(':user_id', $id, PDO::PARAM_INT);
            $query->bindParam(':major_id', $majorId, PDO::PARAM_INT);
            $query->execute();


            return true;
        } catch (Exception $e) {
            throw new Exception("Could not insert teaching major data into database.");
        }
    }

    public static function retrieveCurrTermTeachingCourses($db, $id)
    {
        $query = "SELECT `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "`,
		`" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_CODE . "` , `" . CourseFetcher::DB_TABLE . "`.`" .
            CourseFetcher::DB_COLUMN_NAME . "`,	`" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_NAME . "` AS
						" . TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_NAME . "
			FROM `" . DB_NAME . "`.`" . CourseFetcher::DB_TABLE . "`
			INNER JOIN `" . DB_NAME . "`.`" . Tutor_has_course_has_termFetcher::DB_TABLE . "`
				ON `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "` = `" .
            Tutor_has_course_has_termFetcher::DB_TABLE . "`.`" . Tutor_has_course_has_termFetcher::DB_COLUMN_COURSE_ID . "`
			INNER JOIN `" . TermFetcher::DB_TABLE . "`
				ON `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "` = `" .
            Tutor_has_course_has_termFetcher::DB_TABLE . "`.`" . Tutor_has_course_has_termFetcher::DB_COLUMN_TERM_ID . "`
			WHERE `" . Tutor_has_course_has_termFetcher::DB_COLUMN_TUTOR_USER_ID . "` = :tutorId
			AND
			(NOW() BETWEEN `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_START_DATE . "` AND `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_END_DATE . "`)";

        try {
            $query = $db->getConnection()->prepare($query);
            $query->bindParam(':tutorId', $id, PDO::PARAM_INT);

            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new Exception("Could not retrieve teaching courses data from database.");
        }
    }

    public static function retrieveAllAppointments($db, $id)
    {
        $query =
            "SELECT `" . AppointmentFetcher::DB_COLUMN_START_TIME . "`, `" . AppointmentFetcher::DB_COLUMN_END_TIME . "`,
			`" . AppointmentFetcher::DB_COLUMN_COURSE_ID . "`, `" . AppointmentFetcher::DB_COLUMN_TUTOR_USER_ID . "`,
			`" . AppointmentFetcher::DB_COLUMN_TERM_ID . "`
				FROM `" . DB_NAME . "`.`" . AppointmentFetcher::DB_TABLE . "`
				WHERE `" . AppointmentFetcher::DB_COLUMN_TUTOR_USER_ID . "`=:id";

        try {
            $query = $db->getConnection()->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Something terrible happened . Could not retrieve data from database.");
            // end catch

            return null;
        }
    }

    public static function hasAppointmentWithId($db, $tutorId, $appointmentId)
    {
        try {
            $sql = "SELECT COUNT(" . AppointmentFetcher::DB_COLUMN_ID . ")
            FROM `" . DB_NAME . "`.`" . AppointmentFetcher::DB_TABLE . "`
            WHERE `" . AppointmentFetcher::DB_COLUMN_TUTOR_USER_ID . "` = :tutor_id
            AND  `" . AppointmentFetcher::DB_COLUMN_ID . "` = :appointment_id";
            $query = $db->getConnection()->prepare($sql);
            $query->bindParam(':tutor_id', $tutorId, PDO::PARAM_INT);
            $query->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);

            $query->execute();

            if ($query->fetchColumn() === '0') return false;
        } catch (Exception $e) {
            throw new Exception("Could not check if tutor id already exists on database.");
        }

        return true;
    }

    public static function replaceMajorId($db, $id, $newMajorId)
    {
        $query = "UPDATE `" . DB_NAME . "`.`" . self::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_MAJOR_ID . "`= :major_id
					WHERE `" . self::DB_COLUMN_USER_ID . "`= :id";

        try {
            $query = $db->getConnection()->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':major_id', $newMajorId, PDO::PARAM_INT);
            $query->execute();

            return true;
        } catch (Exception $e) {
            throw new Exception("Something terrible happened. Could not update first name");
        }
    }


    public static function retrieveSingle($db, $id)
    {
        $query =
            "SELECT `" . self::DB_COLUMN_MAJOR_ID . "`, `" . self::DB_COLUMN_USER_ID . "`, `" . MajorFetcher::DB_TABLE .
            "`.`" . MajorFetcher::DB_COLUMN_CODE . "`,`" . MajorFetcher::DB_TABLE . "`.`" . MajorFetcher::DB_COLUMN_NAME . "`
		FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
		INNER JOIN `" . MajorFetcher::DB_TABLE . "`
			ON `" . MajorFetcher::DB_TABLE . "`.`" . MajorFetcher::DB_COLUMN_ID . "` = `" . TutorFetcher::DB_TABLE . "`.`"
            . TutorFetcher::DB_COLUMN_MAJOR_ID . "`
		WHERE `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_USER_ID . "`=:id";

        try {
            $query = $db->getConnection()->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);

            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Something terrible happened . Could not retrieve tutor data from database." . $e->getMessage());
        } // end catch
    }

    public static function retrieveAll($db)
    {
        $query =
            "SELECT `" . UserFetcher::DB_COLUMN_FIRST_NAME . "`, `" . UserFetcher::DB_COLUMN_LAST_NAME . "`,  `" .
            self::DB_COLUMN_USER_ID . "`
				FROM `" . DB_NAME . "`.`" . UserFetcher::DB_TABLE . "`
				INNER JOIN `" . self::DB_TABLE . "`
				ON `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_USER_ID . "`=`" . UserFetcher::DB_TABLE . "`.`" .
            UserFetcher::DB_COLUMN_ID . "`";

        try {
            $query = $db->getConnection()->prepare($query);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Something terrible happened . Could not data from database .: ");
        } // end catch
    }

    public static function existsUserId($db, $id)
    {
        try {
            $sql = "SELECT COUNT(" . self::DB_COLUMN_USER_ID . ") FROM `" . DB_NAME . "`.`" .
                self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_USER_ID . "` = :user_id";
            $query = $db->getConnection()->prepare($sql);
            $query->bindParam(':user_id', $id, PDO::PARAM_INT);
            $query->execute();

            if ($query->fetchColumn() === '0') return false;
        } catch (Exception $e) {
            throw new Exception("Could not check if tutor id already exists on database.");
        }

        return true;
    }

} 