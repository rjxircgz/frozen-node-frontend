<?php namespace Frozennode\Frontend;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Frozennode\Frontend\DataTable\DataTable;
use Illuminate\Support\Facades\Validator as LValidator;
use Frozennode\Frontend\Fields\Factory as FieldFactory;
use Frozennode\Frontend\Config\Factory as ConfigFactory;
use Frozennode\Frontend\Actions\Factory as ActionFactory;
use Frozennode\Frontend\DataTable\Columns\Factory as ColumnFactory;

class FrontendServiceProvider extends ServiceProvider {

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
		$this->loadViewsFrom(__DIR__.'/../../views', 'frontend');

		$this->mergeConfigFrom(
			__DIR__.'/../../config/frontend.php', 'frontend'
		);

		$this->loadTranslationsFrom(__DIR__.'/../../lang', 'frontend');

		$this->publishes([
			__DIR__.'/../../config/frontend.php' => config_path('frontend.php'),
		]);

		$this->publishes([
			__DIR__.'/../../../public' => public_path('packages/frozennode/frontend'),
		], 'public');

		//set the locale
		$this->setLocale();

		$this->app['events']->fire('frontend.ready');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//include our view composers, and routes to avoid issues with catch-all routes defined by users
		include __DIR__.'/../../viewComposers.php';
		include __DIR__.'/../../routes.php';

		//the frontend validator
		$this->app['frontend_validator'] = $this->app->share(function($app)
		{
			//get the original validator class so we can set it back after creating our own
			$originalValidator = LValidator::make(array(), array());
			$originalValidatorClass = get_class($originalValidator);

			//temporarily override the core resolver
			LValidator::resolver(function($translator, $data, $rules, $messages) use ($app)
			{
				$validator = new Validator($translator, $data, $rules, $messages);
				$validator->setUrlInstance($app->make('url'));
				return $validator;
			});

			//grab our validator instance
			$validator = LValidator::make(array(), array());

			//set the validator resolver back to the original validator
			LValidator::resolver(function($translator, $data, $rules, $messages) use ($originalValidatorClass)
			{
				return new $originalValidatorClass($translator, $data, $rules, $messages);
			});

			//return our validator instance
			return $validator;
		});

		//set up the shared instances
		$this->app['frontend_config_factory'] = $this->app->share(function($app)
		{
			return new ConfigFactory($app->make('frontend_validator'), LValidator::make(array(), array()), config('frontend'));
		});

		$this->app['frontend_field_factory'] = $this->app->share(function($app)
		{
			return new FieldFactory($app->make('frontend_validator'), $app->make('itemconfig'), $app->make('db'));
		});

		$this->app['frontend_datatable'] = $this->app->share(function($app)
		{
			$dataTable = new DataTable($app->make('itemconfig'), $app->make('frontend_column_factory'), $app->make('frontend_field_factory'));
			$dataTable->setRowsPerPage($app->make('session.store'), config('frontend.global_rows_per_page'));

			return $dataTable;
		});

		$this->app['frontend_column_factory'] = $this->app->share(function($app)
		{
			return new ColumnFactory($app->make('frontend_validator'), $app->make('itemconfig'), $app->make('db'));
		});

		$this->app['frontend_action_factory'] = $this->app->share(function($app)
		{
			return new ActionFactory($app->make('frontend_validator'), $app->make('itemconfig'), $app->make('db'));
		});

		$this->app['frontend_menu'] = $this->app->share(function($app)
		{
			return new Menu($app->make('config'), $app->make('frontend_config_factory'));
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('frontend_validator', 'frontend_config_factory', 'frontend_field_factory', 'frontend_datatable', 'frontend_column_factory',
			'frontend_action_factory', 'frontend_menu');
	}

	/**
	 * Sets the locale if it exists in the session and also exists in the locales option
	 *
	 * @return void
	 */
	public function setLocale()
	{
		if ($locale = $this->app->session->get('frontend_locale'))
		{
			$this->app->setLocale($locale);
		}
	}

}
