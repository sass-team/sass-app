<?php

/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/29/14
 * Time: 8:01 PM
 */
class Secretary extends User {
	/**
	 * Constructor
	 * @param $database
	 */
	public function __construct($data, $db) {
		parent::__construct($data, $db);
	}

	public function isSecretary() {
		return true;
	}
} 