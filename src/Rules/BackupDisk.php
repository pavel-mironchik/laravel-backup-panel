<?php

namespace PavelMironchik\LaravelBackupPanel\Rules;

use Illuminate\Contracts\Validation\Rule;

class BackupDisk implements Rule
{
    public function passes($attribute, $value)
    {
        $configuredBackupDisks = config('backup.backup.destination.disks');

        return in_array($value, $configuredBackupDisks);
    }

    public function message()
    {
        return 'Current disk is not configured as a backup disk';
    }
}
