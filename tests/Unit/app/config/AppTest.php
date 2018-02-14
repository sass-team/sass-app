<?php

namespace Tests\Unit\app\config;

use App;
use Tests\TestCase;

/**
 * @author  Rizart Dokollari <r.dokollari@gmail.com>
 * @since   2/14/18
 */
class AppTest extends TestCase
{
    /** @test */
    public function app_returns_correct_sender()
    {
        $env = require __DIR__ . '/../../../../.env.php';

        $domain = $env['MAILGUN_DOMAIN'];

        $this->assertEquals("noreply@$domain", App::mailFrom());
    }
}