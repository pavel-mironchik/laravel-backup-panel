<?php

namespace PavelMironchik\LaravelBackupPanel;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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
            return App::environment('local');
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
}
