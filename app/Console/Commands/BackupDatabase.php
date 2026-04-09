<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use ZipArchive;

class BackupDatabase extends Command
{
  protected $signature = 'backup:database';
  protected $description = 'Send database backup to email';

  public function handle()
  {
    $host = env('DB_HOST');
    $user = env('DB_USERNAME');
    $pass = env('DB_PASSWORD');
    $db   = env('DB_DATABASE');

    $date = date("Y-m-d_H-i-s");
    $sql_file = storage_path("app/backup_$date.sql");
    $zip_file = storage_path("app/backup_$date.zip");

    // Create DB dump
    $command = "mysqldump --host=$host --user=$user --password=$pass $db > $sql_file";
    exec($command . ' 2>&1', $output, $resultCode);

    if ($resultCode !== 0) {
      \Log::error('Backup error: ' . implode("\n", $output));
      throw new \Exception('Backup failed: ' . implode("\n", $output));
    }

    if (!file_exists($sql_file)) {
      $this->error('Backup failed');
      return;
    }

    // Zip file
    $zip = new ZipArchive();
    if ($zip->open($zip_file, ZipArchive::CREATE) === TRUE) {
      $zip->addFile($sql_file, basename($sql_file));
      $zip->close();
    }

    // Send Mail
    Mail::raw('Database backup attached.', function ($message) use ($zip_file, $date) {
      $message->to('your@email.com')
        ->subject("Backup - $date")
        ->attach($zip_file);
    });

    // Delete files
    unlink($sql_file);
    unlink($zip_file);

    $this->info('Backup sent successfully!');
  }
}
