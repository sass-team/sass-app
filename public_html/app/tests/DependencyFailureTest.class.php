<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/10/2014
 * Time: 8:09 AM
 */
class DependencyFailureTest extends PHPUnit_Framework_TestCase
{
	public function testOne() {
		$this->assertTrue(FALSE);
	}

	/**
	 * @depends testOne
	 */
	public function testTwo() {

	}
} 