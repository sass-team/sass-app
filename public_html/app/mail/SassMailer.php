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
        $phpMailer = new PHPMailer(App::environment(['testing', 'local']));

        $phpMailer->setFrom(App::mailFrom(), 'SASS App');

        $phpMailer->addAddress($data['to']);

        $phpMailer->Subject = $data['subject'];

        $html = $data['html'];

        if (array_key_exists('recipient-variables', $data)) {
            $html = $this->replaceRecipientVariables(
                $data['html'],
                $data['recipient-variables']
            );
        }

        $phpMailer->msgHTML($html);

        $phpMailer = $this->setupSmtp($phpMailer);

        return $phpMailer->send();
    }

    public function replaceRecipientVariables($html, array $variables)
    {
        foreach ($variables as $key => $value) {
            $html = str_replace('%' . $key . '%', $value, $html);
        }

        return $html;
    }

    private function setupSmtp(PHPMailer $phpMailer)
    {
        $phpMailer->isSMTP();

        if (App::environment(['local', 'testing'])) {
            $phpMailer->SMTPDebug = 2;
            $phpMailer->Debugoutput = 'html';
        }

        $phpMailer->Host = 'smtp.gmail.com';
        $phpMailer->Port = 587;
        $phpMailer->SMTPSecure = 'tls';
        $phpMailer->SMTPAuth = true;
        $phpMailer->Username = App::getenv('SMTP_USERNAME');
        $phpMailer->Password = App::getenv('SMTP_PASSWORD');

        return $phpMailer;
    }
}