<?php
namespace Ndlovu28\BpoMailer\Providers;
use Illuminate\Support\ServiceProvider;

use Ndlovu28\BpoMailer\Console\SystemInit;

class BpoMailerProvider extends ServiceProvider{
	/**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(){
    	$this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    	$this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    	$this->loadViewsFrom(__DIR__.'/../views', 'ndlovu28');

    	if ($this->app->runningInConsole()) {
    		$this->commands([
    			SystemInit::class,
    		]);
    	}
    }
}