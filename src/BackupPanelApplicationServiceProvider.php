<?php

namespace PavelMironchik\BackupPanel;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class BackupPanelApplicationServiceProvider extends ServiceProvider
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

        BackupPanel::auth(function ($request) {
            return app()->environment('local') ||
                   Gate::check('viewBackupPanel', [$request->user()]);
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
        Gate::define('viewBackupPanel', function ($user) {
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
