<?php

namespace Tests\Unit\app\config\mail;

use App\mail\SassMailer;
use App\mail\SassMailerException;

/**
 * @author  Rizart Dokollari <r.dokollari@gmail.com>
 * @since   2/14/18
 */
class SassMailerTest extends \Tests\TestCase
{
    /** @var SassMailer */
    protected $sassMailer;

    public function setUp()
    {
        parent::setUp();

        $this->sassMailer = new SassMailer();
    }

    /** @test */
    public function it_replaces_html_variables()
    {
        $actual = $this->sassMailer->replaceRecipientVariables('%key%', [
            'key' => $expected = 'value',
        ]);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_throws_exception_about_required_data()
    {
        $required = ['to', 'subject', 'html'];
        $expected = [];

        foreach ($required as $key) {
            $ucFirstKey = ucfirst($key);
            $expected[$key] = ['required' => "The $ucFirstKey is required"];
        }

        $expected = json_encode($expected);

        $this->expectExceptionMessage($expected);
        $this->expectException(SassMailerException::class);

        $this->sassMailer->send([]);
    }
}