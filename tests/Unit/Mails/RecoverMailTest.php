<?php

require __DIR__ . '/../../../public_html/app/init.php';

/**
 * @author  Rizart Dokollari <r.dokollari@gmail.com>
 * @since   2/13/18
 */
class RecoverMailTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $_SERVER['SERVER_NAME'] = 'sass';
    }

    /** @test */
    public function the_mailer_can_send_recover_emails()
    {
        // given I a attempt to send an email
        $email = 'r.dokollari@gmail.com';

        $lastResponse = Mailer::sendRecover($email);

        // then I should get a 200 response
        $this->assertEquals(200, $lastResponse->getStatusCode());
    }
}