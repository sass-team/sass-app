<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 9/2/2014
 * Time: 10:50 AM
 */
class Major
{



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
        if (is_null($id)) throw new Exception("Major is required.");

        if (!preg_match("/^[0-9]+$/", $id)) {
            throw new Exception("Data has been tempered. Aborting process.");
        }

        if (!MajorFetcher::idExists($db, $id)) {
            // TODO: sent email to developer relevant to this error.
            throw new Exception("Either something went wrong with a database query, or you're trying to hack this app. In either case, the developers were just notified about this.");
        }
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
        if (!preg_match("/^[a-zA-Z\\ ]{1,50}$/", $majorName)) {
            throw new Exception("Major name can only contain <a href='http://www.regular-expressions.info/shorthand.html'
            target='_blank'>word characters</a> and spaces of length 1-50");
        }

        if (MajorFetcher::nameExists($db, $majorName)) {
            throw new Exception("Course name already exists on database. Please insert a different one.");
        }

        return $majorName;
    }



    public static function delete($db, $id) {
        self::validateId($db, $id);
        MajorFetcher::delete($db, $id);
    }

} 

