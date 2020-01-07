<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use PavelMironchik\LaravelBackupPanel\LaravelBackupPanelApplicationServiceProvider;

class LaravelBackupPanelServiceProvider extends LaravelBackupPanelApplicationServiceProvider
{
    /**
     * Register the Laravel Backup Panel gate.
     *
     * This gate determines who can access Laravel Backup Panel in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewLaravelBackupPanel', function ($user) {
            return in_array($user->email, [
                // 'admin@your-site.com'
            ]);
        });
    }
}
