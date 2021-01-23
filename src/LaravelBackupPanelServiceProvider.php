<?php

namespace PavelMironchik\LaravelBackupPanel;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use PavelMironchik\LaravelBackupPanel\Console\InstallCommand;
use PavelMironchik\LaravelBackupPanel\Http\Livewire\App;
use PavelMironchik\LaravelBackupPanel\Http\Middleware\Authenticate;

class LaravelBackupPanelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/laravel_backup_panel.php' => config_path('laravel_backup_panel.php'),
            ], 'laravel-backup-panel-config');

            $this->publishes([
                __DIR__.'/../resources/assets/css' => public_path('vendor/laravel_backup_panel'),
            ], 'laravel-backup-panel-assets');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel_backup_panel'),
            ], 'laravel-backup-panel-views');

            $this->publishes([
                __DIR__.'/../stubs/LaravelBackupPanelServiceProvider.php.stub' => app_path('Providers/LaravelBackupPanelServiceProvider.php'),
            ], 'laravel-backup-panel-provider');

            $this->commands([
                InstallCommand::class,
            ]);
        }

        Route::group([
            'prefix' => config('laravel_backup_panel.path'),
            'middleware' => ['web', Authenticate::class],
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel_backup_panel');

        Livewire::component('laravel_backup_panel::app', App::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel_backup_panel.php', 'laravel_backup_panel');
    }
}
