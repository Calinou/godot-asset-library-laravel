<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Database\Seeds\MigrateLegacyDbSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput;

class MigrateLegacyDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
        migrate:legacy
        {path : The path to the old databasse SQL dump}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a database dump from the legacy Godot asset library';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Wiping data and running migrations...');
        $this->runCommand('migrate:refresh', [], new ConsoleOutput());

        $this->info('Importing SQL dump into the database...');
        /** @var string */
        $path = $this->argument('path');
        /** @var string */
        $sql = file_get_contents($path);
        DB::unprepared($sql);

        $this->info('Adjusting database structure...');
        $seeder = new MigrateLegacyDbSeeder();
        $seeder->run();

        $this->info("Data from \"$path\" migrated!");
    }
}
