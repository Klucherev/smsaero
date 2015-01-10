<?php namespace Klucherev\Smsaero;

use Illuminate\Support\ServiceProvider;
use Config;

class SmsaeroServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
   * Bootstrap the application events.
   *
   * @return void
   */
	public function boot()
	{
		$this->package('klucherev/smsaero');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$app = $this->app;

		// Get config loader
		$loader = $this->app['config']->getLoader();

  		// Get environment name
		$env = $this->app['config']->getEnvironment();

  		// Add package namespace with path set, override package if app config exists in the main app directory
		if (file_exists(app_path() .'/config/packages/klucherev/smsaero')) {
			$loader->addNamespace('smsaero', app_path() .'/config/packages/klucherev/smsaero');
		} else {
			$loader->addNamespace('smsaero',__DIR__.'/../../config');
		}

		// Load package override config file
		$config = $loader->load($env,'config','smsaero');

  		// Override value
		$this->app['config']->set('smsaero::config',$config);


		$app['smsaero'] = $app->share(function ($app) {
			$login    = $this->app['config']->get('smsaero::smsaero_login');
			$password = $this->app['config']->get('smsaero::smsaero_password');
			$from     = $this->app['config']->get('smsaero::smsaero_from');
            return new Smsaero($login, $password, $from);
        });

		$this->app->booting(function()
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('Smsaero', 'Klucherev\Smsaero\Facades\Smsaero');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('smsaero');
	}

}
