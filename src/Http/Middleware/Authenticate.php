<?php

namespace PavelMironchik\LaravelBackupPanel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PavelMironchik\LaravelBackupPanel\LaravelBackupPanel;

class Authenticate
{
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return Response|void
     */
    public function handle($request, $next)
    {
        if (LaravelBackupPanel::check($request)) {
            return $next($request);
        }

        abort(403);
    }
}
