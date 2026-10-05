<?php

namespace Tests\Feature;

use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Tests\TestCase;

class BackupDatabaseTest extends TestCase
{
    private string $directory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->directory = sys_get_temp_dir().'/wvs-backup-test-'.uniqid();
        config(['backups.path' => $this->directory, 'backups.keep_days' => 14]);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->directory);

        parent::tearDown();
    }

    private function useFakeMysql(): void
    {
        config([
            'database.default' => 'mysql',
            'database.connections.mysql' => ['driver' => 'mysql', 'host' => '127.0.0.1', 'port' => 3306, 'database' => 'wvs_backup_test', 'username' => 'backup_user', 'password' => 's3cret"pass'],
        ]);
    }

    /**
     * Pretend mysqldump ran: write the file the shell pipeline would have.
     */
    private function fakeSuccessfulDump(): void
    {
        Process::fake(function (PendingProcess $process) {
            preg_match("/> '([^']+)'$/", end($process->command), $match);
            File::put($match[1], gzencode('-- dump'));

            return Process::result();
        });
    }

    public function test_mysql_backups_run_mysqldump_without_exposing_the_password(): void
    {
        $this->useFakeMysql();
        $this->fakeSuccessfulDump();

        $this->artisan('app:backup-database')->assertSuccessful();

        Process::assertRan(function (PendingProcess $process) {
            $command = implode(' ', (array) $process->command);

            return str_contains($command, 'mysqldump')
                && str_contains($command, 'wvs_backup_test')
                && str_contains($command, '--single-transaction')
                && str_contains($command, 'gzip')
                && ! str_contains($command, 's3cret');
        });
        $this->assertCount(1, File::glob($this->directory.'/mysql-*.sql.gz'));
    }

    public function test_a_failed_dump_fails_the_command_and_leaves_no_file(): void
    {
        $this->useFakeMysql();
        Process::fake(fn () => Process::result(errorOutput: 'Access denied', exitCode: 2));

        $this->artisan('app:backup-database')->expectsOutputToContain('Backup failed: Access denied')->assertFailed();

        $this->assertSame([], File::glob($this->directory.'/*.sql.gz'));
    }

    public function test_sqlite_backups_copy_and_compress_the_file(): void
    {
        $database = sys_get_temp_dir().'/wvs-backup-test-'.uniqid().'.sqlite';
        File::put($database, 'pretend database contents');
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => $database]);

        $this->artisan('app:backup-database')->assertSuccessful();

        $backup = File::glob($this->directory.'/sqlite-*.sql.gz')[0];
        $this->assertSame('pretend database contents', gzdecode(File::get($backup)));
        File::delete($database);
    }

    public function test_a_dump_that_writes_nothing_is_a_failure(): void
    {
        $this->useFakeMysql();
        Process::fake();

        $this->artisan('app:backup-database')->expectsOutputToContain('no backup file was written')->assertFailed();
    }

    public function test_old_backups_are_deleted(): void
    {
        $this->useFakeMysql();
        $this->fakeSuccessfulDump();
        File::ensureDirectoryExists($this->directory);
        File::put($old = $this->directory.'/mysql-old.sql.gz', '');
        touch($old, now()->subDays(15)->getTimestamp());
        File::put($recent = $this->directory.'/mysql-recent.sql.gz', '');
        touch($recent, now()->subDays(13)->getTimestamp());

        $this->artisan('app:backup-database');

        $this->assertFileDoesNotExist($old);
        $this->assertFileExists($recent);
    }

    public function test_backups_are_scheduled_nightly(): void
    {
        $this->artisan('schedule:list')->expectsOutputToContain('app:backup-database');
    }
}
