<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class BackupDatabase extends Command
{
    protected $signature = 'app:backup-database';

    protected $description = 'Save a gzipped snapshot of the database and delete snapshots past the retention period';

    public function handle(): int
    {
        $directory = config('backups.path');
        File::ensureDirectoryExists($directory, 0700);

        $connection = config('database.default');
        $config = config("database.connections.{$connection}");
        $path = $directory.'/'.$connection.'-'.now()->format('Y-m-d-His').'.sql.gz';

        $ok = match ($config['driver']) {
            'mysql', 'mariadb' => $this->dumpMysql($config, $path),
            'sqlite' => $this->copySqlite($config, $path),
            default => $this->unsupported($config['driver']),
        };

        if (! $ok) {
            File::delete($path);

            return self::FAILURE;
        }

        $this->info('Saved '.basename($path).' ('.number_format(File::size($path) / 1024, 1).' KB).');
        $this->prune($directory);

        return self::SUCCESS;
    }

    private function dumpMysql(array $config, string $path): bool
    {
        // Credentials go in a private temp file so the password never shows
        // up in the process list.
        $credentials = tempnam(sys_get_temp_dir(), 'wvs-backup-');
        chmod($credentials, 0600);
        file_put_contents($credentials, implode("\n", [
            '[client]',
            'user="'.addcslashes((string) $config['username'], '"\\').'"',
            'password="'.addcslashes((string) $config['password'], '"\\').'"',
            'host="'.addcslashes((string) $config['host'], '"\\').'"',
            'port='.(int) $config['port'],
            '',
        ]));

        try {
            $dump = implode(' ', array_map('escapeshellarg', [
                'mysqldump',
                '--defaults-extra-file='.$credentials,
                '--single-transaction',
                '--quick',
                '--no-tablespaces',
                $config['database'],
            ]));

            $result = Process::timeout(600)->run(['bash', '-o', 'pipefail', '-c', $dump.' | gzip > '.escapeshellarg($path)]);
        } finally {
            @unlink($credentials);
        }

        if ($result->failed() || ! File::exists($path)) {
            $this->error('Backup failed: '.(trim($result->errorOutput() ?: $result->output()) ?: 'no backup file was written.'));

            return false;
        }

        return true;
    }

    private function copySqlite(array $config, string $path): bool
    {
        if ($config['database'] === ':memory:' || ! File::exists($config['database'])) {
            $this->error('There is no SQLite database file to back up.');

            return false;
        }

        File::put($path, gzencode(File::get($config['database']), 9));

        return true;
    }

    private function unsupported(string $driver): bool
    {
        $this->error("Backups aren't set up for the {$driver} driver.");

        return false;
    }

    private function prune(string $directory): void
    {
        $cutoff = now()->subDays(config('backups.keep_days'))->getTimestamp();

        foreach (File::glob($directory.'/*.sql.gz') as $file) {
            if (File::lastModified($file) < $cutoff) {
                File::delete($file);
                $this->line('Deleted old backup '.basename($file).'.');
            }
        }
    }
}
