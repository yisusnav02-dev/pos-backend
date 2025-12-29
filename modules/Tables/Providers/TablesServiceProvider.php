<?php

namespace Modules\Tables\Providers;

use Illuminate\Support\ServiceProvider;

class TablesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
    }

    public function register()
    {
        //
    }
}