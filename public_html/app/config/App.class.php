<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/12/2014
 * Time: 7:49 PM
 */
class App
{
	public static $settings;

	/**
	 * Check if current time is during working hours
	 * @return bool
	 */
	static function isWorkingDateTimeOn()
	{
		date_default_timezone_set(self::getTimeZone());

		$curWorkingDate = new DateTime();
		$curWorkingHour = intval($curWorkingDate->format('H'));
		$curWorkingDay = intval($curWorkingDate->format('N'));

		// save resources - only run cron at working hours/day (monday - friday)
		if ($curWorkingHour < self::getFirstWorkingHour() || $curWorkingHour > self::getLastWorkingHour() ||
			$curWorkingDay > self::getLastWorkingDay()
		)
		{
			return false;
		}

		return true;
	}

	public static function getTimeZone()
	{
		return self::$settings['TimeZone'];
	}

	public static function getFirstWorkingHour()
	{
		return self::$settings['FIRST_WORKING_HOUR'];
	}

	public static function getLastWorkingHour()
	{
		return self::$settings['LAST_WORKING_HOUR'];
	}

	public static function getLastWorkingDay()
	{
		return self::$settings['LAST_WORKING_DAY'];
	}

	/**
	 * Force Date default time zone
	 * @return DateTime
	 */
	static function getCurWorkingDate()
	{
		date_default_timezone_set(self::getTimeZone());

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
		if (self::isHostnameInSSLList())
		{
			return "https://" . $_SERVER['SERVER_NAME'];
		}

		return "http://" . $_SERVER['SERVER_NAME'];
	}

	/**
	 * @return array
	 */
	public static function isHostnameInSSLList()
	{
		$domainName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "cron";

		$hosts = self::getHostNames();

		$hostsWithSSL = $hosts['SSL'];

		return in_array($domainName, $hostsWithSSL);
	}

	public static function getHostNames()
	{
		return self::$settings['HOST_NAMES'];
	}

	/**
	 * Check if App is accessed from ssl
	 *
	 * @return bool
	 */
	public static function isSecure()
	{
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
		{
			return true;
		}

		if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ||
			!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on'
		)
		{
			return true;
		}

		return false;
	}

	/**
	 * Load App Settings (database, email & ReCaptcha credentials as well as working hours.)
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

	public static function getName()
	{
		return self::$settings['NAME'];
	}

	public static function getVersion()
	{
		return self::$settings['VERSION'];
	}

	public static function getDefaultDateFormat()
	{
		return self::$settings['DEFAULT_DATE_FORMAT'];
	}

	public static function getHostname()
	{
		$hosts = self::getHostNames();
		$hostsWithSSL = $hosts['SSL'];

		$hostname = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $hostsWithSSL['production'];

		return $hostname;
	}

	public static function getPDOErrorMode()
	{
		return self::$settings['PDO_ERROR_MODE'];
	}

	public static function getGithubNewIssueUrl()
	{
		return self::$settings['GITHUB_NEW_ISSUE_URL'];
	}
}
