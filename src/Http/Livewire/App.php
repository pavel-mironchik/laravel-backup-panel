<?php

namespace PavelMironchik\LaravelBackupPanel\Http\Livewire;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use PavelMironchik\LaravelBackupPanel\Jobs\CreateBackupJob;
use PavelMironchik\LaravelBackupPanel\Rules\BackupDisk;
use PavelMironchik\LaravelBackupPanel\Rules\PathToZip;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\BackupDestination\BackupDestination;
use Spatie\Backup\Helpers\Format;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatus;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatusFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

class App extends Component
{
    public $backupStatuses = [];

    public $activeDisk = null;

    public $disks = [];

    public $files = [];

    public $deletingFile = null;

    public function updateBackupStatuses()
    {
        $this->backupStatuses = Cache::remember('backup-statuses', now()->addSeconds(4), function () {
            return BackupDestinationStatusFactory::createForMonitorConfig(config('backup.monitor_backups'))
                ->map(function (BackupDestinationStatus $backupDestinationStatus) {
                    return [
                        'name' => $backupDestinationStatus->backupDestination()->backupName(),
                        'disk' => $backupDestinationStatus->backupDestination()->diskName(),
                        'reachable' => $backupDestinationStatus->backupDestination()->isReachable(),
                        'healthy' => $backupDestinationStatus->isHealthy(),
                        'amount' => $backupDestinationStatus->backupDestination()->backups()->count(),
                        'newest' => $backupDestinationStatus->backupDestination()->newestBackup()
                            ? $backupDestinationStatus->backupDestination()->newestBackup()->date()->diffForHumans()
                            : 'No backups present',
                        'usedStorage' => Format::humanReadableSize($backupDestinationStatus->backupDestination()->usedStorage()),
                    ];
                })
                ->values()
                ->toArray();
        });

        if (! $this->activeDisk and count($this->backupStatuses)) {
            $this->activeDisk = $this->backupStatuses[0]['disk'];
        }

        $this->disks = collect($this->backupStatuses)
            ->map(function ($backupStatus) {
                return $backupStatus['disk'];
            })
            ->values()
            ->all();

        $this->emitSelf('backupStatusesUpdated');
    }

    public function getFiles(string $disk = '')
    {
        if ($disk) {
            $this->activeDisk = $disk;
        }

        $this->validateActiveDisk();

        $backupDestination = BackupDestination::create($this->activeDisk, config('backup.backup.name'));

        $this->files = Cache::remember("backups-{$this->activeDisk}", now()->addSeconds(4), function () use ($backupDestination) {
            return $backupDestination
                ->backups()
                ->map(function (Backup $backup) {
                    $size = method_exists($backup, 'sizeInBytes') ? $backup->sizeInBytes() : $backup->size();

                    return [
                        'path' => $backup->path(),
                        'date' => $backup->date()->format('Y-m-d H:i:s'),
                        'size' => Format::humanReadableSize($size),
                    ];
                })
                ->toArray();
        });
    }

    public function showDeleteModal($fileIndex)
    {
        $this->deletingFile = $this->files[$fileIndex];

        $this->emitSelf('showDeleteModal');
    }

    public function deleteFile()
    {
        $deletingFile = $this->deletingFile;
        $this->deletingFile = null;

        $this->emitSelf('hideDeleteModal');

        $this->validateActiveDisk();
        $this->validateFilePath($deletingFile ? $deletingFile['path'] : '');

        $backupDestination = BackupDestination::create($this->activeDisk, config('backup.backup.name'));

        $backupDestination
            ->backups()
            ->first(function (Backup $backup) use ($deletingFile) {
                return $backup->path() === $deletingFile['path'];
            })
            ->delete();

        $this->files = collect($this->files)
            ->reject(function ($file) use ($deletingFile) {
                return $file['path'] === $deletingFile['path']
                    && $file['date'] === $deletingFile['date']
                    && $file['size'] === $deletingFile['size'];
            })
            ->values()
            ->all();
    }

    public function downloadFile(string $filePath)
    {
        $this->validateActiveDisk();
        $this->validateFilePath($filePath);

        $backupDestination = BackupDestination::create($this->activeDisk, config('backup.backup.name'));

        $backup = $backupDestination->backups()->first(function (Backup $backup) use ($filePath) {
            return $backup->path() === $filePath;
        });

        if (! $backup) {
            return response('Backup not found', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->respondWithBackupStream($backup);
    }

    public function respondWithBackupStream(Backup $backup): StreamedResponse
    {
        $fileName = pathinfo($backup->path(), PATHINFO_BASENAME);
        $size = method_exists($backup, 'sizeInBytes') ? $backup->sizeInBytes() : $backup->size();

        $downloadHeaders = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Type' => 'application/zip',
            'Content-Length' => $size,
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
            'Pragma' => 'public',
        ];

        return response()->stream(function () use ($backup) {
            $stream = $backup->stream();

            fpassthru($stream);

            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, $downloadHeaders);
    }

    public function createBackup(string $option = '')
    {
        dispatch(new CreateBackupJob($option))
            ->onQueue(config('laravel_backup_panel.queue'));
    }

    public function render()
    {
        return view('laravel_backup_panel::livewire.app');
    }

    protected function validateActiveDisk()
    {
        try {
            Validator::make(
                ['activeDisk' => $this->activeDisk],
                [
                    'activeDisk' => ['required', new BackupDisk()],
                ],
                [
                    'activeDisk.required' => 'Select a disk',
                ]
            )->validate();
        } catch (ValidationException $e) {
            $message = $e->validator->errors()->get('activeDisk')[0];
            $this->emitSelf('showErrorToast', $message);

            throw $e;
        }
    }

    protected function validateFilePath(string $filePath)
    {
        try {
            Validator::make(
                ['file' => $filePath],
                [
                    'file' => ['required', new PathToZip()],
                ],
                [
                    'file.required' => 'Select a file',
                ]
            )->validate();
        } catch (ValidationException $e) {
            $message = $e->validator->errors()->get('file')[0];
            $this->emitSelf('showErrorToast', $message);

            throw $e;
        }
    }
}
