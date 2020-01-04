<?php

namespace PavelMironchik\BackupPanel\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Single page application catch-all route.
     *
     * @return View
     */
    public function index()
    {
        return view('backup_panel::layout', [
            'globalVariables' => [
                'appName' => config('app.name'),
                'path' => config('backup_panel.path')
            ]
        ]);
    }
}
