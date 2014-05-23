<?php

/**
 * Class User will contain prototype for users; tutors, secretary and admin.
 * Log In, Log Out.
 */
class User
{

   // connection to db.
   private $dbConnection;


   /**
    * Constructor
    * @param $database
    */
   public function __construct($dbConnection) {
      $this->dbConnection = $dbConnection;
   } // end __construct


   /**
    * Verifies given credentials are correct. If login successfuly, returns true
    * else return the error message.
    *
    * Dependancies:
    * require_once ROOT_PATH . "inc/model/bcrypt.php";
    * $bcrypt = new Bcrypt(12);
    *
    * @param $email $email of user
    * @param $password $password of user
    *
    * @return bool|string
    */
   public function login($email, $password) {

      if (empty($email) === true || empty($password) === true) {
         return 'Sorry, but we need both your email and password.';
      } else if ($this->email_exists($email) === false) {
         throw new Exception('Sorry that email doesn\'t exists.');
      }
      $query = "SELECT password, email FROM `" . DB_NAME . "`.`" . DB::USER_TABLE . "` WHERE `" . DB::USER_EMAIL . "` = :email";
      $query = $this->dbConnection->prepare($query);
      $query->bindParam(':email', $email);

      try {

         $query->execute();
         $data = $query->fetch();
         $hash_password = $data[DB::USER_PASSWORD];

         echo "<h1>" . $hash_password . "</h1>";
         // using the verify method to compare the password with the stored hashed password.
         if (!password_verify($password, $hash_password)) {
            throw new Exception('Sorry, that email/password is invalid');
         }

      } catch (PDOException $e) {
         // "Sorry could not connect to the database."
         throw new Exception($e->getMessage());
      }
   }// end function login

   /**
    * Verifies a user with given email exists.
    * returns true if found; else false
    *
    * @param $email $email given email
    * @return bool true if found; else false
    */
   public function email_exists($email) {
      $email = trim($email);
      $query = "SELECT COUNT(`" . DB::USER_ID . "`) FROM `" . DB_NAME . "`.`" . DB::USER_TABLE . "` WHERE `" .
         DB::USER_EMAIL . "` = :email";

      $query = $this->dbConnection->prepare($query);
      $query->bindParam(':email', $email, PDO::PARAM_STR);

      try {
         $query->execute();
         $rows = $query->fetchColumn();

         if ($rows == 1) {
            return true;
         } else {
            return false;
         } // end else if

      } catch (PDOException $e) {
         die($e->getMessage());
      } // end catch
   } // end function user_exists


