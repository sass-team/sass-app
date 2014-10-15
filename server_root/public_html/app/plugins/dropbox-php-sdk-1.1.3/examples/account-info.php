#!/usr/bin/env php
<?php

require_once __DIR__.'/helper.php';
use \Dropbox as dbx;

list($client) = parseArgs("account-info", $argv);

$accountInfo = $client->getAccountInfo();

print_r($accountInfo);
