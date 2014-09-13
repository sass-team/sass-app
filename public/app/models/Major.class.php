<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 9/2/2014
 * Time: 10:50 AM
 */
class Major
{

    // const DB_TABLE = "major";
    // const DB_COLUMN_ID = "id";
    // const DB_COLUMN_EXTENSIONS = "extension";

    // public static function validate($db, $majorId) {
    //     if (!preg_match("/^[0-9]+$/", $majorId)) {
    //         throw new Exception("Data has been tempered. Aborting process.");
    //     }

    //     $query = "SELECT COUNT(`" . Major::DB_COLUMN_ID . "`)  FROM `" . DB_NAME . "`.`" . Major::DB_TABLE . "`
    //         WHERE `" . Major::DB_TABLE . "`.`" . Major::DB_COLUMN_EXTENSIONS . "` = :extension";
    //     $query = $db->getDbConnection()->prepare($query);
    //     $query->bindParam(':extension', $majorId);

    //     try {

    //         $query->execute();
    //         $data = $query->fetch();
    //     } catch (Exception $e) {
    //         throw new Exception("Could not connect to database.");
    //     }

    //     if ($data === 1) {
    //         return true;
    //     } else {
    //         // TODO: sent email to developer relavant to this error.
    //         throw new Exception("Either something went wrong with a database query, or you're trying to hack this app. In either case, the developers were just notified about this.");
    //     }
    // }

    const NULL = "null";

    public static function validateSingle($db, $majorId) {
        if (!isset($majorId) || empty($majorId)) throw new Exception("Data has been tempered. Aborting process.");
        if (strcmp($majorId, self::NULL) === 0) throw new Exception("Major is required.");
        if (!MajorFetcher::majorExists($db, $majorId)) throw new Exception("Data has been tempered. Aborting process");

        return true;
    }

    public static function create($db, $majorCode, $majorName) {
        $majorCode = self::validateCode($db, $majorCode);
        $majorName = self::validateName($db, $majorName);
        MajorFetcher::insert($db, $majorCode, $majorName);
    }

    public static function update($db, $majorId, $newMajorCode, $newMajorName) {
        $newMajorName = self::validateName($db, $newMajorName);
        $majorId = self::validateId($db, $majorId);
    }

    public static function updateCode($db, $id, $newCode) {
        $newCode = self::validateCode($db, $newCode);
        MajorFetcher::updateCode($db, $id, $newCode);
    }

    public static function updateName($db, $id, $newName) {
        $newName = self::validateName($db, $newName);
        MajorFetcher::updateName($db, $id, $newName);
    }

    public static function validateId($db, $id) {
        $id = trim($id);
        if (!preg_match('/^[0-9]+$/', $id) || (!MajorFetcher::idExists($db, $id))) {
            throw new Exception("Data tempering detected.
            <br/>You&#39;re trying to hack this app.<br/>Developers are being notified about this.<br/>Expect Us.");
        }

        return $id;
    }

    public static function  validateCode($db, $majorCode) {
        $majorCode = trim($majorCode);

        if (!preg_match("/^[A-Z]{1,10}$/", $majorCode)) {
            throw new Exception("Major code can only contain capital letters in the range of A-Z and of length 1-10.");
        }

        if (MajorFetcher::codeExists($db, $majorCode)) {
            throw new Exception("Major code already exists on database. Please insert a different one.");
        }

        return $majorCode;
    }

    public static function  validateName($db, $majorName) {
        $majorName = trim($majorName);

        if (!preg_match("/^[\\w\\ ]{1,50}$/", $majorName)) {
            throw new Exception("Major name can only contain <a href='http://www.regular-expressions.info/shorthand.html'
            target='_blank'>word characters</a> and spaces of length 1-50");
        }

        if (MajorFetcher::nameExists($db, $majorName)) {
            throw new Exception("Course name already exists on database. Please insert a different one.");
        }

        return $majorName;
    }

    public static function delete($db, $id) {
        Person::validateId($id);
        if (!MajorFetcher::majorExists($db, $id)) {
            throw new Exception("Could not retrieve major to be deleted from database. <br/>
                Maybe some other administrator just deleted it?");
        }

        MajorFetcher::delete($db, $id);
    }
} 