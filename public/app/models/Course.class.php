<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 9/2/2014
 * Time: 8:28 AM
 */
class Course
{
    const NULL = "null";

    public static function validateSingle($db, $courseId) {
        if (!isset($courseId) || empty($courseId)) throw new Exception("Data has been tempered. Aborting process.");
        if (strcmp($courseId, self::NULL) === 0) throw new Exception("Course is required.");
        if (!CourseFetcher::courseExists($db, $courseId)) throw new Exception("Data has been tempered. Aborting process");

        return true;
    }

    public static function create($db, $courseCode, $courseName) {
        $courseCode = self::validateCode($db, $courseCode);
        $courseName = self::validateName($db, $courseName);
        CourseFetcher::insert($db, $courseCode, $courseName);
    }

    public static function update($db, $courseId, $newCourseCode, $newCourseName) {
        $newCourseName = self::validateName($db, $newCourseName);
        $courseId = self::validateId($db, $courseId);
    }

    public static function updateCode($db, $id, $newCode) {
        $newCode = self::validateCode($db, $newCode);
        CourseFetcher::updateCode($db, $id, $newCode);
    }

    public static function updateName($db, $id, $newName) {
        $newName = self::validateName($db, $newName);
        CourseFetcher::updateName($db, $id, $newName);
    }

    public static function validateId($db, $id) {
        $id = trim($id);
        if (!preg_match('/^[0-9]+$/', $id) || (!CourseFetcher::idExists($db, $id))) {
            throw new Exception("Data tempering detected.
			<br/>You&#39;re trying to hack this app.<br/>Developers are being notified about this.<br/>Expect Us.");
        }

        return $id;
    }

    public static function  validateCode($db, $courseCode) {
        $courseCode = trim($courseCode);

        if (!preg_match("/^[A-Z0-9]{1,10}$/", $courseCode)) {
            throw new Exception("Course code can contain capital letters in the range of A-Z, numbers 0-9 and of length 1-10.");
        }

        if (CourseFetcher::codeExists($db, $courseCode)) {
            throw new Exception("Course code already exists on database. Please insert a different one.");
        }

        return $courseCode;
    }

    public static function  validateName($db, $courseName) {
        $courseName = trim($courseName);

        if (!preg_match("/^[\\w\\ ]{1,50}$/", $courseName)) {
            throw new Exception("Course code can only contain <a href='http://www.regular-expressions.info/shorthand.html'
            target='_blank'>word characters</a> and spaces of length 1-50");
        }

        if (CourseFetcher::nameExists($db, $courseName)) {
            throw new Exception("Course name already exists on database. Please insert a different one.");
        }

        return $courseName;
    }

    public static function delete($db, $id) {
        Person::validateId($id);
        if (!CourseFetcher::courseExists($db, $id)) {
            throw new Exception("Could not retrieve course to be deleted from database. <br/>
                Maybe some other administrator just deleted it?");
        }

        CourseFetcher::delete($db, $id);
    }

} 