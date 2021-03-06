<?php namespace Syscover\Market;

use Illuminate\Support\ServiceProvider;
use Syscover\Market\GraphQL\MarketGraphQLServiceProvider;

class MarketServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        // register routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        // register migrations
        $this->loadMigrationsFrom(__DIR__ .'/../../database/migrations');

        // register translations
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'market');

        // register seeds
        $this->publishes([
            __DIR__ . '/../../database/seeds/' => base_path('/database/seeds')
        ], 'seeds');

        // register config files
        $this->publishes([
            __DIR__ . '/../../config/pulsar-market.php' => config_path('pulsar-market.php'),
        ]);

        // register tests
        $this->publishes([
            __DIR__ . '/../../tests/Feature' => base_path('/tests/Feature')
        ], 'tests');
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
        //
	}
}
