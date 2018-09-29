<?php

namespace Tests;

require __DIR__ . '/../public_html/app/init.php';

use Faker\Factory;
use Major;
use PHPUnit_Framework_TestCase;
use Tutor;

/**
 * @author  Rizart Dokollari <r.dokollari@gmail.com>
 * @since   2/14/18
 */
class TestCase extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $_SERVER['SERVER_NAME'] = 'sass';

        putenv("env=testing");
    }

    protected function generateTutor()
    {
        $factory = Factory::create();

        $major = $this->generateMajor();

        return new Tutor(
            $factory->uuid,
            $factory->firstName,
            $factory->lastName,
            'r.dokollari@gmail.com',
            $factory->phoneNumber,
            $factory->image(),
            $factory->sentence,
            $factory->dateTime(),
            $factory->word,
            $factory->boolean(),
            $major->getId()
        );
    }

    private function generateMajor()
    {
        $factory = Factory::create();

        return new Major($factory->uuid, $factory->word, $factory->word);
    }
}