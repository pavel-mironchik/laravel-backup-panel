<?php

namespace PavelMironchik\BackupPanel\Tests;

use Illuminate\Foundation\Application;
use PavelMironchik\BackupPanel\BackupPanelServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            BackupPanelServiceProvider::class,
        ];
    }
}