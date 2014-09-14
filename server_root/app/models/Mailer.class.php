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

	public static function sendNewAccount($db, $id, $senderEmail, $senderName, $receiverEmail, $receiverName) {
		require ROOT_PATH . "app/plugins/PHPMailer/PHPMailerAutoload.php";

		$getString = User::generateNewPasswordString($db, $id);
		$subject = "New SASS App Account";
		$alternativeEmail = "dev.sass.ms@gmail.com";
		$alternativeName = "SASS Developers";
		$setPasswordLink = "<a href='http://" . $_SERVER['SERVER_NAME'] . "/login/set/" . $id . "/" . $getString . "' target='_blank' >Set SASS App Password</a><br/>";
		$sassPageLogin = "<a href='http://" . $_SERVER['SERVER_NAME'] . "/login/' target='_blank' >Login</a><br/>";
		$senderName = "Administrator: " . $senderName;

		try {
			//Create a new PHPMailer instance
			$mail = new PHPMailer();
			// Set PHPMailer to use the sendmail transport
			//Set who the message is to be sent from
			$mail->setFrom($senderEmail, $senderName);
			//Set an alternative reply-to address
			$mail->addReplyTo($alternativeEmail, $alternativeName);
			//Set who the message is to be sent to
			$mail->addAddress($receiverEmail, $receiverName);
			//Set the subject line
			$mail->Subject = 'PHPMailer sendmail test';

			$message = "<div style='padding-top:20px;width:550px;margin:0 auto'>";
			$message .= "<h1 style='margin:0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:40px;letter-spacing:-1px;color:#333;font-weight:normal'>
			Welcome to SASS App</h1><br/>
			<p style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>
			You&#39;ve been just invited to the SASS App, <strong>$receiverName</strong></p><br/></div>";

			$message .= "You can use the following link to activate your account.<br/>";
			$message .= $setPasswordLink;
			$message .= "<br/>You will be prompted for a password for your account. When you are done, you can $sassPageLogin at SASS App using this email address and the password you choose.<br/>";
			$message .= "<br/>Thanks,<br/>SASS Automatic System";


			$email_body = $message;

			//Set who the message is to be sent from
			$mail->setFrom($senderEmail, $senderName);
			//Set who the message is to be sent to
			$mail->addAddress($receiverEmail, $receiverName);
			//Set the subject line
			$mail->Subject = 'SASS | ' . $subject;
			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			$mail->msgHTML($email_body);
			//Attach an image file
			//$mail->addAttachment('images/phpmailer_mini.gif');

			$mail->send();

		} catch (phpmailerException $e) {
			throw new Exception("PHPMailer error: " . $e->errorMessage()); //Pretty error messages from PHPMailer
		} catch
		(Exception $e) {
			throw new Exception("Something went wrong with mail. Please re-send mail to user for setting password."); //Pretty error messages from PHPMailer
		}
	}
}