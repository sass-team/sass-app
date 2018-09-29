<?php

namespace App\mail;

use User;

class EmailNewAccount
{
    /** @var SassMailer */
    private $sassMailer;

    public function __construct(SassMailer $sassMailer)
    {
        $this->sassMailer = $sassMailer;
    }

    public function handle(User $user, $passwordLink)
    {
        $recipientVariables = [
            'recipient.fullName'        => $user->fullName(),
            'recipient.setPasswordLink' => $passwordLink,
        ];

        $data = [
            'to'                  => $user->getEmail(),
            'subject'             => 'Welcome',
            'toName'              => $user->fullName(),
            'html'                => file_get_contents(ROOT_PATH . 'mail/templates/verify_email.html'),
            'text'                => 'Please visit ' . $passwordLink . ' to setup you password.',
            'recipient-variables' => $recipientVariables,
        ];

        return $this->sassMailer->send($data);
    }
}