<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/19/2014
 * Time: 11:16 AM
 */
class ScheduleFetcher
{

    const DB_TABLE = "work_week_hours";
    const DB_COLUMN_ID = "id";
    const DB_COLUMN_START_TIME = "start";
    const DB_COLUMN_END_TIME = "end";
    const DB_COLUMN_TERM_ID = "term_id";
    const DB_COLUMN_TUTOR_USER_ID = "tutor_user_id";
    const DB_COLUMN_MONDAY = "monday";
    const DB_COLUMN_TUESDAY = "tuesday";
    const DB_COLUMN_WEDNESDAY = "wednesday";
    const DB_COLUMN_THURSDAY = "thursday";
    const DB_COLUMN_FRIDAY = "friday";


    public static function insert($db, $tutorId, $termId, $repeatingDays, $timeStart, $timeEnd) {
        $monday = $tuesday = $wednesday = $thursday = $friday = 0;

        foreach ($repeatingDays as $repeatingDay) {
            switch ($repeatingDay) {
                case '1':
                    $monday = 1;
                    break;
                case '2':
                    $tuesday = 1;
                    break;
                case '3':
                    $wednesday = 1;
                    break;
                case '4':
                    $thursday = 1;
                    break;
                case '5':
                    $friday = 1;
                    break;
            }
        }

        try {
            $queryInsertUser = "INSERT INTO `" . DB_NAME . "`.`" . self::DB_TABLE . "`
            (`" . self::DB_COLUMN_START_TIME . "`, `" . self::DB_COLUMN_END_TIME . "`, `" . self::DB_COLUMN_TERM_ID . "`,
             `" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_MONDAY . "`, `" . self::DB_COLUMN_TUESDAY . "`,
             `" . self::DB_COLUMN_WEDNESDAY . "`,`" . self::DB_COLUMN_THURSDAY . "`,`" . self::DB_COLUMN_FRIDAY . "`)
				VALUES(
					:start,
					:end,
					:term_id,
					:tutor_user_id,
					:monday,
					:tuesday,
					:wednesday,
					:thursday,
					:friday
				)";


            $queryInsertUser = $db->getConnection()->prepare($queryInsertUser);
            $queryInsertUser->bindParam(':start', $timeStart, PDO::PARAM_STR);
            $queryInsertUser->bindParam(':end', $timeEnd, PDO::PARAM_STR);
            $queryInsertUser->bindParam(':tutor_user_id', $tutorId, PDO::PARAM_INT);
            $queryInsertUser->bindParam(':term_id', $termId, PDO::PARAM_INT);
            $queryInsertUser->bindParam(':monday', $monday, PDO::PARAM_INT);
            $queryInsertUser->bindParam(':tuesday', $tuesday, PDO::PARAM_INT);
            $queryInsertUser->bindParam(':wednesday', $wednesday, PDO::PARAM_INT);
            $queryInsertUser->bindParam(':thursday', $thursday, PDO::PARAM_INT);
            $queryInsertUser->bindParam(':friday', $friday, PDO::PARAM_INT);

            $queryInsertUser->execute();

            // last inserted if of THIS connection
            return $appointmentId = $db->getConnection()->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Could not insert data into database." . $e->getMessage());
        }

    }


    public static function existsId($db, $id) {
        try {
            $sql = "SELECT COUNT(" . self::DB_COLUMN_ID . ") FROM `" . DB_NAME . "`.`" .
                self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :id";
            $query = $db->getConnection()->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();

            if ($query->fetchColumn() === '0') return false;
        } catch (Exception $e) {
            throw new Exception("Could not check data already exists on database.");
        }

        return true;
    }


    public static function retrieveAll($db) {
        $query =
            "SELECT `" . self::DB_COLUMN_START_TIME . "`, `" . self::DB_COLUMN_END_TIME . "`, `" .
            self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_TERM_ID . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "` order by `" .
            self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "` DESC";

        try {
            $query = $db->getConnection()->prepare($query);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Could not retrieve data from database.");
        }
    }

