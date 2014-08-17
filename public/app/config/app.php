<?php
/**
 * Created by PhpStorm.
 * User:
 * Date: 5/12/14
 * Time: 11:38 PM
 */
// these two constants are used to create root-relative web addresses
// and absolute server paths throught all the code
define("BASE_URL", "/");
define("ROOT_PATH", $_SERVER["DOCUMENT_ROOT"] . "/");

// removed for safety reasons.

define("DB_HOST","localhost");
define("DB_NAME","sass-ms");
define("DB_PORT","3306"); // default port.
define("DB_USER","root");
define("DB_PASS","");
