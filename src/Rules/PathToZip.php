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
        return 'The given value must be a path to a zip file.';
    }
}
