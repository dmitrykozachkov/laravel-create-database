<?php

namespace Dmitrykozachkov\LaravelCreateDatabase;

use Illuminate\Support\ServiceProvider;

class LaravelCreateDatabaseProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
        $this->commands([
            Commands\CreateDatabase::class
        ]);

    }
}
