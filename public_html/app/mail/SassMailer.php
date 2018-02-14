<?php

namespace App\mail;

use App;
use PHPMailer;


/**
 * @author  Rizart Dokollari <r.dokollari@gmail.com>
 * @since   2/14/18
 */
class SassMailer
{
    public function send($data)
    {
        $phpMailer = new PHPMailer();
        $phpMailer->setFrom(App::mailFrom());
        $phpMailer->addAddress($data['to']);
        $phpMailer->Subject = $data['subject'];
        $phpMailer->msgHTML($data['html']);

        $phpMailer = $this->setupTestingEnv($phpMailer);

        if ( ! $phpMailer->send()) {
            throw new \Exception($phpMailer->ErrorInfo);
        }

        return true;
    }

    private function setupTestingEnv(PHPMailer $phpMailer)
    {
        if ( ! App::env('testing')) {
            return $phpMailer;
        }

        $phpMailer->isSMTP();
        $phpMailer->SMTPDebug = 2;
        $phpMailer->Debugoutput = 'html';
        $phpMailer->Host = 'smtp.gmail.com';
        $phpMailer->Port = 587;
        $phpMailer->SMTPSecure = 'tls';
        $phpMailer->SMTPAuth = true;
        $phpMailer->Username = App::getenv('SMTP_USERNAME');
        $phpMailer->Password = App::getenv('SMTP_PASSWORD');

        return $phpMailer;
    }
}