<?php

namespace PavelMironchik\BackupPanel\Http\Controllers;

use Illuminate\View\View;
use PavelMironchik\BackupPanel\BackupPanel;

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
            'globalVariables' => BackupPanel::scriptVariables(),
        ]);
    }
}
