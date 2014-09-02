<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 9/2/2014
 * Time: 10:50 AM
 */
class Major
{

    const DB_TABLE = "major";
    const DB_COLUMN_ID = "id";
    const DB_COLUMN_EXTENSIONS = "extension";

    public static function validate($db, $majorId) {
        if (!preg_match("/^[0-9]+$/", $majorId)) {
            throw new Exception("Data has been tempered. Aborting process.");
        }

        $query = "SELECT COUNT(`" . Major::DB_COLUMN_ID . "`)  FROM `" . DB_NAME . "`.`" . Major::DB_TABLE . "`
            WHERE `" . Major::DB_TABLE . "`.`" . Major::DB_COLUMN_EXTENSIONS . "` = :extension";
        $query = $db->getDbConnection()->prepare($query);
        $query->bindParam(':extension', $majorId);

        try {

            $query->execute();
            $data = $query->fetch();
        } catch (Exception $e) {
            throw new Exception("Could not connect to database.");
        }

        if ($data === 1) {
            return true;
        } else {
            // TODO: sent email to developer relavant to this error.
            throw new Exception("Either something went wrong with a database query, or you're trying to hack this app. In either case, the developers were just notified about this.");
        }
    }
} 