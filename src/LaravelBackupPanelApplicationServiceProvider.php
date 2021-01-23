<?php

namespace PavelMironchik\LaravelBackupPanel;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class LaravelBackupPanelApplicationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureAuthorization();
    }

    /**
     * Configure the Laravel Backup Panel authorization services.
     *
     * @return void
     */
    protected function configureAuthorization()
    {
        $this->gate();

        LaravelBackupPanel::auth(function ($request) {
            return App::environment('local') ||
                   Gate::check('viewLaravelBackupPanel', [$request->user()]);
        });
    }

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

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
