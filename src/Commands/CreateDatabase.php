<?php

namespace Dmitrykozachkov\LaravelCreateDatabase\Commands;

use Illuminate\Console\Command;

class CreateDatabase extends Command
{
    private $character = 'utf8';
    private $collate = 'utf8_general_ci';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create';

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
        $host = env('DB_HOST');
        $database = env('DB_DATABASE');
        $user_name = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        $pdo = new \PDO("mysql:host=$host", $user_name, $password);

        $sql = "CREATE DATABASE " . env('DB_DATABASE') . " CHARACTER SET utf8 COLLATE utf8_general_ci";
        $stmt = $pdo->prepare($sql);
        $stmt->execute() ? $this->line('Database was created') : $this->error('ERROR: unable to create database');
    }
}
