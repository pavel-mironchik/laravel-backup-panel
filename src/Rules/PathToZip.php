<?php

namespace PavelMironchik\LaravelBackupPanel\Rules;

use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Rule;

class PathToZip implements Rule
{
    public function passes($attribute, $value)
    {
        return Str::endsWith($value, '.zip');
    }

    public function message()
    {
        return 'The given value must be a path to a zip file.';
    }
}