    public static function retrieveTutors($db) {
        $query =
            "SELECT `" . TermFetcher::DB_COLUMN_NAME . "`, `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`, `" . self::DB_COLUMN_START_TIME . "`, `" .
            self::DB_COLUMN_END_TIME . "`, `" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_TERM_ID . "`,
			`" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_FIRST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`"
            . UserFetcher::DB_COLUMN_LAST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`  AS
			" . UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_ID . "
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DB_NAME . "`.`" . UserFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`  = `" .
            UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DB_NAME . "`.`" . TermFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`  = `" .
            TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`
			WHERE CURRENT_TIMESTAMP() BETWEEN `" . TermFetcher::DB_COLUMN_START_DATE . "` AND `" . TermFetcher::DB_COLUMN_END_DATE . "`";


        try {
            $query = $db->getConnection()->prepare($query);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Could not retrieve data from database.");
        }
    }

    public static function retrieveTutorsOnTerm($db, $termId) {
        $query =
            "SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`, `" . self::DB_COLUMN_START_TIME . "`, `" .
            self::DB_COLUMN_END_TIME . "`, `" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_TERM_ID . "`,
			`" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_FIRST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`"
            . UserFetcher::DB_COLUMN_LAST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`  AS
			" . UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_ID . "
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DB_NAME . "`.`" . UserFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`  = `" .
            UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DB_NAME . "`.`" . TermFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`  = `" .
            TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`
			WHERE `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "` = :term_id";

        try {
            $query = $db->getConnection()->prepare($query);
            $query->bindParam(':term_id', $termId, PDO::PARAM_INT);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Could not retrieve data from database.");
        }
    }

    public static function retrieveTutorsOnCurrentTerms($db) {
        $query =
            "SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`, `" . self::DB_COLUMN_START_TIME . "`,
            `" . self::DB_COLUMN_END_TIME . "`,	`" . self::DB_COLUMN_TUTOR_USER_ID . "`,`" . self::DB_COLUMN_TERM_ID . "`,
			`" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_FIRST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_LAST_NAME . "`,
			`" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`  AS
			" . UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_ID . ",
			`" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_NAME . "`,
			`" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`  AS
			" . TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_ID . ", `" . self::DB_COLUMN_MONDAY . "`, `" .
            self::DB_COLUMN_TUESDAY . "`, `" . self::DB_COLUMN_WEDNESDAY . "`,`" . self::DB_COLUMN_THURSDAY . "`,`" .
            self::DB_COLUMN_FRIDAY . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DB_NAME . "`.`" . UserFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`  = `" .
            UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DB_NAME . "`.`" . TermFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`  = `" .
            TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`
			WHERE CURRENT_TIMESTAMP() BETWEEN `" . TermFetcher::DB_COLUMN_START_DATE . "` AND `" . TermFetcher::DB_COLUMN_END_DATE . "`";

        try {
            $query = $db->getConnection()->prepare($query);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Could not retrieve data from database.");
        }
    }


    public static function retrieveSingleTutor($db, $tutorId, $termId) {
        $query =
            "SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`, `" . self::DB_COLUMN_START_TIME . "`, `" .
            self::DB_COLUMN_END_TIME . "`, `" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_TERM_ID . "`,
			`" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_FIRST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`"
            . UserFetcher::DB_COLUMN_LAST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`  AS
			" . UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_ID . ", `" . TermFetcher::DB_TABLE . "`.`" .
            TermFetcher::DB_COLUMN_START_DATE . "` AS " . TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_START_DATE
            . ", `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_END_DATE . "`  AS " . TermFetcher::DB_TABLE
            . "_" . TermFetcher::DB_COLUMN_END_DATE . "
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DB_NAME . "`.`" . UserFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`  = `" .
            UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DB_NAME . "`.`" . TermFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`  = `" .
            TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`
			WHERE `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "` = :tutor_id
			AND `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "` = :term_id
			ORDER BY `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "` DESC";

        try {
            $query = $db->getConnection()->prepare($query);
            $query->bindParam(':tutor_id', $tutorId, PDO::PARAM_INT);
            $query->bindParam(':term_id', $termId, PDO::PARAM_INT);

            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Could not retrieve data from database.");
        }
    }

