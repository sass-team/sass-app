<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) <year> <copyright holders>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @author Rizart Dokollari
 * @author George Skarlatos
 * @since 9/14/2014
 */
class Mailer
{

	public static function sendNewAccount($senderEmail, $senderName, $receiverEmail, $receiverName, $link) {
		$receiverEmail = "email@email.com";
		$receiverName = "Rizart Dokollari";

		require __DIR__ . '/../../plugins/PHPMailer/PHPMailerAutoload.php';
		$senderEmail = "no-reply@" . $_SERVER['SERVER_NAME'];
		$alternativeEmail = "dev.sass.ms@gmail.com";
		$alternativeName = "SASS Developers";

		$senderName = "Administrator App";

		try {
			//Create a new PHPMailer instance
			$mail = new PHPMailer();
			// Set PHPMailer to use the sendmail transport
			$mail->isSendmail();
			//Set who the message is to be sent from
			$mail->setFrom($senderEmail, $senderName);
			//Set an alternative reply-to address
			$mail->addReplyTo($alternativeEmail, $alternativeName);
			//Set who the message is to be sent to
			$mail->addAddress($receiverEmail, $receiverName);
			//Set the subject line
			$mail->Subject = 'PHPMailer sendmail test';
			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			$mail->msgHTML(file_get_contents("http://" . $_SERVER['SERVER_NAME'] . "mail/contents.html"), dirname($_SERVER['SERVER_NAME'] . "mail/contents.html"));
			//Replace the plain text body with one created manually
			$mail->AltBody = 'This is a plain-text message body';
			//Attach an image file
			$mail->addAttachment('images/phpmailer_mini.png');

			$mail->send();

		} catch (phpmailerException $e) {
			throw new Exception("PHPMailer error: " . $e->errorMessage()); //Pretty error messages from PHPMailer
		} catch (Exception $e) {
			throw new Exception("Something terrible happended"); //Pretty error messages from PHPMailer
		}
	}
}