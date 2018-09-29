<?php
/**
 * @author Rizart Dokollari <r.dokollari@gmail.com>
 * @since 9/29/18
 */

namespace tests\Unit\app\config;

use App;
use Tests\TestCase;

class AppTest extends TestCase
{
    /** @test */
    public function it_can_detect_when_running_on_local_or_testing_enviroment()
    {
        $this->assertTrue(App::environment(['local', 'testing']));
    }

    /** @test */
    public function it_can_detect_when_running_on_production()
    {
        App::setEnv('production');

        $this->assertFalse(App::environment(['local', 'testing']));
    }
}