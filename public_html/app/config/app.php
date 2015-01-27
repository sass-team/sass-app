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
 * @since 9/7/14.
 */

// these two constants are used to create root-relative web addresses
// and absolute server paths through the code
define("BASE_URL", "/");
define("ROOT_PATH", __DIR__ . "/../");

require ROOT_PATH . 'vendor/autoload.php';

# Load configuration functions & credentials
require_once ROOT_PATH . "config/App.class.php";
App::loadSettings(ROOT_PATH . '../../.env.php');

# Redirect to ssl if current call not a cron job and hostname not in list with ssl (see .env.php)
if (!App::isSecure() && isset($_SERVER['SERVER_NAME']) && App::isHostnameInSSLList())
{
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
	exit();
}

# Load all SASS App functions
require ROOT_PATH . "config/loadAllClasses.php";
