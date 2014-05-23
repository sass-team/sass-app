<?php

/**
 * Created by PhpStorm.
 * User: Rizart Dokollari
 * Date: 5/20/14
 * Time: 4:25 AM
 */
class Bcrypt
{
   private $rounds;

   public function __construct($rounds = 12) {
      if (CRYPT_BLOWFISH != 1) {
         // TODO: email programmers when this exception occur.
         throw new Exception("A fatal error occured with the server. Our software engineers are being notified.");
      } // end if		} // end if
      $this->rounds = $rounds;
   } // end __construct

   /**
    * Gen Salt: create a random string, used to generate strong hash
    *
    * @param $password
    * @return string
    */
   public function genHash($password) {
      /* 2y selects bcrypt algorithm */
      /* $this->rounds is the workload factor, which is kept usually from 12 to 15 */
      $hash = crypt($password, '$2y$' . $this->rounds . '$' . $this->genSalt());

      return $hash;
   } // end genSalt

   /**
    * Gen Hash
    *
    * @return string
    */
   private function genSalt() {
      $string = str_shuffle(mt_rand()); // generating a random string
      $salt = uniqid($string, true); // generating a random and unique string

      return $salt;
   } // end genHash

   /**
    * Verify Password
    *
    * @param $password
    * @param $existingHash
    * @return bool
    */
   public function verify($password, $existingHash) {
      /* Hash new password with old hash */
      $hash = crypt($password, $existingHash);

      /* Do Hashs match? */
      if ($hash === $existingHash) {
         return true;
      } else {
         return false;
      } // end else if
   } // end verify
} 