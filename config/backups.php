<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Database Backups
    |--------------------------------------------------------------------------
    |
    | `php artisan app:backup-database` writes a gzipped snapshot here every
    | night and deletes snapshots older than `keep_days`. These live on the
    | same machine as the database; copy them somewhere else too.
    |
    */

    'path' => env('BACKUP_PATH', storage_path('app/backups')),

    'keep_days' => (int) env('BACKUP_KEEP_DAYS', 14),

];
