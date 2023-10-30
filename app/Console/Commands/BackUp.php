<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
         $filename=date("y_m_d_H_i_s").".sql";
        
         $comand="mysqldump --user=".env('DB_USERNAME')." --password=".env("DB_PASSWORD")." --host=".env("DB_HOST").env("DB_DATABASE")." > ".storage_path()."/app/backup/".$filename;
        exec($comand);
    }
}
