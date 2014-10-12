<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/10/2014
 * Time: 4:46 AM
 */
class MailFetcherTest extends PHPUnit_Extensions_Database_TestCase
{

//	public function testCanSendMail() {
//		$class = $this->getMockClass(
//			'MailerFetcher',
//			array('canSendMail')
//		);
//
//		$class::staticExpects($this->any())
//			->method('canSendMail')
//			->will($this->returnValue(true));
//
//		$this->assertEquals(
//			true,
//			$class::canSendMail()
//		);
//	}

	public function getConnection() {
		$pdo = new PDO('sqlite::memory:');
		return $this->createDefaultDBConnection($pdo, ':memory:');
	}

	public function getDataSet() {
		return $this->createFlatXMLDataSet(dirname(__FILE__) . '/_files/mailfetcher-seed.xml');
	}
}