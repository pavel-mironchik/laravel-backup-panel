<?php

namespace PavelMironchik\LaravelBackupPanel\Http\Controllers;

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
}
