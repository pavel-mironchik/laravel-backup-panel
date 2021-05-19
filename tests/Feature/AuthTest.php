<?php

namespace PavelMironchik\LaravelBackupPanel\Tests\Feature;

use PavelMironchik\LaravelBackupPanel\Http\Middleware\Authenticate;
use PavelMironchik\LaravelBackupPanel\LaravelBackupPanel;
use PavelMironchik\LaravelBackupPanel\Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthTest extends TestCase
{
    public function test_authentication_callback_works()
    {
        $this->assertFalse(LaravelBackupPanel::check('admin'));

        LaravelBackupPanel::auth(function ($request) {
            return $request === 'admin';
        });

        $this->assertTrue(LaravelBackupPanel::check('admin'));
        $this->assertFalse(LaravelBackupPanel::check('hacker'));
        $this->assertFalse(LaravelBackupPanel::check(null));
    }

    public function test_authentication_middleware_can_pass()
    {
        LaravelBackupPanel::auth(function () {
            return true;
        });

        $middleware = new Authenticate;

        $response = $middleware->handle(
            new class
            {
            },
            function ($value) {
                return 'response';
            }
        );

        $this->assertSame('response', $response);
    }

    public function test_authentication_middleware_responds_with_403_on_failure()
    {
        $this->expectException(HttpException::class);

        LaravelBackupPanel::auth(function () {
            return false;
        });

        $middleware = new Authenticate;

        $middleware->handle(
            new class
            {
            },
            function ($value) {
                return 'response';
            }
        );
    }

    protected function tearDown(): void
    {
        LaravelBackupPanel::$authUsing = null;

        parent::tearDown();
    }
}
