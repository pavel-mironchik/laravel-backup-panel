<?php

namespace PavelMironchik\LaravelBackupPanel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use PavelMironchik\LaravelBackupPanel\Jobs\CreateBackupJob;
use PavelMironchik\LaravelBackupPanel\Rules\BackupDisk;
use PavelMironchik\LaravelBackupPanel\Rules\PathToZip;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\BackupDestination\BackupDestination;
use Spatie\Backup\Helpers\Format;

class BackupsController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'disk' => ['required', new BackupDisk()],
        ]);

        $backupDestination = BackupDestination::create($validated['disk'], config('backup.backup.name'));

        return Cache::remember("backups-{$validated['disk']}", now()->addSeconds(4), function () use ($backupDestination) {
            return $backupDestination
                ->backups()
                ->map(function (Backup $backup) {
                    return [
                        'path' => $backup->path(),
                        'date' => $backup->date()->format('Y-m-d H:i:s'),
                        'size' => Format::humanReadableSize($backup->size()),
                    ];
                })
                ->toArray();
        });
    }

    public function delete(Request $request)
    {
        $validated = $request->validate([
            'disk' => new BackupDisk(),
            'path' => ['required', new PathToZip()],
        ]);

        $backupDestination = BackupDestination::create($validated['disk'], config('backup.backup.name'));

        $backupDestination
            ->backups()
            ->first(function (Backup $backup) use ($validated) {
                return $backup->path() === $validated['path'];
            })
            ->delete();

        $this->respondSuccess();
    }

    public function create(Request $request)
    {
        $option = $request->input('option', '');
        dispatch(new CreateBackupJob($option));
    }
}
