<?php
/**
 * @author Rizart Dokollari <r.dokollari@gmail.com>
 * @since 9/29/18
 */

namespace tests\Integration\Mails;

use Mailer;
use Tests\TestCase;

class MailerTest extends TestCase
{
    /** @test */
    public function it_may_sent_emails_about_new_account()
    {
        $email = 'r.dokollari@gmail.com';

        $user = \User::findByEmail($email);

        Mailer::sendNewAccount($user);

        // No exception was thrown
        $this->assertTrue(true);
    }
}