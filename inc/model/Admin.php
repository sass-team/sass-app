<?php
/**
 * Created by PhpStorm.
 * User: rdk
 * Date: 5/29/14
 * Time: 2:13 PM
 */

class Admin extends User{

   public function isAdmin() {
      return true;
   }
} 