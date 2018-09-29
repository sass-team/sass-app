<?php
/**
 * @author Rizart Dokollari <r.dokollari@gmail.com>
 * @since 9/29/18
 */

namespace tests\Unit\app\config\mail;

use App\mail\EmailNewAccount;
use App\mail\SassMailer;
use Faker\Factory;
use PHPMailer;
use Tests\TestCase;
use User;

class EmailNewAccountTest extends TestCase
{
    /** @var  EmailNewAccount */
    protected $emailNewAccount;
    protected $setPasswordLink;
    /** @var User */
    protected $user;

    public function setUp()
    {
        $sassMailer = new SassMailer();

        $this->user = $this->generateTutor();

        $this->emailNewAccount = new EmailNewAccount($sassMailer);

        $this->setPasswordLink = Factory::create()->url;
    }

    /** @test */
    public function it_does_not_send_email_when_on_local_or_testing_env()
    {
        $phpMailer = $this->emailNewAccount->handle($this->user, $this->setPasswordLink);

        $this->assertInstanceOf(PHPMailer::class, $phpMailer);
    }

    /** @test */
    public function it_generates_expected_html()
    {
        /** @var PHPMailer $phpMailer */
        $phpMailer = $this->emailNewAccount->handle($this->user, $this->setPasswordLink);

        $body = $phpMailer->createBody();

        $this->assertContains(
            "Welcome to SASS App {$this->user->fullName()}. <br/>Please set your password by clicking the link below.",
            $body
        );

        $this->assertContains(
            '<a href="' . $this->setPasswordLink . '" class="btn-primary" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background: #348eda; margin: 0; padding: 0; border-color: #348eda; border-style: solid; border-width: 10px 20px;">Confirm email address</a>',
            $body
        );
    }

    /** @test */
    public function it_uses_expected_to_email_and_full_name()
    {
        /** @var PHPMailer $phpMailer */
        $phpMailer = $this->emailNewAccount->handle($this->user, $this->setPasswordLink);

        $expected = [[$this->user->getEmail(), $this->user->fullName()]];

        $actual = $phpMailer->getToAddresses();

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_uses_expected_subject()
    {
        /** @var PHPMailer $phpMailer */
        $phpMailer = $this->emailNewAccount->handle($this->user, $this->setPasswordLink);

        $this->assertEquals('Welcome', $phpMailer->Subject);
    }
}