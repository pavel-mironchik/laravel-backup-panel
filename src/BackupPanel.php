<?php

namespace PavelMironchik\BackupPanel;

use Closure;
use Illuminate\Http\Request;

class BackupPanel
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
        return [
            'appName' => config('app.name'),
            'path' => config('backup_panel.path'),
        ];
    }
}
