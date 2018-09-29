<?php

class Major
{
    private $code;
    private $name;
    private $id;

    public function __construct($id, $code, $name)
    {
        $this->code = $code;
        $this->name = $name;
        $this->id = $id;
    }

    public static function create($majorCode, $majorName)
    {
        $majorCode = self::validateCode($majorCode);
        $majorName = self::validateName($majorName);
        MajorFetcher::insert($majorCode, $majorName);
    }

    public static function validateCode($majorCode)
    {
        $majorCode = trim($majorCode);

        if (!preg_match("/^[A-Z0-9]{1,10}$/", $majorCode)) {
            throw new Exception("Major code can only contain capital letters in the range of A-Z, 0-9 and of length 1-10.");
        }

        if (MajorFetcher::codeExists($majorCode)) {
            throw new Exception("Major code already exists on database. Please insert a different one.");
        }

        return $majorCode;
    }

    public static function validateName($majorName)
    {
        $majorName = trim($majorName);
        if (!preg_match("/^[a-zA-Z\\ ]{1,50}$/", $majorName)) {
            throw new Exception("Major name can only contain <a href='http://www.regular-expressions.info/shorthand.html'
            target='_blank'>word characters</a> and spaces of length 1-50");
        }

        if (MajorFetcher::nameExists($majorName)) {
            throw new Exception("Major name already exists on database. Please insert a different one.");
        }

        return $majorName;
    }

    public static function update($majorId, $newMajorCode, $newMajorName)
    {
        $newMajorName = self::validateName($newMajorName);
        $majorId = self::validateId($majorId);
    }

    public static function validateId($id)
    {
        if (is_null($id)) throw new Exception("Major is required.");

        if (!preg_match("/^[0-9]+$/", $id)) {
            throw new Exception("Data has been tempered. Aborting process.");
        }

        if (!MajorFetcher::idExists($id)) {
            // TODO: sent email to developer relevant to this error.
            throw new Exception("Either something went wrong with a database query, or you're trying to hack this app. In either case, the developers were just notified about this.");
        }
    }

    public static function updateCode($id, $newCode)
    {
        $newCode = self::validateCode($newCode);
        MajorFetcher::updateCode($id, $newCode);
    }

    public static function updateName($id, $newName)
    {
        $newName = self::validateName($newName);
        MajorFetcher::updateName($id, $newName);
    }

    public static function delete($id)
    {
        self::validateId($id);

        MajorFetcher::delete($id);
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }
}
