<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::get('/files', 'BackupController@files');

    Route::get('/files/download', 'BackupController@download');

    Route::delete('/files', 'BackupController@delete');

    Route::post('/files', 'BackupController@create');
});

// Catch-all route...
Route::get('/{view?}', 'HomeController@index')
    ->where('view', '(.*)')
    ->name('laravel_backup_panel.index');
