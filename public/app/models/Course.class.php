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

    public static function createCourse($db, $courseCode, $courseName) {
        $courseCode = self::validateCode($db, $courseCode);
        $courseName = self::validateCourseName($db, $courseName);
        CourseFetcher::insert($db, $courseCode, $courseName);
    }

    public static function  validateCode($db, $courseCode) {
        $courseCode = trim($courseCode);

        if (!preg_match("/^[A-Z0-9]{1,10}$/", $courseCode)) {
            throw new Exception("Course code can contain characters in the range of A-Z, 0-9 and of length 1-10.");
        }

        if (CourseFetcher::codeExists($db, $courseCode)) {
            throw new Exception("Course code already exists on database. Please insert a different one.");
        }

        return $courseCode;
    }

    public static function  validateCourseName($db, $courseName) {
        $courseName = trim($courseName);

        if (!preg_match("/^[\\w\\ ]{1,50}$/", $courseName)) {
            throw new Exception("Course code can only contain <a href='http://www.regular-expressions.info/shorthand.html'
            target='_blank'>word characters</a> and spaces of length 1-50");
        }

        if (CourseFetcher::nameExists($db, $courseName)) {
            throw new Exception("Course code already exists on database. Please insert a different one.");
        }

        return $courseName;
    }

} 