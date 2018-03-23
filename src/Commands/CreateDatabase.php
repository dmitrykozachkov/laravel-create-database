<?php

namespace Dmitrykozachkov\LaravelCreateDatabase\Commands;

use Illuminate\Console\Command;
use \PDO;
use \Artisan;

class CreateDatabase extends Command
{
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

    protected $configs = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->configs = [
            'connection' => env('DB_CONNECTION'),
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => env('DB_DATABASE'),
            'user_name' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->createDb();
        $this->migrate();
        $this->seed();
        $this->passport();

        $this->info('Done.');
    }

    private function createDb()
    {
        $connection = "${$this->configs['connection']}:host=${$this->configs['host']};port=${$this->configs['port']}";

        $pdo = new PDO($connection, $this->configs['user_name'], $this->configs['password']);

        $sql = "CREATE DATABASE " . env('DB_DATABASE') . " CHARACTER SET utf8 COLLATE utf8_general_ci";

        $stmt = $pdo->prepare($sql);

        if ($stmt->execute()) {
            $this->line('Database was created.');
        } else {
            $this->error('ERROR: unable to create database!');
            die;
        }
    }

    private function migrate()
    {
        if ($this->option('migrate')) {
            if (Artisan::call('migrate') == 0) {
                $this->line('Migration table created successfully.');
            } else {
                $this->error('ERROR: migration is failed!');
            }
        }
    }

    private function seed()
    {
        if ($this->option('seed')) {
            if (Artisan::call('db:seed') == 0) {
                $this->line('Seeding is done.');
            } else {
                $this->error('ERROR: seeding is failed!');
            }
        }
    }

    private function passport()
    {
        if ($this->option('passport')) {
            if (Artisan::call('passport:install') == 0) {
                $this->line('Passport data was installed.');
            } else {
                $this->error('ERROR: passport data installing is failed!');
            }
        }
    }

}
