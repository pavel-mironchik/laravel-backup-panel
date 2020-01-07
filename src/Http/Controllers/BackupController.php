<?php

namespace PavelMironchik\LaravelBackupPanel\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Adapter\Local;

class BackupController extends Controller
{
    public function files()
    {
        $diskNames = config('backup.backup.destination.disks');

        if (! count($diskNames)) {
            return ['error' => 'No disks configured!'];
        }

        $data = [];
        foreach ($diskNames as $diskName) {
            $disk = Storage::disk($diskName);
            $adapter = $disk->getDriver()->getAdapter();
            $files = $disk->allFiles();

            foreach ($files as $file) {
                if (substr($file, -4) == '.zip') {
                    $data[] = [
                        'path'     => $file,
                        'name'     => pathinfo($file, PATHINFO_BASENAME),
                        'size'     => round((int) $disk->size($file) / 1048576, 2).' MB',
                        'disk'     => $diskName,
                        'download' => ($adapter instanceof Local) ? true : false,
                        'date'     => Carbon::createFromTimeStamp($disk->lastModified($file))
                            ->formatLocalized('%d %B %Y, %H:%M'),
                    ];
                }
            }
        }

        $data = array_reverse($data);

        return ['files' => $data];
    }

    public function download(Request $request)
    {
        $disk = Storage::disk($request->input('disk'));
        $path = $request->input('path');

        $adapter = $disk->getDriver()->getAdapter();
        if (! $adapter instanceof Local) {
            abort(404, 'Only files from local disk can be downloaded!');
        }

        if (! $disk->exists($path)) {
            abort(404, 'There is no such file!');
        }

        $storage_path = $disk->getDriver()->getAdapter()->getPathPrefix();

        return response()->download($storage_path.$path);
    }

    public function delete(Request $request)
    {
        $disk = Storage::disk($request->input('disk'));
        $path = $request->input('path');

        if (! $disk->exists($path)) {
            return ['error' => 'There is no such file!'];
        }

        $disk->delete($path);

        return 'success';
    }

    public function create()
    {
        try {
            Artisan::call('backup:run');

            $output = Artisan::output();
            if (strpos($output, 'Backup failed because')) {
                preg_match('/Backup failed because(.*?)$/ms', $output, $match);
                Log::error($output);

                return response(['error' => $match[0]], 500);
            }

            return 'success';
        } catch (Exception $exception) {
            Log::error($exception);

            return response(['error' => $exception->getMessage()], 500);
        }
    }
}
