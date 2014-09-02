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

} 