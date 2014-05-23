<?php
/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/12/14
 * Time: 11:49 PM
 */

/*
PDO function:
*	@param mysql:host=localhost
*	1st part of string: type of database
*	2nd depending on type of db, on this case host=.
*	3rd name of database
*	4th if sql uses default port, this is not need. else you need to specify it like:
*
*	@param  the user name
*	@param
*/
//try { // connects to database
//   $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=" . DB_PORT, DB_USER, DB_PASS);
//   $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // CHANGE THE ERROR MODE, THROW AN EXCEPTION WHEN AN ERROR IS FOUND
//   $db->exec("SET NAMES 'utf8'");
//} catch (Exception $e) { // program ends if exception is found
//   echo "Could not connect to the database: <br>" . $e;
//   exit;
//} // end

class DB
{

   // data of database for used in queries only
   // user table
   const USER_TABLE = 'user';
   const USER_EMAIL = "email";
   const USER_FIRST_NAME = 'f_name';
   const USER_LAST_NAME = 'l_name';
   const USER_DATE = 'date';
   const USER_USER_TYPES = 'user_types';
   const USER_PROFILE_DESCRIPTION = 'profile_description';
   const USER_MOBILE = 'mobile';
   const USER_PASSWORD = 'password';
   const USER_USER_TYPE_ID = 'user_types_ID';
   CONST USER_IMG_LOC = 'img_loc';
   const USER_TYPES_ID = 'id';


   //  user_types table
   const USERTYPES_TABLE = 'user_types';
   const USERTYPES_TYPE = 'type';
   const USERTYPES_ID = 'id';

   // major table
   const MAJOR_TABLE = 'major';
   const MAJOR_NAME = 'name';
   ///////////////////////////////////////////////

   private $dbConnection;

   /**
    * Constructor.
    *
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