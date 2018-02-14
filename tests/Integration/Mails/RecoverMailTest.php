<?php

namespace Tests\Integration\Mails;

use Mailer;
use Tests\TestCase;

/**
 * @author  Rizart Dokollari <r.dokollari@gmail.com>
 * @since   2/13/18
 */
class RecoverMailTest extends TestCase
{
    /** @test */
    public function the_mailer_can_send_recover_emails()
    {
        // given I a attempt to send an email
        $email = 'r.dokollari@gmail.com';

        Mailer::sendRecover($email);

        // No exception was thrown
        $this->assertTrue(true);
    }
}