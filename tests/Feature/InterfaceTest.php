<?php

namespace PavelMironchik\LaravelBackupPanel\Tests;

use Illuminate\Foundation\Application;
use PavelMironchik\LaravelBackupPanel\LaravelBackupPanel;

class InterfaceTest extends TestCase
{
    protected $basePath = 'backup_test';

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:GhFMLyZ7x32kzu0How7wF8CIei+UC9Lc69Jcr+Z3sAk=');
        $app['config']->set('app.name', 'Laravel Backup Panel Test');
        $app['config']->set('laravel_backup_panel.path', $this->basePath);
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        LaravelBackupPanel::auth(function () {
            return true;
        });

        $this->app->instance('path.public', __DIR__.'/../../public');
    }

    public function test_panel_is_served_at_configured_path()
    {
        $this->get($this->basePath)
            ->assertOk();
    }

    public function test_home_view_is_served()
    {
        $this->get($this->basePath)
            ->assertViewIs('laravel_backup_panel::layout');
    }

    public function test_home_view_gets_global_variables()
    {
        $this->get($this->basePath)
            ->assertViewHas('globalVariables', [
                'path' => $this->basePath,
            ]);
    }
}
