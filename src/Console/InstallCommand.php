<?php

namespace PavelMironchik\LaravelBackupPanel\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-backup-panel:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Laravel Backup Panel resources';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('Publishing Laravel Backup Panel service provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'laravel-backup-panel-provider']);

        $this->comment('Publishing Laravel Backup Panel assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'laravel-backup-panel-assets']);

        $this->comment('Publishing Laravel Backup Panel views...');
        $this->callSilent('vendor:publish', ['--tag' => 'laravel-backup-panel-views']);

        $this->comment('Publishing Laravel Backup Panel configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'laravel-backup-panel-config']);

        $this->registerServiceProvider();

        $this->info('Laravel Backup Panel resources installed successfully.');
    }

    protected function registerServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\LaravelBackupPanelServiceProvider::class')) {
            return;
        }

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL,
            "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL."        {$namespace}\Providers\LaravelBackupPanelServiceProvider::class,".PHP_EOL,
            $appConfig
        ));

        file_put_contents(app_path('Providers/LaravelBackupPanelServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/LaravelBackupPanelServiceProvider.php'))
        ));
    }
}
