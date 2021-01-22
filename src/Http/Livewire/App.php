<?php

namespace PavelMironchik\LaravelBackupPanel\Http\Livewire;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use PavelMironchik\LaravelBackupPanel\Jobs\CreateBackupJob;
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

        if (!$this->activeDisk and count($this->backupStatuses)) {
            $this->activeDisk = $this->backupStatuses[0]['disk'];
        }

        foreach ($this->backupStatuses as $backupStatus) {
            $this->disks[] = $backupStatus['disk'];
        }

        $this->emitSelf('backupStatusesUpdated');
    }

    public function getFiles(string $disk = '')
    {
        if ($disk) {
            $this->activeDisk = $disk;
        }

        if (!$this->activeDisk) {
            return;
        }

        $backupDestination = BackupDestination::create($this->activeDisk, config('backup.backup.name'));

        $this->files = Cache::remember("backups-{$this->activeDisk}", now()->addSeconds(4), function () use ($backupDestination) {
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

    public function showDeleteModal($fileIndex)
    {
        $this->deletingFile = $this->files[$fileIndex];

        $this->emitSelf('showDeleteModal');
    }

    public function deleteFile()
    {
        if (!$this->deletingFile) {
            return;
        }

        $deletingFile = $this->deletingFile;
        $this->deletingFile = null;

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

        $this->emitSelf('hideDeleteModal');
    }

    public function downloadFile(string $filePath)
    {
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

        $downloadHeaders = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Type' => 'application/zip',
            'Content-Length' => $backup->size(),
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
}
