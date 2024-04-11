<?php

namespace Vishnu\FillableGenerator;

use Illuminate\Support\ServiceProvider;

class FillableGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\GenerateFillableCommand::class,
            ]);
        }
    }
}
