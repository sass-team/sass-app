<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/12/2014
 * Time: 7:49 PM
 */
class App
{

    const WORKING_HOUR_START = 11;
    const WORKING_HOUR_END = 20;
    const NAME = "SASS App";
    const VERSION = "v1.5.0";
    const DATE_FORMAT = "m/d/Y g:i A";
    const PRODUCTION_HOST = "sass-app.in";
    const DEV_SSL_HOST = "sass.app";
    const WORKING_DAY_END = 5;
    const DB_HOST = 'dbHost';
    const DB_USERNAME = 'dbUsername';
    const DB_PASSWORD = 'dbPassword';
    const DB_NAME = 'dbName';
    const DB_PORT = 'dbPort';

    // develop environment
    public static $dsn = array(
        self::DB_HOST => 'localhost',
        self::DB_USERNAME => 'root',
        self::DB_PASSWORD => 'toor',
        self::DB_NAME => 'sass',
        self::DB_PORT => '3306'
    );


    static function isWorkingDateTimeOn()
    {
        date_default_timezone_set('Europe/Athens');

        $curWorkingDate = new DateTime();

        $curWorkingHour = intval($curWorkingDate->format('H'));
        $curWorkingDay = intval($curWorkingDate->format('N'));

        // save resources - only run cron at working hours/day (monday - friday)
        if ($curWorkingHour < self::WORKING_HOUR_START || $curWorkingHour > self::WORKING_HOUR_END || $curWorkingDay > self::WORKING_DAY_END) {
            return false;
        }

        return true;
    }

    static function getCurWorkingDate()
    {
        date_default_timezone_set('Europe/Athens');

        $curWorkingDate = new DateTime();

        return $curWorkingDate;
    }

    public static function getDomainName()
    {
        $domainName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "cron";

        switch ($domainName) {
            case self::PRODUCTION_HOST:
            case self::DEV_SSL_HOST:
                $domainName = "https://" . $domainName;
                break;
        }

        return $domainName;
    }

    public static function isSecure()
    {
        $isSecure = false;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $isSecure = true;
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            $isSecure = true;
        }
        return $isSecure;
    }
}
