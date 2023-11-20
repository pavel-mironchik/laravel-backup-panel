<?php

use Illuminate\Support\Facades\Route;

if(config('laravel_backup_panel.routes') === true) {
  Route::view('/', 'laravel_backup_panel::layout')->name('laravel-backup-panel.index');
}
