<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel Backup Panel Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Laravel Backup Panel will be accessible from.
    | Feel free to change this path to anything you like.
    |
    | Note that the URI will not affect the paths of its internal API that
    | aren't exposed to users.
    |
    */

    'path' => 'backup',

    /*
    |--------------------------------------------------------------------------
    | Queue To Run Backup Jobs
    |--------------------------------------------------------------------------
    |
    | You can specify a queue name to use for the jobs to run through.
    |
    | Useful when you don't want to run backup jobs on the same queue as user
    | actions and things that is more time critical.
    |
    */

    'queue' => null,

];
