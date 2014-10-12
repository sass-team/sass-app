<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/10/2014
 * Time: 4:53 AM
 */

/**
 * All credits to http://stackoverflow.com/a/1864355/2790481
 * and https://gist.github.com/jonashansen229/4534794
 * Class DatabaseManager
 */
class DatabaseManager
{
	const DB_HOST = 'dbHost';
	const DB_USERNAME = 'dbUsername';
	const DB_PASSWORD = 'dbPassword';
	const DB_NAME = 'dbName';
	const DB_PORT = 'dbPort';

//	public static $dsnProduction = array(
//		self::DB_HOST => 'localhost',
//		self::DB_USERNAME => 'root',
//		self::DB_PASSWORD => '',
//		self::DB_NAME => 'sass-ms_db',
//		self::DB_PORT => '3306'
//	);

	public static $dsnProduction = array(
		self::DB_HOST => "mysql.hostinger.gr",
		self::DB_USERNAME => "u110998101_sassu",
		self::DB_PASSWORD => "DDhS662fu5PzfgmM7a",
		self::DB_NAME => "u110998101_sassd",
		self::DB_PORT => "3306"
	);
	private static $instance;
	private $dbConnection;


	// Constructor

	private function __construct() {
		try { // connects to database

			$this->dbConnection =
				new PDO("mysql:host=" . self::$dsnProduction[self::DB_HOST] . ";dbname=" . self::$dsnProduction[self::DB_NAME] .
					";port=" . self::$dsnProduction[self::DB_PORT], self::$dsnProduction[self::DB_USERNAME],
					self::$dsnProduction[self::DB_PASSWORD]);

			$this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // CHANGE THE ERROR MODE, THROW AN EXCEPTION WHEN AN ERROR IS FOUND
			$this->dbConnection->exec("SET NAMES 'utf8'");
		} catch (PDOException $e) { // program ends if exception is found
			throw new Exception("Could not connect to the database." . $e->getMessage() .  self::$dsnProduction[self::DB_HOST]);
		} // end
	}

	public static function getConnection() {
		return self::getInstance();
	}

	private static function getInstance() {
		if (!self::$instance) { // If no instance then make one
			self::$instance = new self();
		}
		return self::$instance->dbConnection;
	}

	public function __clone() {
		throw new Exception("Can't clone a singleton.");
	}
} 