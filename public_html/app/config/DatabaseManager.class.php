<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/10/2014
 * Time: 4:53 AM
 */

require_once ROOT_PATH . 'config/App.class.php';
/**
 * All credits to http://stackoverflow.com/a/1864355/2790481
 * and https://gist.github.com/jonashansen229/4534794
 * Class DatabaseManager
 */
class DatabaseManager
{
	private static $instance;
	private $dbConnection;

	private function __construct() {
		try { // connects to database

			$this->dbConnection =
				new PDO("mysql:host=" . App::getDbHost() . ";dbname=" . App::getDbName() .
					";port=" . App::getDbPort(), App::getDbUsername(),
					App::getDbPassword());

			$this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, App::getPDOErrorMode()); // CHANGE THE ERROR MODE, THROW AN EXCEPTION WHEN AN ERROR IS FOUND
			$this->dbConnection->exec("SET NAMES 'utf8'");
		} catch (PDOException $e) { // program ends if exception is found
			throw new Exception("Could not connect to the database.". $e->getMessage());
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
