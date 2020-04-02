<?php

namespace PavelMironchik\LaravelBackupPanel;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use RuntimeException;

class LaravelBackupPanel
{
    /**
     * The callback that should be used to authenticate Laravel Backup Panel users.
     *
     * @var Closure
     */
    public static $authUsing;

    /**
     * Determine if the given request can access the Laravel Backup Panel dashboard.
     *
     * @param  Request  $request
     * @return bool
     */
    public static function check($request)
    {
        return (static::$authUsing ?: function () {
            return app()->environment('local');
        })($request);
    }

    /**
     * Set the callback that should be used to authenticate Laravel Backup Panel users.
     *
     * @param  Closure  $callback
     * @return static
     */
    public static function auth(Closure $callback)
    {
        static::$authUsing = $callback;

        return new static;
    }

    /**
     * Get the default JavaScript variables for Laravel Backup Panel.
     *
     * @return array
     */
    public static function scriptVariables()
    {
        return array_merge(
            config('laravel_backup_panel'),
            [
                'assetsAreCurrent' => static::assetsAreCurrent()
            ]
        );
    }

    /**
     * Determine if Laravel Backup Panel's published assets are up-to-date.
     *
     * @return bool
     *
     * @throws RuntimeException
     */
    private static function assetsAreCurrent()
    {
        $publishedPath = public_path('vendor/laravel_backup_panel/mix-manifest.json');

        if (! File::exists($publishedPath)) {
            throw new RuntimeException('Laravel Backup Panel assets are not published. Please run: php artisan vendor:publish --tag=laravel-backup-panel-assets --force');
        }

        return File::get($publishedPath) === File::get(__DIR__.'/../public/vendor/laravel_backup_panel/mix-manifest.json');
    }
}
