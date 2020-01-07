<?php

namespace PavelMironchik\BackupPanel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PavelMironchik\BackupPanel\BackupPanel;

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
        return BackupPanel::check($request) ? $next($request) : abort(403);
    }
}