    public static function retrieveWorkingHours($db, $tutorId, $termId) {
        $query =
            "SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`, `" . self::DB_COLUMN_START_TIME . "`, `" .
            self::DB_COLUMN_END_TIME . "`, `" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_TERM_ID . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`=:tutor_id
			AND `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`=:term_id";

        try {
            $query = $db->getConnection()->prepare($query);
            $query->bindParam(':tutor_id', $tutorId, PDO::PARAM_INT);
            $query->bindParam(':term_id', $termId, PDO::PARAM_INT);

            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Could not retrieve data from database.");
        }
    }

    public static function retrieveSingle($db, $id) {
        $query = "SELECT  `" . self::DB_COLUMN_ID . "` ,
						  `" . self::DB_COLUMN_TERM_ID . "`, 
						  `" . self::DB_COLUMN_TUTOR_USER_ID . "` , 
						  `" . self::DB_COLUMN_START_TIME . "`,
						  `" . self::DB_COLUMN_END_TIME . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_ID . "`=:id";

        try {
            $query = $db->getConnection()->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);

            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Could not retrieve data from database .: ");
        } // end catch
    }

    public static function  updateStartingDate($db, $id, $newStartingDate) {
        $query = "UPDATE `" . DB_NAME . "`.`" . self::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_START_TIME . "`= :newName
					WHERE `id`= :id";

        try {
            $query = $db->getConnection()->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':newName', $newStartingDate, PDO::PARAM_STR);
            $query->execute();

            return true;
        } catch (Exception $e) {
            throw new Exception("Something terrible happened. Could not update starting time.");
        }
    }

    public static function updateSingleColumn($db, $id, $column, $value, $valueType) {
        $query = "UPDATE `" . DB_NAME . "`.`" . self::DB_TABLE . "`
					SET	`" . $column . "`= :column
					WHERE `id`= :id";

        try {
            $query = $db->getConnection()->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':column', $value, $valueType);
            $query->execute();

            return true;
        } catch (Exception $e) {
            throw new Exception("Something went wrong. Could not update schedules table.");
        }
    }

    public static function idExists($db, $id) {
        try {
            $sql = "SELECT COUNT(" . self::DB_COLUMN_ID . ") FROM `" . DB_NAME . "`.`" .
                self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :id";
            $query = $db->getConnection()->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();

            if ($query->fetchColumn() === 0) return false;
        } catch (Exception $e) {
            throw new Exception("Could not check if schedule id already exists on database. <br/> Aborting process.");
        }

        return true;
    }

    public static function delete($db, $id) {
        try {
            $query = "DELETE FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :id";
            $query = $db->getConnection()->prepare($query);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return true;
        } catch (Exception $e) {
            throw new Exception("Could not delete schedule from database.");
        }
    }


    /**
     * NEEDS TESTING
     * @param $db
     * @param $dateStart
     * @param $dateEnd
     * @param $tutorId
     * @return bool
     * @throws Exception
     */
    public static function existDatesBetween($db, $dateStart, $dateEnd, $tutorId) {
        date_default_timezone_set('Europe/Athens');
        $dateStart = $dateStart->format(Dates::DATE_FORMAT_IN);
        $dateEnd = $dateEnd->format(Dates::DATE_FORMAT_IN);

        $query = "SELECT COUNT(`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`),`" . CourseFetcher::DB_TABLE . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_TUTOR_USER_ID . "` = :tutor_id
			AND(`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "`  BETWEEN $dateStart AND $dateEnd)";


        try {
            $query = $db->getConnection()->prepare($query);
            $query->bindParam(':tutor_id', $tutorId, PDO::PARAM_INT);

            $query->execute();
            if ($query->fetchColumn() === '0') return false;

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Could not retrieve teaching courses data from database." . $e->getMessage());
        }
        return true;
    }
}