<?php
/**
 * @author Rizart Dokollari <r.dokollari@gmail.com>
 * @since 9/29/18
 */

namespace App\mail;

class EmailNewAccount
{
    /** @var SassMailer */
    private $sassMailer;

    public function __construct(SassMailer $sassMailer)
    {
        $this->sassMailer = $sassMailer;
    }

    public function handle($newUserId, $receiverEmail, $receiverName)
    {
        $mg = new Mailgun(App::getMailgunKey());
        $domain = App::getMailgunDomain();

        // Load mail template
        $emailVerificationTemplate = file_get_contents(ROOT_PATH . 'mail/templates/verify_email.html');
        $getString = User::generateNewPasswordString($newUserId);
        $setPasswordLink = App::getDomainName() . "/login/set/" . $newUserId . "/" . $getString;

        # Now, compose and send the message.
        $mg->sendMessage($domain, [
            'from'                => "SASS App admin@" . App::getHostname(),
            'to'                  => $receiverEmail,
            'subject'             => 'Welcome',
            'text'                => 'Your mail does not support html',
            'html'                => $emailVerificationTemplate,
            'recipient-variables' => '{"' . $receiverEmail . '": {"id":' . $newUserId . ',"setPasswordLink":"' . $setPasswordLink . '","fullName":"' . $receiverName . '"}}',
        ]);

        return true;

        return $this->sassMailer->send()
    }
}