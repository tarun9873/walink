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
    $db = env('DB_DATABASE');
    $date = date("Y-m-d_H-i-s");

    $sql_file = storage_path("app/backup_$date.sql");
    $zip_file = storage_path("app/backup_$date.zip");

    // DB connection
    $tables = \DB::select('SHOW TABLES');
    $dbName = 'Tables_in_' . $db;

    $output = '';

    foreach ($tables as $table) {
      $tableName = $table->$dbName;

      // Table structure
      $createTable = \DB::select("SHOW CREATE TABLE $tableName");
      $output .= "\n\n" . $createTable[0]->{'Create Table'} . ";\n\n";

      // Table data
      $rows = \DB::table($tableName)->get();

      foreach ($rows as $row) {
        $values = array_map(function ($value) {
          return addslashes($value);
        }, (array)$row);

        $values = "'" . implode("','", $values) . "'";
        $output .= "INSERT INTO $tableName VALUES ($values);\n";
      }
    }

    file_put_contents($sql_file, $output);

    // Zip file
    $zip = new \ZipArchive();
    if ($zip->open($zip_file, \ZipArchive::CREATE) === TRUE) {
      $zip->addFile($sql_file, basename($sql_file));
      $zip->close();
    }

    // Send Mail
    \Mail::raw('Database backup attached.', function ($message) use ($zip_file, $date) {
      $message->to('ak3400988@gmail.com')
        ->subject("Backup - $date")
        ->attach($zip_file);
    });

    // Delete files
    if (file_exists($sql_file)) {
      unlink($sql_file);
    }

    if (file_exists($zip_file)) {
      unlink($zip_file);
    }

    \Log::info('Backup completed at ' . now());

    $this->info('Backup sent successfully!');
  }
}