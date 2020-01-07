<?php

namespace PavelMironchik\BackupPanel\Tests;

use Illuminate\Foundation\Application;
use PavelMironchik\BackupPanel\BackupPanel;

class InterfaceTest extends TestCase
{
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
        $app['config']->set('app.name', 'Backup Panel Test');
        $app['config']->set('backup_panel.path', 'backup_test');
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        BackupPanel::auth(function () {
            return true;
        });

        $this->app->instance('path.public', __DIR__.'/../../public');
    }

    public function test_panel_is_served_at_configured_path()
    {
        $this->get('backup_test')
            ->assertOk();
    }

    public function test_home_view_is_served()
    {
        $this->get('backup_test')
            ->assertViewIs('backup_panel::layout');
    }

    public function test_home_view_gets_global_variables()
    {
        $this->get('backup_test')
            ->assertViewHas('globalVariables', [
                'appName' => 'Backup Panel Test',
                'path' => 'backup_test',
            ]);
    }
}