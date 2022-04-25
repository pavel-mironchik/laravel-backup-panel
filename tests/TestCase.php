<?php

namespace PavelMironchik\LaravelBackupPanel\Tests;

use Illuminate\Foundation\Application;
use Livewire\LivewireServiceProvider;
use PavelMironchik\LaravelBackupPanel\LaravelBackupPanelServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param  Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelBackupPanelServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }
}
