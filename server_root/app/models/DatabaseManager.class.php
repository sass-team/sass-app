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
	const DB_HOST = 'localhost';
	const DB_NAME = 'sass-ms_db';
	const DB_PORT = '3306';
	const DB_USER = 'root';
	const DB_PASS = '';
	private static $instance;
	private $db;

	// Constructor
	private function __construct() {
		try { // connects to database
			$this->$db->setConnection(new PDO("mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";port=" .
				self::DB_PORT, self::DB_USER, self::DB_PASS));
			$this->$db->getConnection()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // CHANGE THE ERROR MODE, THROW AN EXCEPTION WHEN AN ERROR IS FOUND
			$this->$db->getConnection()->exec("SET NAMES 'utf8'");
		} catch (PDOException $e) { // program ends if exception is found
			throw new Exception("Could not connect to the database.");
		} // end
	}

	public static function getConnection() {
		return self::getInstance();
	}

	private static function getInstance() {
		if (!self::$instance) { // If no instance then make one
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __clone() {
		throw new Exception("Can't clone a singleton.");
	}
} 