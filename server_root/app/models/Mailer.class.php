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


	const DEV_SASS_GMAIL = "dev.sass.ms@gmail.com";

	const SASS_APP_AUTOMATIC_SYSTEM_ALTERNATIVE_NAME = "SASS App Automatic System";

	const NEW_SASS_APP_APPOINTMENT_SUBJECT = "New Appointment";
	const NEW_SASS_APP_REPORT_PENDING = "New Report Pending";

	const SASS_SUBJECT_PREFIX = 'SASS App | ';

	const NO_REPLY_EMAIL_PREFIX = "no-reply@";

	public static function sendTutorNewReport($db, $reportId, $tutorId, $courseId) {
		require ROOT_PATH . "app/plugins/PHPMailer/PHPMailerAutoload.php";

		try {
//			$report = ReportFetcher::retrieveSingle($db, $reportId);
			$appointment = AppointmentFetcher::retrieveSingle($db, $tutorId);
			$tutorUser = UserFetcher::retrieveSingle($db, $appointment[AppointmentFetcher::DB_COLUMN_TUTOR_USER_ID]);
			$course = CourseFetcher::retrieveSingle($db, $courseId);

			$subject = self::NEW_SASS_APP_APPOINTMENT_SUBJECT;
			$alternativeEmail = self::DEV_SASS_GMAIL;
			$alternativeName = self::SASS_APP_AUTOMATIC_SYSTEM_ALTERNATIVE_NAME;
			$reportLink = "<a href='http://" . $_SERVER['SERVER_NAME'] . "/reports/" . $reportId . "' target='_blank' >View Report</a><br/>";

			$senderEmail = self::NO_REPLY_EMAIL_PREFIX . $_SERVER['SERVER_NAME'];
			$senderName = self::SASS_APP_AUTOMATIC_SYSTEM_ALTERNATIVE_NAME;
			$receiverEmail = $tutorUser[UserFetcher::DB_COLUMN_EMAIL];
			$receiverName = $tutorUser[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $tutorUser[UserFetcher::DB_COLUMN_LAST_NAME];;


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
			$mail->Subject = $subject;


			$message = "<div bgcolor='#fafafa' marginheight='0' marginwidth='0' style='width:100%!important;background:#fafafa'>";
			$message .= "<div style='padding:20px 20px 20px 20px!important;width:550px;margin:0 auto'>";
			$message .= "<h1 style='margin:0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:40px;letter-spacing:-1px;color:#333;font-weight:normal'>
					New Pending Report from SASS App</h1>";
			$message .= "<span style='color: #f0ad4e; margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'background-color>
					We have a new <span style='color:#f0ad4e!important;'>pending</span> report for you, <strong>$receiverName</strong></span>.";

			$message .= "<div style='margin:20px auto!important;width:510px;padding:20px 20px 20px 20px!important;border:1px solid #ddd!important;border-radius:3px!important;background:#ffffff!important'>";
			$message .= "<p style='margin:5px 0 15px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px;font-weight:normal;color:#333;line-height:20px'>
					Please follow the below link and fill in the report.<br/>$reportLink</p>";
			$message .= "<p style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>
							The report is intended for the appointment with data:";
			$message .= "<br/><strong>Course name:</strong> " . $course[CourseFetcher::DB_COLUMN_NAME];
			$message .= "<br/><strong>Starts at:</strong> " . $appointment[AppointmentFetcher::DB_COLUMN_START_TIME];
			$message .= "<br/><strong>Ends at:</strong> " . $appointment[AppointmentFetcher::DB_COLUMN_END_TIME] . "</p>";

			$message .= "<div style='margin:20px 0;border-top:1px solid #ddd'></div>";
			$message .= "<p style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>Thanks,<br/><strong>$senderName</strong></p>";
			$message .= "</div>";
			$message .= "<div style='margin:20px 0;text-align:center;'>";
			$message .= '<img alt="SASS logo" height="40" width="40" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAC4jAAAuIwF4pT92AAAAB3RJTUUH3gkOEB4GKCv7JwAABOFJREFUWMPt1n+slmUZB/DP9TznvICkiDCcJChUDAMpkR+OR0dkLp0zZz+mbG2t1tYmzZrzZenGakVbnbdcVrjStpxlODVsrTbWahqDR63FiElqwBbNQeuHghyB877nee/+OI9wfDnoH/1D2/v969lzf+/7vq7vfV3f+6aPPvroo48+/hfEGx+NaM6WXIkrgnMTf8VTif0drTTR5NyiBZkVM8PsZ9u+3p2I09CcimV4Hy7BIfwRO9paozUnq8ffj8sSx4Ldwm8zmBTNZZJ7ExdnWfb9rvQErsPPSFPOkNyspP1DptxPTJ6IMBjNGdiYuEnEr0XcR0zD47iwDm4SvoDbE/tSpG8Gf8NX8ZmBhub5KdmIb3S0nu6M6fBcQ/N2YihOidyLT5IvDO+oaM/AsR7lMskdGO5obXDqDDYMas7l5J8PYmUKn+2k1nD996GG5gnJ9AyXI8eLPQd4WKQXRJrg6LKLcGeY9hD5SJKu6GUkzsUabJugrp4NRhqxflDEtXg+Jcd75pc4lGE6BhJTxxO6RqvIPRC5kQnU+xKOZN77S6pXahV6w5iamFWv/+bgIzaniMNSyqW0ANOCwZ4sDqbcbzIcwcLg5lxz4I3xUd9KI6OtIyOjvQ2SLcEdxGMdP36GdCBYfLpK6VhwAjcOal48fqyThg530lCFyli9XRNjDTKO0xrtVK1jea44imtwXca8XLG3Ur76Fo3/PZxPWofXcsUKLM0Higeqbtk91eFFe4xnbbA4U7zeVe4dv1Kl7I7xYi1WZopGmlzsMlqeFCWvlMdzq0piFa7Ch3PFJXmjeK6qyk6PetfjHtIf8IM6kPlYpesXlfLIuM1TrtiVOCe4MVieK1bnihcq5b9P5bv6QKZ7CB8LluSjbsoVr1TKfermUClfrZQ/zRXT8W5cpbImj2LH+E2JLxPLctfe0/ApueUX4p11Yvsq5Z4ehdpd5VOZ4sX6CBfg45ni75PyYt9oKiXbU6XcE1nxRCQrMBe35ooqU+zMexb8/WBWPJmShbgScwes2lopuyJbQncDMw4Oum0geW1NiKJecEZiele55TQv1IyO1t5K+ZNM0QgWBDenFE9Wdhw92ZSpPFopH8sVe7AIt2BvnMH9J+Mr+LyIy9tp6ADZ3dhI3EC1tYe/DVPaWsvf7upqaN5am/eDHa2hM3CWYgjHs4bmh3oJba0TtX+N4BxMG6s9v6L7zAQX5jY0GtZffUq59fMHrV85gT/+CS+HmNfwxWhorp1g/504kMR5WWL1GZJNieFIi/aT3VAH+ijpyOlMm9EgrRxnM/NDWnh6LlFhirCZwQsm9tCxjgxpdxZi9oDme95UN1mzgdUR8e22uxt4BH/G0xOtNMpLxjxv+aC7Lqhvi/PqB0BPLmkp/hUp7RxLypyBcf5bH/EizMGmaMT6R6T0LnynNs2ZxC2kfzSs2zhs3qeJTVhH9/63qK3P4WvYjt2JS4Or8SCxnTRQX31zsKmttbOheRGex6OJLbWxL65VfRw/HyDdhQ9gaf0segn3Jp39wy49gSXYQv4jumcs/sTDwev1K2Vb8DIuG7sE0ifqZ9bvEn/paP2nnvZPXI8i+EjicLArcadG+2CnfV96m57LPkrWrbM+G5FtJfvu2frankncRjbrbA1wSt1lffTRRx999PF/jP8CuIm4fhDynqkAAAAASUVORK5CYII=" />';
			$message .= "</div>";
			$message .= "</div>";
			$message .= "</div>";

			$email_body = $message;

			//Set who the message is to be sent from
			$mail->setFrom($senderEmail, $senderName);
			//Set who the message is to be sent to
			$mail->addAddress($receiverEmail, $receiverName);
			//Set the subject line
			$mail->Subject = self::SASS_SUBJECT_PREFIX . self::SASS_SUBJECT_PREFIX . $subject;
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

	public static function sendTutorNewAppointment($db, $tutorId) {
		require ROOT_PATH . "app/plugins/PHPMailer/PHPMailerAutoload.php";


		try {
			$appointemnt = AppointmentFetcher::retrieveSingle($db, $tutorId);
			$tutorUser = UserFetcher::retrieveSingle($db, $appointemnt[AppointmentFetcher::DB_COLUMN_TUTOR_USER_ID]);
			$course = CourseFetcher::retrieveSingle($db, $appointemnt[AppointmentFetcher::DB_COLUMN_COURSE_ID]);

			$subject = self::NEW_SASS_APP_APPOINTMENT_SUBJECT;
			$alternativeEmail = self::DEV_SASS_GMAIL;
			$alternativeName = self::SASS_APP_AUTOMATIC_SYSTEM_ALTERNATIVE_NAME;
			$setViewScheduleLink = "<a href='http://" . $_SERVER['SERVER_NAME'] . "/appointments/" . $appointemnt[AppointmentFetcher::DB_COLUMN_ID] . "' target='_blank' >View Schedule</a><br/>";

			$senderEmail = self::NO_REPLY_EMAIL_PREFIX . $_SERVER['SERVER_NAME'];
			$senderName = self::SASS_APP_AUTOMATIC_SYSTEM_ALTERNATIVE_NAME;
			$receiverEmail = $tutorUser[UserFetcher::DB_COLUMN_EMAIL];
			$receiverName = $tutorUser[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $tutorUser[UserFetcher::DB_COLUMN_LAST_NAME];


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
			$mail->Subject = $subject;


			$message = "<div bgcolor='#fafafa' marginheight='0' marginwidth='0' style='width:100%!important;background:#fafafa'>";
			$message .= "<div style='padding:20px 20px 20px 20px!important;width:550px;margin:0 auto'>";
			$message .= "<h1 style='margin:0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:40px;letter-spacing:-1px;color:#333;font-weight:normal'>
					New Appointment from SASS App</h1>";
			$message .= "<span style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>
					We have a new appointment for you, <strong>$receiverName</strong></span>.";

			$message .= "<div style='margin:20px auto!important;width:510px;padding:20px 20px 20px 20px!important;border:1px solid #ddd!important;border-radius:3px!important;background:#ffffff!important'>";
			$message .= "<p style='margin:5px 0 15px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px;font-weight:normal;color:#333;line-height:20px'>
					Here is an overview the appointment";
			$message .= "<br/><strong>Course name:</strong> " . $course[CourseFetcher::DB_COLUMN_NAME];
			$message .= "<br/><strong>Starts at:</strong> " . $appointemnt[AppointmentFetcher::DB_COLUMN_START_TIME];
			$message .= "<br/><strong>Ends at:</strong> " . $appointemnt[AppointmentFetcher::DB_COLUMN_END_TIME];
			$message .= "<p style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>
							For more details you can visit your $setViewScheduleLink.</p>";
			$message .= "<div style='margin:20px 0;border-top:1px solid #ddd'></div>";
			$message .= "<p style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>Thanks,<br/><strong>$senderName</strong></p>";
			$message .= "</div>";
			$message .= "<div style='margin:20px 0;text-align:center;'>";
			$message .= '<img alt="SASS logo" height="40" width="40" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAC4jAAAuIwF4pT92AAAAB3RJTUUH3gkOEB4GKCv7JwAABOFJREFUWMPt1n+slmUZB/DP9TznvICkiDCcJChUDAMpkR+OR0dkLp0zZz+mbG2t1tYmzZrzZenGakVbnbdcVrjStpxlODVsrTbWahqDR63FiElqwBbNQeuHghyB877nee/+OI9wfDnoH/1D2/v969lzf+/7vq7vfV3f+6aPPvroo48+/hfEGx+NaM6WXIkrgnMTf8VTif0drTTR5NyiBZkVM8PsZ9u+3p2I09CcimV4Hy7BIfwRO9paozUnq8ffj8sSx4Ldwm8zmBTNZZJ7ExdnWfb9rvQErsPPSFPOkNyspP1DptxPTJ6IMBjNGdiYuEnEr0XcR0zD47iwDm4SvoDbE/tSpG8Gf8NX8ZmBhub5KdmIb3S0nu6M6fBcQ/N2YihOidyLT5IvDO+oaM/AsR7lMskdGO5obXDqDDYMas7l5J8PYmUKn+2k1nD996GG5gnJ9AyXI8eLPQd4WKQXRJrg6LKLcGeY9hD5SJKu6GUkzsUabJugrp4NRhqxflDEtXg+Jcd75pc4lGE6BhJTxxO6RqvIPRC5kQnU+xKOZN77S6pXahV6w5iamFWv/+bgIzaniMNSyqW0ANOCwZ4sDqbcbzIcwcLg5lxz4I3xUd9KI6OtIyOjvQ2SLcEdxGMdP36GdCBYfLpK6VhwAjcOal48fqyThg530lCFyli9XRNjDTKO0xrtVK1jea44imtwXca8XLG3Ur76Fo3/PZxPWofXcsUKLM0Higeqbtk91eFFe4xnbbA4U7zeVe4dv1Kl7I7xYi1WZopGmlzsMlqeFCWvlMdzq0piFa7Ch3PFJXmjeK6qyk6PetfjHtIf8IM6kPlYpesXlfLIuM1TrtiVOCe4MVieK1bnihcq5b9P5bv6QKZ7CB8LluSjbsoVr1TKfermUClfrZQ/zRXT8W5cpbImj2LH+E2JLxPLctfe0/ApueUX4p11Yvsq5Z4ehdpd5VOZ4sX6CBfg45ni75PyYt9oKiXbU6XcE1nxRCQrMBe35ooqU+zMexb8/WBWPJmShbgScwes2lopuyJbQncDMw4Oum0geW1NiKJecEZiele55TQv1IyO1t5K+ZNM0QgWBDenFE9Wdhw92ZSpPFopH8sVe7AIt2BvnMH9J+Mr+LyIy9tp6ADZ3dhI3EC1tYe/DVPaWsvf7upqaN5am/eDHa2hM3CWYgjHs4bmh3oJba0TtX+N4BxMG6s9v6L7zAQX5jY0GtZffUq59fMHrV85gT/+CS+HmNfwxWhorp1g/504kMR5WWL1GZJNieFIi/aT3VAH+ijpyOlMm9EgrRxnM/NDWnh6LlFhirCZwQsm9tCxjgxpdxZi9oDme95UN1mzgdUR8e22uxt4BH/G0xOtNMpLxjxv+aC7Lqhvi/PqB0BPLmkp/hUp7RxLypyBcf5bH/EizMGmaMT6R6T0LnynNs2ZxC2kfzSs2zhs3qeJTVhH9/63qK3P4WvYjt2JS4Or8SCxnTRQX31zsKmttbOheRGex6OJLbWxL65VfRw/HyDdhQ9gaf0segn3Jp39wy49gSXYQv4jumcs/sTDwev1K2Vb8DIuG7sE0ifqZ9bvEn/paP2nnvZPXI8i+EjicLArcadG+2CnfV96m57LPkrWrbM+G5FtJfvu2frankncRjbrbA1wSt1lffTRRx999PF/jP8CuIm4fhDynqkAAAAASUVORK5CYII=" />';
			$message .= "</div>";
			$message .= "</div>";
			$message .= "</div>";

			$email_body = $message;

			//Set who the message is to be sent from
			$mail->setFrom($senderEmail, $senderName);
			//Set who the message is to be sent to
			$mail->addAddress($receiverEmail, $receiverName);
			//Set the subject line
			$mail->Subject = self::SASS_SUBJECT_PREFIX . self::SASS_SUBJECT_PREFIX . $subject;
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

	public static function sendNewAccount($db, $id, $senderEmail, $senderName, $receiverEmail, $receiverName) {
		require ROOT_PATH . "app/plugins/PHPMailer/PHPMailerAutoload.php";

		$getString = User::generateNewPasswordString($db, $id);
		$subject = "New Account";
		$alternativeEmail = self::DEV_SASS_GMAIL;
		$alternativeName = self::SASS_APP_AUTOMATIC_SYSTEM_ALTERNATIVE_NAME;
		$setPasswordLink = "<a href='http://" . $_SERVER['SERVER_NAME'] . "/login/set/" . $id . "/" . $getString . "' target='_blank' >Set SASS App Password</a><br/>";
		$sassPageLogin = "<a href='http://" . $_SERVER['SERVER_NAME'] . "/login/' target='_blank' >log in</a>";

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
			$mail->Subject = $subject;

			$message = "<div bgcolor='#fafafa' marginheight='0' marginwidth='0' style='width:100%!important;background:#fafafa'>";
			$message .= "<div style='padding:20px 20px 20px 20px!important;width:550px;margin:0 auto'>";
			$message .= "<h1 style='margin:0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:40px;letter-spacing:-1px;color:#333;font-weight:normal'>
					Welcome to SASS App</h1>";
			$message .= "<span style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>
					You&#39;ve been just invited to the SASS App, <strong>$receiverName</strong></span>.";

			$message .= "<div style='margin:20px auto!important;width:510px;padding:20px 20px 20px 20px!important;border:1px solid #ddd!important;border-radius:3px!important;background:#ffffff!important'>";
			$message .= "<p style='margin:5px 0 15px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px;font-weight:normal;color:#333;line-height:20px'>You can use the following link to activate your account. ";
			$message .= "<br/>" . $setPasswordLink . "</p>";
			$message .= "<p style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>
							You will be prompted for a password for your account.<br/>When you are done, you can $sassPageLogin at SASS App<br/> using this email address and the password you choose.</p>";
			$message .= "<div style='margin:20px 0;border-top:1px solid #ddd'></div>";
			$message .= "<p style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>Thanks,<br/><strong>$senderName</strong></p>";
			$message .= "</div>";
			$message .= "<div style='margin:20px 0;text-align:center;'>";
			$message .= '<img alt="SASS logo" height="40" width="40" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAC4jAAAuIwF4pT92AAAAB3RJTUUH3gkOEB4GKCv7JwAABOFJREFUWMPt1n+slmUZB/DP9TznvICkiDCcJChUDAMpkR+OR0dkLp0zZz+mbG2t1tYmzZrzZenGakVbnbdcVrjStpxlODVsrTbWahqDR63FiElqwBbNQeuHghyB877nee/+OI9wfDnoH/1D2/v969lzf+/7vq7vfV3f+6aPPvroo48+/hfEGx+NaM6WXIkrgnMTf8VTif0drTTR5NyiBZkVM8PsZ9u+3p2I09CcimV4Hy7BIfwRO9paozUnq8ffj8sSx4Ldwm8zmBTNZZJ7ExdnWfb9rvQErsPPSFPOkNyspP1DptxPTJ6IMBjNGdiYuEnEr0XcR0zD47iwDm4SvoDbE/tSpG8Gf8NX8ZmBhub5KdmIb3S0nu6M6fBcQ/N2YihOidyLT5IvDO+oaM/AsR7lMskdGO5obXDqDDYMas7l5J8PYmUKn+2k1nD996GG5gnJ9AyXI8eLPQd4WKQXRJrg6LKLcGeY9hD5SJKu6GUkzsUabJugrp4NRhqxflDEtXg+Jcd75pc4lGE6BhJTxxO6RqvIPRC5kQnU+xKOZN77S6pXahV6w5iamFWv/+bgIzaniMNSyqW0ANOCwZ4sDqbcbzIcwcLg5lxz4I3xUd9KI6OtIyOjvQ2SLcEdxGMdP36GdCBYfLpK6VhwAjcOal48fqyThg530lCFyli9XRNjDTKO0xrtVK1jea44imtwXca8XLG3Ur76Fo3/PZxPWofXcsUKLM0Higeqbtk91eFFe4xnbbA4U7zeVe4dv1Kl7I7xYi1WZopGmlzsMlqeFCWvlMdzq0piFa7Ch3PFJXmjeK6qyk6PetfjHtIf8IM6kPlYpesXlfLIuM1TrtiVOCe4MVieK1bnihcq5b9P5bv6QKZ7CB8LluSjbsoVr1TKfermUClfrZQ/zRXT8W5cpbImj2LH+E2JLxPLctfe0/ApueUX4p11Yvsq5Z4ehdpd5VOZ4sX6CBfg45ni75PyYt9oKiXbU6XcE1nxRCQrMBe35ooqU+zMexb8/WBWPJmShbgScwes2lopuyJbQncDMw4Oum0geW1NiKJecEZiele55TQv1IyO1t5K+ZNM0QgWBDenFE9Wdhw92ZSpPFopH8sVe7AIt2BvnMH9J+Mr+LyIy9tp6ADZ3dhI3EC1tYe/DVPaWsvf7upqaN5am/eDHa2hM3CWYgjHs4bmh3oJba0TtX+N4BxMG6s9v6L7zAQX5jY0GtZffUq59fMHrV85gT/+CS+HmNfwxWhorp1g/504kMR5WWL1GZJNieFIi/aT3VAH+ijpyOlMm9EgrRxnM/NDWnh6LlFhirCZwQsm9tCxjgxpdxZi9oDme95UN1mzgdUR8e22uxt4BH/G0xOtNMpLxjxv+aC7Lqhvi/PqB0BPLmkp/hUp7RxLypyBcf5bH/EizMGmaMT6R6T0LnynNs2ZxC2kfzSs2zhs3qeJTVhH9/63qK3P4WvYjt2JS4Or8SCxnTRQX31zsKmttbOheRGex6OJLbWxL65VfRw/HyDdhQ9gaf0segn3Jp39wy49gSXYQv4jumcs/sTDwev1K2Vb8DIuG7sE0ifqZ9bvEn/paP2nnvZPXI8i+EjicLArcadG+2CnfV96m57LPkrWrbM+G5FtJfvu2frankncRjbrbA1wSt1lffTRRx999PF/jP8CuIm4fhDynqkAAAAASUVORK5CYII=" />';
			$message .= "</div>";
			$message .= "</div>";
			$message .= "</div>";

			$email_body = $message;

			//Set who the message is to be sent from
			$mail->setFrom($senderEmail, $senderName);
			//Set who the message is to be sent to
			$mail->addAddress($receiverEmail, $receiverName);
			//Set the subject line
			$mail->Subject = self::SASS_SUBJECT_PREFIX . $subject;
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

	public static function sendRecover($db, $email) {
		User::validateExistingEmail($db, $email, UserFetcher::DB_TABLE);
		$user = UserFetcher::retrieveUsingEmail($db, $email);
		if ($user[UserFetcher::DB_COLUMN_ACTIVE] != 1) throw new Exception("Sorry, you account has been activated.");
		$id = $user[UserFetcher::DB_COLUMN_ID];
		$name = $user[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $user[UserFetcher::DB_COLUMN_LAST_NAME];
		$genString = User::generateNewPasswordString($db, $id);

		$subject = "SASS App Password recovery";
		$alternativeEmail = self::DEV_SASS_GMAIL;
		$alternativeName = self::SASS_APP_AUTOMATIC_SYSTEM_ALTERNATIVE_NAME;
		$passwordRecoveryLink = "<a href='http://" . $_SERVER['SERVER_NAME'] . "/login/recover/" . $id . "/" . $genString . "' target='_blank' >Reset Password</a>";
		$sassPageLogin = "<a href='http://" . $_SERVER['SERVER_NAME'] . "/login/' target='_blank' >log in</a>";
		$sassPageRecover = "<a href='http://" . $_SERVER['SERVER_NAME'] . "/login/confirm-password' target='_blank' >password recovery</a>";
		$senderEmail = self::NO_REPLY_EMAIL_PREFIX . $_SERVER['SERVER_NAME'];
		$senderName = self::SASS_APP_AUTOMATIC_SYSTEM_ALTERNATIVE_NAME;
		$receiverEmail = $email;
		$receiverName = $name;
		try {
			require ROOT_PATH . "app/plugins/PHPMailer/PHPMailerAutoload.php";
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
			$mail->Subject = $subject;

			$message = "<div bgcolor='#fafafa' marginheight='0' marginwidth='0' style='width:100%!important;background:#fafafa'>";
			$message .= "<div style='padding:20px 20px 20px 20px!important;width:550px;margin:0 auto'>";
			$message .= "<h1 style='margin:0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:40px;letter-spacing:-1px;color:#333;font-weight:normal'>
					SASS App Password Recovery</h1>";
			$message .= "<span style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>
					We heard that you lost your SASS App password. Sorry about that!</span>.";

			$message .= "<div style='margin:20px auto!important;width:510px;padding:20px 20px 20px 20px!important;border:1px solid #ddd!important;border-radius:3px!important;background:#ffffff!important'>";
			$message .= "<p style='margin:5px 0 15px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px;font-weight:normal;color:#333;line-height:20px'>";
			$message .= "But don&#39;t worry! You can use the following link within the next hour to reset your password:";
			$message .= "<br/>" . $passwordRecoveryLink . "</p>";
			$message .= "<p style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>";
			$message .= "You will be prompted for a password for your account.<br/>When you are done, you can $sassPageLogin at SASS App <br/>using this email address and the new password you choose.</p>";
			$message .= "<p style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>";
			$message .= "If you don&#39;t use this link within 1 hour, it will expire. Please check your spam folder if the email does not appear within a few minutes. To get a new password reset link, please visit and insert your email: ";
			$message .= "<br/>" . $sassPageRecover . "</p>";
			$message .= "<div style='margin:20px 0;border-top:1px solid #ddd'></div>";
			$message .= "<p style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>Thanks,<br/><strong>$senderName</strong></p>";
			$message .= "</div>";
			$message .= "<div style='margin:20px 0;text-align:center;'>";
			$message .= '<img alt="SASS logo" height="40" width="40" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAC4jAAAuIwF4pT92AAAAB3RJTUUH3gkOEB4GKCv7JwAABOFJREFUWMPt1n+slmUZB/DP9TznvICkiDCcJChUDAMpkR+OR0dkLp0zZz+mbG2t1tYmzZrzZenGakVbnbdcVrjStpxlODVsrTbWahqDR63FiElqwBbNQeuHghyB877nee/+OI9wfDnoH/1D2/v969lzf+/7vq7vfV3f+6aPPvroo48+/hfEGx+NaM6WXIkrgnMTf8VTif0drTTR5NyiBZkVM8PsZ9u+3p2I09CcimV4Hy7BIfwRO9paozUnq8ffj8sSx4Ldwm8zmBTNZZJ7ExdnWfb9rvQErsPPSFPOkNyspP1DptxPTJ6IMBjNGdiYuEnEr0XcR0zD47iwDm4SvoDbE/tSpG8Gf8NX8ZmBhub5KdmIb3S0nu6M6fBcQ/N2YihOidyLT5IvDO+oaM/AsR7lMskdGO5obXDqDDYMas7l5J8PYmUKn+2k1nD996GG5gnJ9AyXI8eLPQd4WKQXRJrg6LKLcGeY9hD5SJKu6GUkzsUabJugrp4NRhqxflDEtXg+Jcd75pc4lGE6BhJTxxO6RqvIPRC5kQnU+xKOZN77S6pXahV6w5iamFWv/+bgIzaniMNSyqW0ANOCwZ4sDqbcbzIcwcLg5lxz4I3xUd9KI6OtIyOjvQ2SLcEdxGMdP36GdCBYfLpK6VhwAjcOal48fqyThg530lCFyli9XRNjDTKO0xrtVK1jea44imtwXca8XLG3Ur76Fo3/PZxPWofXcsUKLM0Higeqbtk91eFFe4xnbbA4U7zeVe4dv1Kl7I7xYi1WZopGmlzsMlqeFCWvlMdzq0piFa7Ch3PFJXmjeK6qyk6PetfjHtIf8IM6kPlYpesXlfLIuM1TrtiVOCe4MVieK1bnihcq5b9P5bv6QKZ7CB8LluSjbsoVr1TKfermUClfrZQ/zRXT8W5cpbImj2LH+E2JLxPLctfe0/ApueUX4p11Yvsq5Z4ehdpd5VOZ4sX6CBfg45ni75PyYt9oKiXbU6XcE1nxRCQrMBe35ooqU+zMexb8/WBWPJmShbgScwes2lopuyJbQncDMw4Oum0geW1NiKJecEZiele55TQv1IyO1t5K+ZNM0QgWBDenFE9Wdhw92ZSpPFopH8sVe7AIt2BvnMH9J+Mr+LyIy9tp6ADZ3dhI3EC1tYe/DVPaWsvf7upqaN5am/eDHa2hM3CWYgjHs4bmh3oJba0TtX+N4BxMG6s9v6L7zAQX5jY0GtZffUq59fMHrV85gT/+CS+HmNfwxWhorp1g/504kMR5WWL1GZJNieFIi/aT3VAH+ijpyOlMm9EgrRxnM/NDWnh6LlFhirCZwQsm9tCxjgxpdxZi9oDme95UN1mzgdUR8e22uxt4BH/G0xOtNMpLxjxv+aC7Lqhvi/PqB0BPLmkp/hUp7RxLypyBcf5bH/EizMGmaMT6R6T0LnynNs2ZxC2kfzSs2zhs3qeJTVhH9/63qK3P4WvYjt2JS4Or8SCxnTRQX31zsKmttbOheRGex6OJLbWxL65VfRw/HyDdhQ9gaf0segn3Jp39wy49gSXYQv4jumcs/sTDwev1K2Vb8DIuG7sE0ifqZ9bvEn/paP2nnvZPXI8i+EjicLArcadG+2CnfV96m57LPkrWrbM+G5FtJfvu2frankncRjbrbA1wSt1lffTRRx999PF/jP8CuIm4fhDynqkAAAAASUVORK5CYII=" />';
			$message .= "</div>";
			$message .= "</div>";
			$message .= "</div>";

			$mail->msgHTML($message);
			//Attach an image file
			//$mail->addAttachment('images/phpmailer_mini.gif');

			$mail->send();
		} catch (Exception $e) {
			throw new Exception("Could not send email. Please re-try to recover your email.");
		}


	}
}