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

    public static $settings;
    public static $dsn = array(
        self::DB_NAME => 'sass'
    );

    /**
     * Check if current time is during working hours
     * @return bool
     */
    static function isWorkingDateTimeOn()
    {
        date_default_timezone_set(self::$settings['DATE_DEFAULT_TIMEZONE_SET']);

        $curWorkingDate = new DateTime();
        $curWorkingHour = intval($curWorkingDate->format('H'));
        $curWorkingDay = intval($curWorkingDate->format('N'));

        // save resources - only run cron at working hours/day (monday - friday)
        if ($curWorkingHour < self::WORKING_HOUR_START || $curWorkingHour > self::WORKING_HOUR_END ||
            $curWorkingDay > self::WORKING_DAY_END
        ) {
            return false;
        }

        return true;
    }

    /**
     * Force Date default time zone
     * @return DateTime
     */
    static function getCurWorkingDate()
    {
        date_default_timezone_set('Europe/Athens');

        $curWorkingDate = new DateTime();

        return $curWorkingDate;
    }

    /**
     * Format App url to ssl
     *
     * @return string
     */
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

    /**
     * Check if App is accessed from ssl
     *
     * @return bool
     */
    public static function isSecure()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') return true;

        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ||
            !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on'
        ) {
            return true;
        }

        return false;
    }


    /**
     * Set App Settings (database, email & recaptcha credentials as well as working hours.)
     * @param $settingsFile
     */
    public static function loadSettings($settingsFile)
    {
        self::$settings = require $settingsFile;
    }

    /**
     * @return mixed
     */
    public static function getDbHost()
    {
        return self::$settings['DB_HOST'];
    }

    public static function getDbName()
    {
        return self::$settings['DB_NAME'];
    }

    public static function getDbUsername()
    {
        return self::$settings['DB_USERNAME'];
    }

    public static function getDbPassword()
    {
        return self::$settings['DB_PASSWORD'];
    }

    public static function getDbPort()
    {
        return self::$settings['DB_PORT'];
    }

    public static function getMailgunKey()
    {
        return self::$settings['MAILGUN_KEY'];
    }

    public static function getMailgunDomain()
    {
        return self::$settings['MAILGUN_DOMAIN'];
    }

    public static function getReCaptchaSiteKey()
    {
        return self::$settings['RECAPTCHA_SITE_KEY'];
    }

    public static function getReCaptchaSecretKey()
    {
        return self::$settings['RECAPTCHA_SECRET_KEY'];
    }
}
