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
	private static $instance;
	/**
	 * dev local server
	 * @var String
	 */
	private $dbHost = "localhost";
	private $dbUsername = "root";
	private $dbPassword = "";
	private $dbName = "sass-ms_db";
	private $dbPort = "3306";
	protected  $dbConnection;

	// Constructor
	private function __construct() {
		try { // connects to database
			$this->$dbConnection->setConnection(new PDO("mysql:host=$this->dbHost;dbname=$this->dbName;port=$this->dbPort", $this->dbUsername, $this->dbPassword));
			$this->$dbConnection->getConnection()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // CHANGE THE ERROR MODE, THROW AN EXCEPTION WHEN AN ERROR IS FOUND
			$this->$dbConnection->getConnection()->exec("SET NAMES 'utf8'");
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