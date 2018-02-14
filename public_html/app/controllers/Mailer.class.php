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

require_once ROOT_PATH . 'config/App.class.php';
require ROOT_PATH . 'vendor/autoload.php';
use Mailgun\Mailgun;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Rizart Dokollari
 * @author George Skarlatos
 * @since 9/14/2014
 */
class Mailer
{
    const EMAIL_DEV_SASS = "dev.sass.ms@gmail.com";
    const EMAIL_DEV_NAME_SASS = "SASS Developers";
    const SASS_APP_AUTOMATIC_SYSTEM_DEVELOPERS = "SASS App | Developers";
    const SASS_APP_AUTOMATIC_SYSTEM = "SASS App | Automatic System";
    const SUBJECT_NEW_SASS_APP_APPOINTMENT = "New Appointment";
    const SUBJECT_NEW_SASS_APP_REPORT_PENDING = "New Report Pending";
    const SUBJECT_SYSTEM_MESSAGE = "System message";
    const SUBJECT_PREFIX = 'SASS App | ';
    const NO_REPLY_EMAIL_PREFIX = "no-reply@";
    const DATE_FORMAT = "M j Y";
    const HOUR_FORMAT = "g:i A";
    const EMAIL_MAILGUN = "mg.sass-app.in";

    public static $dateTimeLastSentMail;

