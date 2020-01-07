<?php

namespace PavelMironchik\LaravelBackupPanel\Http\Controllers;

use Illuminate\View\View;
use PavelMironchik\LaravelBackupPanel\LaravelBackupPanel;

class HomeController extends Controller
{
    /**
     * Single page application catch-all route.
     *
     * @return View
     */
    public function index()
    {
        return view('laravel_backup_panel::layout', [
            'globalVariables' => LaravelBackupPanel::scriptVariables(),
        ]);
    }
}