   /**
    * Returns all information of a user given his email.
    * @param $email $email of user
    * @return mixed If
    */
   function get_data($email) {
      // this query return wrong id, of table user :( :( :( :( 3 FUCKING HOURS FUCKING BUG
      $user = DB::USER_TABLE;
      $user_types_id = DB::USER_TYPES_ID;
      $id = DB::USER_ID;
      $f_name = DB::USER_FIRST_NAME;
      $l_name = DB::USER_LAST_NAME;
      $img_loc = DB::USER_IMG_LOC;
      $date = DB::USER_DATE;
      $profile_description = DB::USER_PROFILE_DESCRIPTION;
      $mobile = DB::USER_MOBILE;
      $usertypes = DB::USERTYPES_TABLE;

      $type = DB::USERTYPES_TYPE;
      $major = DB::MAJOR_TABLE;
      $name = DB::MAJOR_NAME;

//      $query = "SELECT $this->USER_TABLE.$this->ID, $this->USER_TABLE.$this->FIRST_NAME, $this->USER_TABLE.$this->LAST_NAME,
//					$this->USER_TABLE.img_loc, $this->USER_TABLE.$this->DATE, $this->USER_TABLE.profile_description,
//					$this->USER_TABLE.$this->MOBILE, $this->USER_TYPES.type, major.name FROM " . DB_NAME . ".general_user
//						LEFT OUTER JOIN user_types ON user.user_types_id = user_types.id
//						LEFT OUTER JOIN major ON user.major_id = major.id
//					WHERE email = :email;";

            $query = "SELECT `" . DB::USER_TABLE . "`.`" . DB::USER_ID . "`, `" . DB::USER_TABLE . "`.`" . DB::USER_FIRST_NAME . "`,
             `" . DB::USER_TABLE . "`.`" . DB::USER_LAST_NAME . "`, `" . DB::USER_TABLE . "`.`" . "`" . DB::USER_IMG_LOC . "`,
              `" . DB::USER_TABLE . "`.`" . DB::USER_DATE . "`, `" . DB::USER_TABLE . "`.`" . DB::USER_PROFILE_DESCRIPTION . "`,
					`" . DB::USER_TABLE . "`.`" . DB::USER_MOBILE . "`, `" . DB::USERTYPES_TABLE . "`.`" . DB::USERTYPES_TYPE . "`,
					 `" . DB::MAJOR_TABLE . "`.`" . DB::MAJOR_NAME . "` FROM `" . DB_NAME . "`.`" . DB::USER_TABLE . "`
						LEFT OUTER JOIN user_types ON user.user_types_id = user_types.id
						LEFT OUTER JOIN major ON user.major_id = major.id
					WHERE email = :email;";
      $query = "SELECT `$user`.`$id`, `$user`.`$f_name`, `$user`.`$l_name`, `$user`.`$img_loc`
         `$user`.`$date`, `$user`.`$profile_description`, `$user`.`$mobile`, `$usertypes`.`$type`, `$major`.`$name`,
         FROM `" . DB_NAME . "`.`" . $user . "`
						LEFT OUTER JOIN `$usertypes` ON `$user`.`$user_types_id` = user_types.id
						LEFT OUTER JOIN major ON user.major_id = major.id
					WHERE email = :email;";
      echo $query;

      $query = $this->dbConnection->prepare($query);
      $query->bindValue(':email', $email, PDO::PARAM_INT);

      try {
         $query->execute();
         return $query->fetch();
      } catch (PDOException $e) {
         die($e->getMessage());
      } // end try
   } // end function get_data

   function update_profile_data($first_name, $last_name, $mobile_num, $description, $email) {
      $first_name = trim($first_name);
      $last_name = trim($last_name);
      $mobile_num = trim($mobile_num);
      $description = trim($description);
      $email = trim($email);

      $is_profile_data_correct = $this->is_profile_data_correct($first_name, $last_name,
         $mobile_num);

      if ($is_profile_data_correct !== true) {
         return $is_profile_data_correct; // the array of errors messages
      }

      $query = "UPDATE " . DB_NAME . ".general_user
					SET `f_name`= :first_name, `l_name`= :last_name, `mobile`= :mobile,
						`profile_description`= :profile_description
						WHERE `email`= :email";

      try {
         $query = $this->dbConnection->prepare($query);

         $query->bindParam(':first_name', $first_name, PDO::PARAM_STR);
         $query->bindParam(':last_name', $last_name, PDO::PARAM_STR);
         $query->bindParam(':mobile', $mobile_num, PDO::PARAM_STR);
         $query->bindParam(':profile_description', $description, PDO::PARAM_STR);
         $query->bindParam(':email', $email, PDO::PARAM_STR);

         $query->execute();

         return true;
      } catch (PDOException $pe) {
         throw new Exception("Something terrible happened. Could not update database.");
      }
   }

   function is_profile_data_correct($first_name, $last_name, $mobile_num) {
      $errors = array();

      if (!ctype_alpha($first_name)) {
         $errors[] = 'First name may contain only letters.';
      }

      if (!ctype_alpha($last_name)) {
         $errors[] = 'Last name may contain only letters.';
      }

      if (!preg_match('/^[0-9]{10}$/', $mobile_num)) {
         $errors[] = 'Mobile number should contain only digits of total length 10';
      }

      if (empty($errors)) {
         return true;
      } else {
         return $errors;
      }
   }

   public function update_avatar_img($avatar_img_loc, $user_id) {
      try {

         $query = "UPDATE " . DB_NAME . ".general_user SET `img_loc`= :avatar_img WHERE `id`= :user_id";

         $query = $this->dbConnection->prepare($query);
         $query->bindParam(':avatar_img', $avatar_img_loc, PDO::PARAM_STR);
         $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);

         $query->execute();
         return true;
      } catch (PDOException $e) {
         die ($e->getMessage());
      } // end try catch
   }


}

?>
