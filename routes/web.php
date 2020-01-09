<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::get('backups', 'BackupsController@index');
    Route::post('backups', 'BackupsController@create');
    Route::delete('backups', 'BackupsController@delete');

    Route::get('download-backup', 'DownloadBackupController');

    Route::get('backup-statuses', 'BackupStatusesController');
});

// Catch-all route...
Route::get('/{view?}', 'HomeController@index')
    ->where('view', '(.*)')
    ->name('laravel_backup_panel.index');
