<?php

namespace App\mail;

use App;
use PHPMailer;
use Rakit\Validation\Validator;

/**
 * @author  Rizart Dokollari <r.dokollari@gmail.com>
 * @since   2/14/18
 */
class SassMailer
{
    public function send($data)
    {
        $this->validate($data);

        $enableExceptions = App::environment(['testing', 'local']);

        $phpMailer = new PHPMailer($enableExceptions);

        $phpMailer->setFrom(App::mailFrom(), 'SASS App');
        $phpMailer->Subject = $data['subject'];

        $name = array_key_exists('toName', $data) ? $data['toName'] : '';
        $phpMailer->addAddress($data['to'], $name);

        $html = $data['html'];

        if (array_key_exists('recipient-variables', $data)) {
            $html = $this->replaceRecipientVariables(
                $data['html'],
                $data['recipient-variables']
            );
        }

        $phpMailer->msgHTML($html);

        $phpMailer = $this->setupSmtp($phpMailer);

        if (App::environment(['local', 'testing'])) {
            $phpMailer->SMTPDebug = 2;
            $phpMailer->Debugoutput = 'html';

            return $phpMailer;
        }


        return $phpMailer->send();
    }

    private function validate($data)
    {
        $validator = new Validator;

        $validation = $validator->validate($data, [
            'to'      => 'required',
            'subject' => 'required',
            'html'    => 'required',
        ]);

        if ($validation->passes()) {
            return true;
        }

        $json = json_encode($validation->errors()->toArray());

        throw new SassMailerException($json);
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

        $phpMailer->Host = 'smtp.gmail.com';
        $phpMailer->Port = 587;
        $phpMailer->SMTPSecure = 'tls';
        $phpMailer->SMTPAuth = true;
        $phpMailer->Username = App::getenv('SMTP_USERNAME');
        $phpMailer->Password = App::getenv('SMTP_PASSWORD');

        return $phpMailer;
    }
}