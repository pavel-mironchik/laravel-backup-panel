<?php

namespace PavelMironchik\BackupPanel;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class BackupPanelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/backup_panel.php' => config_path('backup_panel.php'),
            ], 'backup-panel-config');

            $this->publishes([
                __DIR__.'/../public/vendor/backup_panel' => public_path('vendor/backup_panel'),
            ], 'backup-panel-assets');
        }

        Route::group([
            'prefix' => config('backup_panel.path'),
            'namespace' => 'PavelMironchik\BackupPanel\Http\Controllers',
            'middleware' => 'web',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'backup_panel');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/backup_panel.php', 'backup_panel');
    }
}
