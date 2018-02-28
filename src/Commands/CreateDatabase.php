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
    protected $signature = 'db:create {--migrate} {--seed} {--passport}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The command will create database and migrate, and seeding data, and install passport.';

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
            if (Artisan::call('migrate') == 0) {
            	$this->line('Migration table created successfully.');
        	} else {
        		$this->error('ERROR: migration is failed!');
        	}
        }

        if ($this->option('seed')) {
            if (Artisan::call('db:seed') == 0) {
            	$this->line('Seeding is done.');
            } else {
        		$this->error('ERROR: seeding is failed!');
        	}
        }

        if ($this->option('passport')) {
            if (Artisan::call('passport:install') == 0) {
            	$this->line('Passport data was installed.');
        	} else {
        		$this->error('ERROR: passport data installing is failed!');
        	}
        }

        $this->info('Done.');
    }
}
