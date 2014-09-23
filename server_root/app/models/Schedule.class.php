<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/19/2014
 * Time: 11:07 AM
 */
class Schedule
{
    public static function add($db, $tutorId, $termId, $repeatingDays, $timeStart, $timeEnd) {
        Tutor::validateId($db, $tutorId);
        Term::validateId($db, $termId);
        self::validateRepeatingDays($repeatingDays);

        $timeStart = self::convertDate($timeStart);
        $timeEnd = self::convertDate($timeEnd);

        $appointmentId = ScheduleFetcher::insert($db, $tutorId, $termId, $repeatingDays, $timeStart, $timeEnd);
        // TODO: add option for admin to check if he wants an automatic email to be said on said user on his email
        return $appointmentId;
    }

    public static function validateRepeatingDays($repeatingDays) {
        if (!isset($repeatingDays)) throw new Exception("At least one day is required.");
        foreach ($repeatingDays as $repeatingDay) {
            if (!preg_match("/^[1-5]$/", $repeatingDay)) throw new Exception(ErrorMessages::getHacking());
        }
    }

    /**
     * @param $timeStart
     * @return DateTime|string
     * @throws Exception
     */
    public static function convertDate($timeStart) {
        date_default_timezone_set('Europe/Athens');
        try {
            $timeStart = new DateTime($timeStart);
            $timeStart = $timeStart->format('Y-m-d H:i:s');

            return $timeStart;
        } catch (Exception $e) {
            throw new Exception("Date have been malformed" . $e->getMessage());
        }
        return $timeStart;
    }

    public static function validateDate($db, $date, $tutorId) {
        $date = Dates::initDateTime($date);
//maybe in 	existDatesBetween you should consider also termId as a parameter?
//		if (ScheduleFetcher::existDatesBetween($db, $dateStart, $dateEnd, $tutorId)) {
//			throw new Exception("Sorry, the days/hours you inputted conflict with existing working hours.");
//		}
        return $date;
    }

    public static function updateStartingDate($db, $id, $newStartingDate, $oldStartingDate) {
        if (strcmp($newStartingDate, $oldStartingDate) === 0) return false;

        Dates::initDateTime($newStartingDate, $oldStartingDate);
        ScheduleFetcher::updateStartingDate($db, $id, $newStartingDate);

        return true;
    }

    public static function updateEndingDate($db, $id, $newEndingDate, $oldEndingDate) {
        if (strcmp($newEndingDate, $oldEndingDate) === 0) return false;

        Dates::initDateTime($newEndingDate, $oldEndingDate);
        ScheduleFetcher::updateSingleColumn($db, $id, ScheduleFetcher::DB_COLUMN_END_TIME, $newEndingDate, PDO::PARAM_STR);
        return true;
    }

    public static function delete($db, $id) {
        self::validateId($db, $id);
        if (!ScheduleFetcher::idExists($db, $id)) {
            throw new Exception("Could not retrieve schedule to be deleted from database. <br/>
                Maybe some other administrator just deleted it?");
        }

        ScheduleFetcher::delete($db, $id);
    }

    public static function validateId($db, $id) {
        if (is_null($id) || !preg_match("/^[0-9]+$/", $id)) {
            throw new Exception("Data has been tempered. Aborting process.");
        }

        if (!ScheduleFetcher::existsId($db, $id)) {
            // TODO: sent email to developer relevant to this error.
            throw new Exception("Either something went wrong with a database query, or you're trying to hack this app. In either case, the developers were just notified about this.");

        }
    }

    public static function getSingleTutor($db, $tutorId, $termId) {
        Tutor::validateId($db, $tutorId);
        Term::validateId($db, $termId);
        return ScheduleFetcher::retrieveSingleTutor($db, $tutorId, $termId);
    }

    public static function getTutorsOnTerm($db, $termId) {
        Term::validateId($db, $termId);
        return ScheduleFetcher::retrieveTutorsOnTerm($db, $termId);
    }

}
