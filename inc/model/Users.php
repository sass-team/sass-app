<?php

/**
 * Created by PhpStorm.
 * User: rdk
 * Date: 5/29/14
 * Time: 1:31 PM
 */
class Users
{
   /**
    * Returns all information of a user given his email.
    * @param $email $email of user
    * @return mixed If
    */
   function getAllData($email) {
      $query = "SELECT user.id, user.`f_name`, user.`l_name`, user.`img_loc`,
						user.date, user.`profile_description`, user.mobile, user_types.type, major.name
					FROM `" . DB_NAME . "`.user
						LEFT OUTER JOIN user_types ON user.`user_types_id` = `user_types`.id
						LEFT OUTER JOIN major ON user.major_id = major.id
					WHERE email = :email";

      $query = $this->dbConnection->prepare($query);
      $query->bindValue(':email', $email, PDO::PARAM_INT);

      try {
         $query->execute();
         return $query->fetch();
      } catch (PDOException $e) {
         die($e->getMessage());
      } // end try
   } // end getAllData
} 