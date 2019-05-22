<?php
/**
 * Created by PhpStorm.
 * User: LunaticFish
 * Date: 2019/3/13
 * Time: 15:57
 */

namespace StackBoom\ApiResponder;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class ApiResponderServiceProvider extends ServiceProvider
{
    public function boot(Filesystem $filesystem){
        $this->publishes([
            __DIR__.'/../database/migrations/create_api_responder_tables.php.stub' => $this->getMigrationFileName($filesystem),
        ], 'migrations');
        $this->publishes([
            __DIR__.'/../config/api_responder.php' => config_path('api_responder.php'),
        ], 'config');

        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__.'/views','api_responder');
    }

    public function register()
    {
        $this->registerModelGenerator();
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param Filesystem $filesystem
     * @return string
     */
    protected function getMigrationFileName(Filesystem $filesystem): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
                return $filesystem->glob($path.'*_create_permission_tables.php');
            })->push($this->app->databasePath()."/migrations/{$timestamp}_create_api_responder_tables.php")
            ->first();
    }

    private function registerModelGenerator(){
        $this->app->singleton('command.stackboom.responder.generator',function($app){
            return $app['StackBoom\ApiResponder\Commands\ResponderGenerateCommand'];
        });

        $this->commands('command.stackboom.responder.generator');
    }
}