<?php

namespace PavelMironchik\BackupPanel\Http\Controllers;

use PavelMironchik\BackupPanel\Http\Middleware\Authenticate;

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
