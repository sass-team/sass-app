<?php

namespace Tests;

require __DIR__ . '/../public_html/app/init.php';


use PHPUnit_Framework_TestCase;

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
}