<?php

namespace Fewsh\DomainOriented;

use Illuminate\Support\ServiceProvider;
class DomainOrientedServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerCommands();
    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\PublishCommand::class,
                Console\DeleteDirectoryCommand::class
            ]);
        }
    }

}