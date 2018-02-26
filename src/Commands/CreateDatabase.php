<?php

namespace Dmitrykozachkov\LaravelCreateDatabase\Commands;

use Illuminate\Console\Command;
use \PDO;
use \Artisan;

class CreateDatabase extends Command
{
    private $character = 'utf8';
    private $collate = 'utf8_general_ci';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create {--migrate} {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The command will create database';

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
        $connection = env('DB_CONNECTION');
        $host = env('DB_HOST');
        $port = env('DB_PORT');
        $database = env('DB_DATABASE');
        $user_name = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        $pdo = new PDO("$connection:host=$host;port=$port", $user_name, $password);

        $sql = "CREATE DATABASE " . env('DB_DATABASE') . " CHARACTER SET utf8 COLLATE utf8_general_ci";

        $stmt = $pdo->prepare($sql);

        if ($stmt->execute()) {
            $this->line('Database was created.');
        } else {
            $this->error('ERROR: unable to create database!');
            die;
        }

        if ($this->option('migrate')) {
            Artisan::call('migrate');
            $this->line('Migration table created successfully.');
        }

        if ($this->option('seed')) {
            Artisan::call('db:seed');
        }

        $this->info('Done.');
    }
}
