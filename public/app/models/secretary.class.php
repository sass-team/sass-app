
<!-- saved from url=(0120)https://bitbucket.org/sass-tm/sass-ms/raw/bc3416dd114676a048acf651227c966ceb608f30/public/app/models/secretary.class.php -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"><style type="text/css"></style><script type="text/javascript" src="chrome-extension://bfbmjmiodbnnpllbbbfblcplfjjepjdn/js/injected.js"></script></head><body><pre style="word-wrap: break-word; white-space: pre-wrap;">&lt;?php

/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/29/14
 * Time: 8:01 PM
 */
class Secretary extends User
{

	public function __construct($db, $id, $firstName, $lastName, $email, $mobileNum, $avatarImgLoc, $profileDescription, $dateAccountCreated, $userType, $accountActiveStatus) {
		parent::__construct($db, $id, $firstName, $lastName, $email, $mobileNum, $avatarImgLoc, $profileDescription, $dateAccountCreated, $userType, $accountActiveStatus);
	}

	public function isSecretary() {
		return true;
	}
} </pre></body></html>