    public static function sendTutorNewReportsCronOnly($appointmentData)
    {
        try {
            $tutorUser = UserFetcher::retrieveSingle($appointmentData[AppointmentFetcher::DB_COLUMN_TUTOR_USER_ID]);
            $course = CourseFetcher::retrieveSingle($appointmentData[AppointmentFetcher::DB_COLUMN_COURSE_ID]);

            $subject = self::SUBJECT_PREFIX . self::SUBJECT_NEW_SASS_APP_REPORT_PENDING;
            $alternativeEmail = self::EMAIL_DEV_SASS;
            $alternativeName = self::SASS_APP_AUTOMATIC_SYSTEM_DEVELOPERS;
            $reportLink = "<a style='background-color:#008dd0;color:#fff;border-radius:4px;display:block;
		text-decoration:none;margin-top:30px;margin-bottom:15px;margin-right:0px;margin-left:0px;padding-top:20px;
		padding-bottom:20px;padding-right:20px;padding-left:20px;text-align:center'
		href='https://" . App::getHostname() . "/appointments/" .
                $appointmentData[AppointmentFetcher::DB_COLUMN_ID] . "' target='_blank' >View Report</a><br/>";
            $appointmentStart = new DateTime($appointmentData[AppointmentFetcher::DB_COLUMN_START_TIME]);
            $appointmentEnd = new DateTime($appointmentData[AppointmentFetcher::DB_COLUMN_END_TIME]);
            $senderEmail = self::NO_REPLY_EMAIL_PREFIX . App::getHostname();
            $senderName = self::SASS_APP_AUTOMATIC_SYSTEM;
            $receiverEmail = $tutorUser[UserFetcher::DB_COLUMN_EMAIL];
            $receiverName = $tutorUser[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $tutorUser[UserFetcher::DB_COLUMN_LAST_NAME];;

            require_once ROOT_PATH . "plugins/PHPMailer/PHPMailerAutoload.php";

            //Create a new PHPMailer instance
            $mail = new PHPMailer();
            // Set PHPMailer to use the sendmail transport
            //Set who the message is to be sent from
            $mail->setFrom($senderEmail, self::SASS_APP_AUTOMATIC_SYSTEM);
            //Set an alternative reply-to address
            $mail->addReplyTo($alternativeEmail, $alternativeName);
            //Set who the message is to be sent to
            $mail->addAddress($receiverEmail, $receiverName);
            //Set the subject line
            $mail->Subject = $subject;


            $message = "<div bgcolor='#fafafa' marginheight='0' marginwidth='0' style='width:100%!important;background:#fafafa'>";
            $message .= "<div style='padding:20px 20px 20px 20px!important;width:550px;margin:0 auto'>";
            $message .= "<h1 style='margin:0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:40px;letter-spacing:-1px;color:#333;font-weight:normal'>
					New Pending Report - SASS App</h1>";
            $message .= "<span style='color: #f0ad4e; margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'background-color>
					We have new <span style='color:#f0ad4e!important;'>pending</span> report(s) for you, <strong>$receiverName</strong></span>.";

            $message .= "<div style='margin:20px auto!important;width:510px;padding:20px 20px 20px 20px!important;border:1px solid #ddd!important;border-radius:3px!important;background:#fafafa!important'>";
            $message .= "<p style='margin:5px 0 15px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px;font-weight:normal;color:#333;line-height:20px'>
					Please follow the below link and fill in the report(s).<br/>$reportLink</p>";
            $message .= "<p style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>
							The report(s) is intended for the appointment with data:";
            $message .= "<br/><strong>Course:</strong> " . $course[CourseFetcher::DB_COLUMN_CODE] . " " . $course[CourseFetcher::DB_COLUMN_NAME];
            $message .= "<br/><strong>Date:</strong> " . $appointmentStart->format(self::DATE_FORMAT);
            $message .= "<br/><strong>Hour:</strong> " . $appointmentStart->format(self::HOUR_FORMAT) . " - " . $appointmentEnd->format(self::HOUR_FORMAT) . "</p>";

            $message .= "<div style='margin:20px 0;border-top:1px solid #ddd'></div>";
            $message .= "<p style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>Thanks,<br/><strong>$senderName</strong></p>";
            $message .= "</div>";
            $message .= "<div style='margin:20px 0;text-align:center;width:510px;'>";
            $message .= '<img alt="SASS logo" height="40" width="40" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAC4jAAAuIwF4pT92AAAAB3RJTUUH3gkOEB4GKCv7JwAABOFJREFUWMPt1n+slmUZB/DP9TznvICkiDCcJChUDAMpkR+OR0dkLp0zZz+mbG2t1tYmzZrzZenGakVbnbdcVrjStpxlODVsrTbWahqDR63FiElqwBbNQeuHghyB877nee/+OI9wfDnoH/1D2/v969lzf+/7vq7vfV3f+6aPPvroo48+/hfEGx+NaM6WXIkrgnMTf8VTif0drTTR5NyiBZkVM8PsZ9u+3p2I09CcimV4Hy7BIfwRO9paozUnq8ffj8sSx4Ldwm8zmBTNZZJ7ExdnWfb9rvQErsPPSFPOkNyspP1DptxPTJ6IMBjNGdiYuEnEr0XcR0zD47iwDm4SvoDbE/tSpG8Gf8NX8ZmBhub5KdmIb3S0nu6M6fBcQ/N2YihOidyLT5IvDO+oaM/AsR7lMskdGO5obXDqDDYMas7l5J8PYmUKn+2k1nD996GG5gnJ9AyXI8eLPQd4WKQXRJrg6LKLcGeY9hD5SJKu6GUkzsUabJugrp4NRhqxflDEtXg+Jcd75pc4lGE6BhJTxxO6RqvIPRC5kQnU+xKOZN77S6pXahV6w5iamFWv/+bgIzaniMNSyqW0ANOCwZ4sDqbcbzIcwcLg5lxz4I3xUd9KI6OtIyOjvQ2SLcEdxGMdP36GdCBYfLpK6VhwAjcOal48fqyThg530lCFyli9XRNjDTKO0xrtVK1jea44imtwXca8XLG3Ur76Fo3/PZxPWofXcsUKLM0Higeqbtk91eFFe4xnbbA4U7zeVe4dv1Kl7I7xYi1WZopGmlzsMlqeFCWvlMdzq0piFa7Ch3PFJXmjeK6qyk6PetfjHtIf8IM6kPlYpesXlfLIuM1TrtiVOCe4MVieK1bnihcq5b9P5bv6QKZ7CB8LluSjbsoVr1TKfermUClfrZQ/zRXT8W5cpbImj2LH+E2JLxPLctfe0/ApueUX4p11Yvsq5Z4ehdpd5VOZ4sX6CBfg45ni75PyYt9oKiXbU6XcE1nxRCQrMBe35ooqU+zMexb8/WBWPJmShbgScwes2lopuyJbQncDMw4Oum0geW1NiKJecEZiele55TQv1IyO1t5K+ZNM0QgWBDenFE9Wdhw92ZSpPFopH8sVe7AIt2BvnMH9J+Mr+LyIy9tp6ADZ3dhI3EC1tYe/DVPaWsvf7upqaN5am/eDHa2hM3CWYgjHs4bmh3oJba0TtX+N4BxMG6s9v6L7zAQX5jY0GtZffUq59fMHrV85gT/+CS+HmNfwxWhorp1g/504kMR5WWL1GZJNieFIi/aT3VAH+ijpyOlMm9EgrRxnM/NDWnh6LlFhirCZwQsm9tCxjgxpdxZi9oDme95UN1mzgdUR8e22uxt4BH/G0xOtNMpLxjxv+aC7Lqhvi/PqB0BPLmkp/hUp7RxLypyBcf5bH/EizMGmaMT6R6T0LnynNs2ZxC2kfzSs2zhs3qeJTVhH9/63qK3P4WvYjt2JS4Or8SCxnTRQX31zsKmttbOheRGex6OJLbWxL65VfRw/HyDdhQ9gaf0segn3Jp39wy49gSXYQv4jumcs/sTDwev1K2Vb8DIuG7sE0ifqZ9bvEn/paP2nnvZPXI8i+EjicLArcadG+2CnfV96m57LPkrWrbM+G5FtJfvu2frankncRjbrbA1wSt1lffTRRx999PF/jP8CuIm4fhDynqkAAAAASUVORK5CYII=" />';
            $message .= "</div>";
            $message .= "</div>";
            $message .= "</div>";
            $mail->msgHTML($message);

            self::safelySendMail($mail);


        } catch (phpmailerException $e) {
            throw new Exception("PHPMailer error: " . $e->errorMessage()); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            throw new Exception("Something went wrong with mail. Email was not send to tutor for reports."); //Pretty error messages from PHPMailer
        }
    }

    /**
     * @param $mail
     * @throws Exception
     * @internal param $db
     */
    public static function safelySendMail($mail)
    {
        /* Note: set_time_limit() does not work with safe_mode enabled */
        while (1 == 1) {
//			set_time_limit(30); // sets (or resets) maximum  execution time to 30 seconds)
            // has been set by system to 120 seconds. un-modified
            // .... put code to process in here

            if (MailerFetcher::canSendMail()) {
                MailerFetcher::updateMailSent();
                $mail->send();
                break;
            }

            usleep(1000000); // sleep for 1 million micro seconds - will not work with Windows servers / PHP4
            // sleep(1); // sleep for 1 seconds (use with Windows servers / PHP4
            if (1 != 1) {
                break;
            }
        }
    }

    public static function sendTutorNewAppointment($tutorId, $secretaryName)
    {
        require_once ROOT_PATH . "plugins/PHPMailer/PHPMailerAutoload.php";


        try {
            $appointment = AppointmentFetcher::retrieveSingle($tutorId);
            $appointmentStart = new DateTime($appointment[AppointmentFetcher::DB_COLUMN_START_TIME]);
            $appointmentEnd = new DateTime($appointment[AppointmentFetcher::DB_COLUMN_END_TIME]);
            $tutorUser = UserFetcher::retrieveSingle($appointment[AppointmentFetcher::DB_COLUMN_TUTOR_USER_ID]);
            $course = CourseFetcher::retrieveSingle($appointment[AppointmentFetcher::DB_COLUMN_COURSE_ID]);

            $subject = self::SUBJECT_PREFIX . self::SUBJECT_NEW_SASS_APP_APPOINTMENT;
            $alternativeEmail = self::EMAIL_DEV_SASS;
            $alternativeName = self::SASS_APP_AUTOMATIC_SYSTEM_DEVELOPERS;
            $setViewScheduleLink = "<a style='background-color:#008dd0;color:#fff;border-radius:4px;display:block;
		text-decoration:none;margin-top:30px;margin-bottom:15px;margin-right:0px;margin-left:0px;padding-top:20px;
		padding-bottom:20px;padding-right:20px;padding-left:20px;text-align:center'
		href='" . App::getDomainName() . "/appointments/" . $appointment[AppointmentFetcher::DB_COLUMN_ID] . "' target='_blank' >Appointment Details</a>";

            $senderEmail = self::NO_REPLY_EMAIL_PREFIX . $_SERVER['SERVER_NAME'];
            $senderName = $secretaryName;
            $receiverEmail = $tutorUser[UserFetcher::DB_COLUMN_EMAIL];
            $receiverName = $tutorUser[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $tutorUser[UserFetcher::DB_COLUMN_LAST_NAME];


            //Create a new PHPMailer instance
            $mail = new PHPMailer();
            // Set PHPMailer to use the sendmail transport
            //Set who the message is to be sent from
            $mail->setFrom($senderEmail, self::SASS_APP_AUTOMATIC_SYSTEM);
            //Set an alternative reply-to address
            $mail->addReplyTo($alternativeEmail, $alternativeName);
            //Set who the message is to be sent to
            $mail->addAddress($receiverEmail, $receiverName);
            //Set the subject line
            $mail->Subject = $subject;


            $message = "<html><div bgcolor='#fafafa' marginheight='0' marginwidth='0' style='width:100%!important;background:#fafafa'>";
            $message .= "<div style='padding:20px 20px 20px 20px!important;width:550px;margin:0 auto'>";
            $message .= "<h1 style='margin:0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:40px;letter-spacing:-1px;color:#333;font-weight:normal'>
					New Appointment from SASS App</h1>";
            $message .= "<span style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>
					We have a new appointment for you, <strong>$receiverName</strong></span>.";

            $message .= "<div style='margin:20px auto!important;width:510px;padding:20px 20px 20px 20px!important;border:1px solid #ddd!important;border-radius:3px!important;background:#ffffff!important'>";
            $message .= "<p style='margin:5px 0 15px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px;font-weight:normal;color:#333;line-height:20px'>
					Here is an overview the appointment";
            $message .= "<br/><strong>Course:</strong> " . $course[CourseFetcher::DB_COLUMN_CODE] . " " . $course[CourseFetcher::DB_COLUMN_NAME];
            $message .= "<br/><strong>Date:</strong> " . $appointmentStart->format(self::DATE_FORMAT);
            $message .= "<br/><strong>Hour:</strong> " . $appointmentStart->format(self::HOUR_FORMAT) . " - " . $appointmentEnd->format(self::HOUR_FORMAT) . "</p>";

            $message .= "<p style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>
							For more details you can visit your $setViewScheduleLink</p>";
            $message .= "<div style='margin:20px 0;border-top:1px solid #ddd'></div>";
            $message .= "<p style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>Thanks,<br/><strong>$senderName</strong></p>";
            $message .= "</div>";
            $message .= "<div style='margin:20px 0;text-align:center;'>";
            $message .= '<img alt="SASS logo" height="40" width="40" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAC4jAAAuIwF4pT92AAAAB3RJTUUH3gkOEB4GKCv7JwAABOFJREFUWMPt1n+slmUZB/DP9TznvICkiDCcJChUDAMpkR+OR0dkLp0zZz+mbG2t1tYmzZrzZenGakVbnbdcVrjStpxlODVsrTbWahqDR63FiElqwBbNQeuHghyB877nee/+OI9wfDnoH/1D2/v969lzf+/7vq7vfV3f+6aPPvroo48+/hfEGx+NaM6WXIkrgnMTf8VTif0drTTR5NyiBZkVM8PsZ9u+3p2I09CcimV4Hy7BIfwRO9paozUnq8ffj8sSx4Ldwm8zmBTNZZJ7ExdnWfb9rvQErsPPSFPOkNyspP1DptxPTJ6IMBjNGdiYuEnEr0XcR0zD47iwDm4SvoDbE/tSpG8Gf8NX8ZmBhub5KdmIb3S0nu6M6fBcQ/N2YihOidyLT5IvDO+oaM/AsR7lMskdGO5obXDqDDYMas7l5J8PYmUKn+2k1nD996GG5gnJ9AyXI8eLPQd4WKQXRJrg6LKLcGeY9hD5SJKu6GUkzsUabJugrp4NRhqxflDEtXg+Jcd75pc4lGE6BhJTxxO6RqvIPRC5kQnU+xKOZN77S6pXahV6w5iamFWv/+bgIzaniMNSyqW0ANOCwZ4sDqbcbzIcwcLg5lxz4I3xUd9KI6OtIyOjvQ2SLcEdxGMdP36GdCBYfLpK6VhwAjcOal48fqyThg530lCFyli9XRNjDTKO0xrtVK1jea44imtwXca8XLG3Ur76Fo3/PZxPWofXcsUKLM0Higeqbtk91eFFe4xnbbA4U7zeVe4dv1Kl7I7xYi1WZopGmlzsMlqeFCWvlMdzq0piFa7Ch3PFJXmjeK6qyk6PetfjHtIf8IM6kPlYpesXlfLIuM1TrtiVOCe4MVieK1bnihcq5b9P5bv6QKZ7CB8LluSjbsoVr1TKfermUClfrZQ/zRXT8W5cpbImj2LH+E2JLxPLctfe0/ApueUX4p11Yvsq5Z4ehdpd5VOZ4sX6CBfg45ni75PyYt9oKiXbU6XcE1nxRCQrMBe35ooqU+zMexb8/WBWPJmShbgScwes2lopuyJbQncDMw4Oum0geW1NiKJecEZiele55TQv1IyO1t5K+ZNM0QgWBDenFE9Wdhw92ZSpPFopH8sVe7AIt2BvnMH9J+Mr+LyIy9tp6ADZ3dhI3EC1tYe/DVPaWsvf7upqaN5am/eDHa2hM3CWYgjHs4bmh3oJba0TtX+N4BxMG6s9v6L7zAQX5jY0GtZffUq59fMHrV85gT/+CS+HmNfwxWhorp1g/504kMR5WWL1GZJNieFIi/aT3VAH+ijpyOlMm9EgrRxnM/NDWnh6LlFhirCZwQsm9tCxjgxpdxZi9oDme95UN1mzgdUR8e22uxt4BH/G0xOtNMpLxjxv+aC7Lqhvi/PqB0BPLmkp/hUp7RxLypyBcf5bH/EizMGmaMT6R6T0LnynNs2ZxC2kfzSs2zhs3qeJTVhH9/63qK3P4WvYjt2JS4Or8SCxnTRQX31zsKmttbOheRGex6OJLbWxL65VfRw/HyDdhQ9gaf0segn3Jp39wy49gSXYQv4jumcs/sTDwev1K2Vb8DIuG7sE0ifqZ9bvEn/paP2nnvZPXI8i+EjicLArcadG+2CnfV96m57LPkrWrbM+G5FtJfvu2frankncRjbrbA1wSt1lffTRRx999PF/jP8CuIm4fhDynqkAAAAASUVORK5CYII=" />';
            $message .= "</div>";
            $message .= "</div>";
            $message .= "</div></html>";

            $email_body = $message;

            //Set who the message is to be sent from
            $mail->setFrom($senderEmail, self::SASS_APP_AUTOMATIC_SYSTEM);
            //Set who the message is to be sent to
            $mail->addAddress($receiverEmail, $receiverName);
            //Set the subject line
            $mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($email_body);
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.gif');

            self::safelySendMail($mail);

        } catch (phpmailerException $e) {
            throw new Exception("PHPMailer error: " . $e->errorMessage()); //Pretty error messages from PHPMailer
        } catch
        (Exception $e) {
            throw new Exception("Something went wrong with mail. Please re-send mail to user for setting password."); //Pretty error messages from PHPMailer
        }
    }

    public static function sendNewAccount($newUserId, $receiverEmail, $receiverName)
    {
        # First, instantiate the SDK with your API credentials and define your domain.
        $mg = new Mailgun(App::getMailgunKey());
        $domain = App::getMailgunDomain();

        // Load mail template
        $emailVerificationTemplate = file_get_contents(ROOT_PATH . 'mail/templates/verify_email.html');
        $getString = User::generateNewPasswordString($newUserId);
        $setPasswordLink = App::getDomainName() . "/login/set/" . $newUserId . "/" . $getString;

        # Now, compose and send the message.
        $mg->sendMessage($domain,
            [
                'from'                => "SASS App admin@" . App::getHostname(),
                'to'                  => $receiverEmail,
                'subject'             => 'Welcome',
                'text'                => 'Your mail does not support html',
                'html'                => $emailVerificationTemplate,
                'recipient-variables' => '{"' . $receiverEmail . '": {"id":' . $newUserId . ',"setPasswordLink":"' . $setPasswordLink . '","fullName":"' . $receiverName . '"}}'
            ]);
    }

    /** @return ResponseInterface */
    public static function sendRecover($email)
    {
        User::validateExistingEmail($email, UserFetcher::DB_TABLE);
        $user = UserFetcher::retrieveUsingEmail($email);
        if ($user[UserFetcher::DB_COLUMN_ACTIVE] != 1) {
            throw new Exception("Sorry, you account has been de-activated.");
        }
        $userId = $user[UserFetcher::DB_COLUMN_ID];
        $receiverName = $user[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $user[UserFetcher::DB_COLUMN_LAST_NAME];
        $genString = User::generateNewPasswordString($userId);

        # First, instantiate the SDK with your API credentials and define your domain.
        $mg = new Mailgun(App::getMailgunKey());
        $domain = App::getMailgunDomain();

        // Load mail template
        $emailVerificationTemplate = file_get_contents(ROOT_PATH . 'mail/templates/verify_recovery.html');
        $verifyAccountRecoveryLink = App::getDomainName() . "/login/set/" . $userId . "/" . $genString;

        try {
            # Now, compose and send the message.
            $mg->sendMessage($domain,
                [
                    'from'                => "SASS App admin@" . App::getHostname(),
                    'to'                  => $email,
                    'subject'             => 'SASS Account Recovery',
                    'text'                => 'Your mail does not support html',
                    'html'                => $emailVerificationTemplate,
                    'recipient-variables' => '{"' . $email . '": {"id":' . $userId . ',"verifyAccountRecoveryLink":"' .
                        $verifyAccountRecoveryLink . '","fullName":"' . $receiverName . '"}}'
                ]);
        } catch (Exception $e) {
            throw new Exception("Sorry, we could not send your recovery email. Please contact the secretariat at your earliest
			convenience or submit a bug issue <a href='" . App::getGithubNewIssueUrl() . "' target='_blank'>here</a>.");
        }

        return $mg->getLastResponse();
    }

    public static function sendDevelopers($systemMessage, $pathFileMessage)
    {
        date_default_timezone_set('Europe/Athens');

        try {
            $subject = self::SUBJECT_PREFIX . self::SUBJECT_SYSTEM_MESSAGE;
            $alternativeEmail = self::EMAIL_DEV_SASS;
            $alternativeName = self::SASS_APP_AUTOMATIC_SYSTEM_DEVELOPERS;
            $dateGenerated = new DateTime();
            $senderEmail = self::NO_REPLY_EMAIL_PREFIX . App::getHostname();
            $senderName = self::SASS_APP_AUTOMATIC_SYSTEM;
            $receiverEmail = self::EMAIL_DEV_SASS;
            $receiverName = self::EMAIL_DEV_NAME_SASS;

            require_once ROOT_PATH . "plugins/PHPMailer/PHPMailerAutoload.php";

            //Create a new PHPMailer instance
            $mail = new PHPMailer();
            // Set PHPMailer to use the sendmail transport
            //Set who the message is to be sent from
            $mail->setFrom($senderEmail, self::SASS_APP_AUTOMATIC_SYSTEM);
            //Set an alternative reply-to address
            $mail->addReplyTo($alternativeEmail, $alternativeName);
            //Set who the message is to be sent to
            $mail->addAddress($receiverEmail, $receiverName);
            //Set the subject line
            $mail->Subject = $subject;


            $message = "<div bgcolor='#fafafa' marginheight='0' marginwidth='0' style='width:100%!important;background:#fafafa'>";
            $message .= "<div style='padding:20px 20px 20px 20px!important;width:550px;margin:0 auto'>";
            $message .= "<h1 style='margin:0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:40px;letter-spacing:-1px;color:#333;font-weight:normal'>
					New System Message - SASS App</h1>";
            $message .= "<span style='color: #f0ad4e; margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'background-color>
					We have new <span style='color:#f0ad4e!important;'>system message</span> for you, <strong>$receiverName</strong></span>.";

            $message .= "<div style='margin:20px auto!important;width:510px;padding:20px 20px 20px 20px!important;border:1px solid #ddd!important;border-radius:3px!important;background:#fafafa!important'>";

            $message .= "<p style='margin:0 0 30px 0;width:510px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>";
            $message .= "<br/><strong>Date generated:</strong> " . $dateGenerated->format(self::DATE_FORMAT . ", " . self::HOUR_FORMAT);
            $message .= "<br/><strong>Path file:</strong> " . $pathFileMessage;
            $message .= "<br/><strong>System Message:</strong> " . $systemMessage . "</p>";

            $message .= "<div style='margin:20px 0;border-top:1px solid #ddd'></div>";
            $message .= "<p style='margin:0 0 30px 0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:17px;font-weight:300;color:#555'>Thanks,<br/><strong>$senderName</strong></p>";
            $message .= "</div>";
            $message .= "<div style='margin:20px 0;text-align:center;width:510px;'>";
            $message .= '<img alt="SASS logo" height="40" width="40" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAC4jAAAuIwF4pT92AAAAB3RJTUUH3gkOEB4GKCv7JwAABOFJREFUWMPt1n+slmUZB/DP9TznvICkiDCcJChUDAMpkR+OR0dkLp0zZz+mbG2t1tYmzZrzZenGakVbnbdcVrjStpxlODVsrTbWahqDR63FiElqwBbNQeuHghyB877nee/+OI9wfDnoH/1D2/v969lzf+/7vq7vfV3f+6aPPvroo48+/hfEGx+NaM6WXIkrgnMTf8VTif0drTTR5NyiBZkVM8PsZ9u+3p2I09CcimV4Hy7BIfwRO9paozUnq8ffj8sSx4Ldwm8zmBTNZZJ7ExdnWfb9rvQErsPPSFPOkNyspP1DptxPTJ6IMBjNGdiYuEnEr0XcR0zD47iwDm4SvoDbE/tSpG8Gf8NX8ZmBhub5KdmIb3S0nu6M6fBcQ/N2YihOidyLT5IvDO+oaM/AsR7lMskdGO5obXDqDDYMas7l5J8PYmUKn+2k1nD996GG5gnJ9AyXI8eLPQd4WKQXRJrg6LKLcGeY9hD5SJKu6GUkzsUabJugrp4NRhqxflDEtXg+Jcd75pc4lGE6BhJTxxO6RqvIPRC5kQnU+xKOZN77S6pXahV6w5iamFWv/+bgIzaniMNSyqW0ANOCwZ4sDqbcbzIcwcLg5lxz4I3xUd9KI6OtIyOjvQ2SLcEdxGMdP36GdCBYfLpK6VhwAjcOal48fqyThg530lCFyli9XRNjDTKO0xrtVK1jea44imtwXca8XLG3Ur76Fo3/PZxPWofXcsUKLM0Higeqbtk91eFFe4xnbbA4U7zeVe4dv1Kl7I7xYi1WZopGmlzsMlqeFCWvlMdzq0piFa7Ch3PFJXmjeK6qyk6PetfjHtIf8IM6kPlYpesXlfLIuM1TrtiVOCe4MVieK1bnihcq5b9P5bv6QKZ7CB8LluSjbsoVr1TKfermUClfrZQ/zRXT8W5cpbImj2LH+E2JLxPLctfe0/ApueUX4p11Yvsq5Z4ehdpd5VOZ4sX6CBfg45ni75PyYt9oKiXbU6XcE1nxRCQrMBe35ooqU+zMexb8/WBWPJmShbgScwes2lopuyJbQncDMw4Oum0geW1NiKJecEZiele55TQv1IyO1t5K+ZNM0QgWBDenFE9Wdhw92ZSpPFopH8sVe7AIt2BvnMH9J+Mr+LyIy9tp6ADZ3dhI3EC1tYe/DVPaWsvf7upqaN5am/eDHa2hM3CWYgjHs4bmh3oJba0TtX+N4BxMG6s9v6L7zAQX5jY0GtZffUq59fMHrV85gT/+CS+HmNfwxWhorp1g/504kMR5WWL1GZJNieFIi/aT3VAH+ijpyOlMm9EgrRxnM/NDWnh6LlFhirCZwQsm9tCxjgxpdxZi9oDme95UN1mzgdUR8e22uxt4BH/G0xOtNMpLxjxv+aC7Lqhvi/PqB0BPLmkp/hUp7RxLypyBcf5bH/EizMGmaMT6R6T0LnynNs2ZxC2kfzSs2zhs3qeJTVhH9/63qK3P4WvYjt2JS4Or8SCxnTRQX31zsKmttbOheRGex6OJLbWxL65VfRw/HyDdhQ9gaf0segn3Jp39wy49gSXYQv4jumcs/sTDwev1K2Vb8DIuG7sE0ifqZ9bvEn/paP2nnvZPXI8i+EjicLArcadG+2CnfV96m57LPkrWrbM+G5FtJfvu2frankncRjbrbA1wSt1lffTRRx999PF/jP8CuIm4fhDynqkAAAAASUVORK5CYII=" />';
            $message .= "</div>";
            $message .= "</div>";
            $message .= "</div>";

            $mail->msgHTML($message);

            // no need to safely send mail -- users are created manually
            $mail->send();

        } catch (phpmailerException $e) {
            throw new Exception("PHPMailer error: " . $e->errorMessage()); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            throw new Exception("Something went wrong with mail: " . $e->getMessage());
        }
    }

    public static function sendPending($buttonsPending, $receiverId, $receiverEmail, $fullName)
    {

        list($btnUrl, $emailVerificationTemplate, $subjectMail) = self::extractMailData($buttonsPending);

        $mg = new Mailgun(App::getMailgunKey());
        $domain = App::getMailgunDomain();

        $mg->sendMessage($domain,
            [
                'from'                => 'SASS App <admin@' . App::getHostname() . '>',
                'to'                  => $receiverEmail,
                'subject'             => $subjectMail,
                'text'                => 'Your mail does not support html',
                'html'                => $emailVerificationTemplate,
                'recipient-variables' => '{"' . $receiverEmail . '": {"id":' . $receiverId . ',"btnUrl":"' .
                    $btnUrl . '","fullName":"' . $fullName . '"}}'
            ]
        );
    }

    /**
     * @param $buttonsPending
     * @return array
     */
    public static function extractMailData($buttonsPending)
    {
        if ($buttonsPending[App::APPOINTMENT_BTN_URL] && $buttonsPending[App::REPORT_BTN_URL]) {
            $btnUrl = 'http://' . App::getAppointmentsListUrl() . '/?appointments=1&reports=1';
            $emailVerificationTemplate = file_get_contents(ROOT_PATH . 'mail/templates/notify_pending_appointments_reports.html');
            $subjectMail = "Pending Appointments and Reports";

            return [$btnUrl, $emailVerificationTemplate, $subjectMail];
        } elseif ($buttonsPending[App::APPOINTMENT_BTN_URL]) {
            $btnUrl = 'http://' . App::getAppointmentsListUrl() . '/?appointments=1&reports=0';
            $emailVerificationTemplate = file_get_contents(ROOT_PATH . 'mail/templates/notify_pending_appointments.html');
            $subjectMail = "Pending Appointments";

            return [$btnUrl, $emailVerificationTemplate, $subjectMail];
        } else {
            $btnUrl = 'http://' . App::getAppointmentsListUrl() . '/?appointments=0&reports=1';
            $emailVerificationTemplate = file_get_contents(ROOT_PATH . 'mail/templates/notify_pending_reports.html');
            $subjectMail = "Pending Reports";

            return [$btnUrl, $emailVerificationTemplate, $subjectMail];
        }
    }

}