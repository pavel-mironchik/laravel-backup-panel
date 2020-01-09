<?php

namespace PavelMironchik\LaravelBackupPanel\Http\Controllers;

use Illuminate\Http\Response;
use PavelMironchik\LaravelBackupPanel\Http\Middleware\Authenticate;

class Controller extends \Illuminate\Routing\Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function respondSuccess(): Response
    {
        return response('', Response::HTTP_NO_CONTENT);
    }
}
