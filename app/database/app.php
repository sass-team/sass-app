<?php
/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/12/14
 * Time: 11:49 PM
 */
class DB {

	private $dbConnection;

	/**
	 * @param mysql :host=localhost
	 *   1st part of string: type of database
	 *   2nd depending on type of db, on this case host=.
	 *   3rd name of database
	 *   4th if sql uses default port, this is not need. else you need to specify it like:
	 * Probably this exception should be emailed to programmers.
	 *
	 * @param $database
	 * @throws error message -- could not connect to database.
	 */
	public function __construct() {
		try { // connects to database
			$this->setDbConnection(new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=" . DB_PORT, DB_USER, DB_PASS));
			$this->getDbConnection()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // CHANGE THE ERROR MODE, THROW AN EXCEPTION WHEN AN ERROR IS FOUND
			$this->getDbConnection()->exec("SET NAMES 'utf8'");
		} catch (PDOException $e) { // program ends if exception is found
			throw new Exception("We could not connect to the database.");
		} // end

	}

	/**
	 * @return \PDO
	 */
	public function getDbConnection() {
		return $this->dbConnection;
	}

	/**
	 * @param \PDO $db
	 */
	public function setDbConnection($db) {
		$this->dbConnection = $db;
	} // end __construct
}

?>