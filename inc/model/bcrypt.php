<?php
/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/13/14
 * Time: 1:34 AM
 */

/* Create a new class called Bcrypt */

class Bcrypt
{
	private $rounds;

	public function __construct($rounds = 12)
	{
		if (CRYPT_BLOWFISH != 1) {
			throw new Exception("Bcrypt is not supported on this server, please see the following to learn more: http://php.net/crypt");
		} // end if
		$this->rounds = $rounds;
	} // end __construct

	/* Gen Salt: create a random string, used to generate strong hash */
	private function genSalt()
	{
		$string = str_shuffle(mt_rand()); // generating a random string
		$salt = uniqid($string, true); // generating a random and unique string

		return $salt;
	} // end genSalt

	/* Gen Hash */
	public function genHash($password)
	{

		/* 2y selects bcrypt algorithm */
		/* $this->rounds is the workload factor, which is kept usually from 12 to 15 */
		$hash = crypt($password, '$2y$' . $this->rounds . '$' . $this->genSalt());

		return $hash;
	} // end genHash

	/* Verify Password */
	public function verify($password, $existingHash)
	{
		/* Hash new password with old hash */
		$hash = crypt($password, $existingHash);

		/* Do Hashs match? */
		if ($hash === $existingHash) {
			return true;
		} else {
			return false;
		} // end else if
	} // end verify
} // end class Bcrypt

?>