<?php

namespace PavelMironchik\LaravelBackupPanel\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class PathToZip implements Rule
{
    public function passes($attribute, $value)
    {
        return Str::endsWith($value, '.zip');
    }

    public function message()
    {
        return 'It must be a zip file';
    }
}
