<?php

namespace Tests\Unit\app\config\mail;

use App\mail\SassMailer;

/**
 * @author  Rizart Dokollari <r.dokollari@gmail.com>
 * @since   2/14/18
 */
class SassMailerTest extends \Tests\TestCase
{
    /** @test */
    public function replace_html_variables()
    {
        $sassMailer = new SassMailer();

        $this->assertEquals(
            'value',
            $sassMailer->replaceRecipientVariables('%key%', ['key' => 'value'])
        );
    }
}