<?php

use Illuminate\Support\Facades\View;

//frontend index view
View::composer('frontend::index', function($view)
{
	//get a model instance that we'll use for constructing stuff
	$config = app('itemconfig');
	$fieldFactory = app('frontend_field_factory');
	$columnFactory = app('frontend_column_factory');
	$actionFactory = app('frontend_action_factory');
	$dataTable = app('frontend_datatable');
	$model = $config->getDataModel();
	$baseUrl = route('frontend_dashboard');
	$route = parse_url($baseUrl);

	//add the view fields
	$view->config = $config;
	$view->dataTable = $dataTable;
	$view->primaryKey = $model->getKeyName();
	$view->editFields = $fieldFactory->getEditFields();
	$view->arrayFields = $fieldFactory->getEditFieldsArrays();
	$view->dataModel = $fieldFactory->getDataModel();
	$view->columnModel = $columnFactory->getColumnOptions();
	$view->actions = $actionFactory->getActionsOptions();
	$view->globalActions = $actionFactory->getGlobalActionsOptions();
	$view->actionPermissions = $actionFactory->getActionPermissions();
	$view->filters = $fieldFactory->getFiltersArrays();
	$view->rows = $dataTable->getRows(app('db'), $view->filters);
	$view->formWidth = $config->getOption('form_width');
	$view->baseUrl = $baseUrl;
	$view->assetUrl = url('packages/frozennode/frontend/');
	$view->route = $route['path'].'/';
	$view->itemId = isset($view->itemId) ? $view->itemId : null;
});

//frontend settings view
View::composer('frontend::settings', function($view)
{
	$config = app('itemconfig');
	$fieldFactory = app('frontend_field_factory');
	$actionFactory = app('frontend_action_factory');
	$baseUrl = route('frontend_dashboard');
	$route = parse_url($baseUrl);

	//add the view fields
	$view->config = $config;
	$view->editFields = $fieldFactory->getEditFields();
	$view->arrayFields = $fieldFactory->getEditFieldsArrays();
	$view->actions = $actionFactory->getActionsOptions();
	$view->baseUrl = $baseUrl;
	$view->assetUrl = url('packages/frozennode/frontend/');
	$view->route = $route['path'].'/';
});

//header view
View::composer(array('frontend::partials.header'), function($view)
{
	$view->menu = app('frontend_menu')->getMenu();
	$view->settingsPrefix = app('frontend_config_factory')->getSettingsPrefix();
	$view->pagePrefix = app('frontend_config_factory')->getPagePrefix();
	$view->configType = app()->bound('itemconfig') ? app('itemconfig')->getType() : false;
});

//the layout view
View::composer(array('frontend::layouts.default'), function($view)
{
	//set up the basic asset arrays
	$view->css = array();
	$view->js = array(
		'jquery' => asset('packages/frozennode/frontend/js/jquery/jquery-1.8.2.min.js'),
		'jquery-ui' => asset('packages/frozennode/frontend/js/jquery/jquery-ui-1.10.3.custom.min.js'),
		'customscroll' => asset('packages/frozennode/frontend/js/jquery/customscroll/jquery.customscroll.js'),
	);

	//add the non-custom-page css assets
	if (!$view->page && !$view->dashboard)
	{
		$view->css += array(
			'jquery-ui' => asset('packages/frozennode/frontend/css/ui/jquery-ui-1.9.1.custom.min.css'),
			'jquery-ui-timepicker' => asset('packages/frozennode/frontend/css/ui/jquery.ui.timepicker.css'),
			'select2' => asset('packages/frozennode/frontend/js/jquery/select2/select2.css'),
			'jquery-colorpicker' => asset('packages/frozennode/frontend/css/jquery.lw-colorpicker.css'),
		);
	}

	//add the package-wide css assets
	$view->css += array(
		'customscroll' => asset('packages/frozennode/frontend/js/jquery/customscroll/customscroll.css'),
		'main' => asset('packages/frozennode/frontend/css/main.css'),
	);

	//add the non-custom-page js assets
	if (!$view->page && !$view->dashboard)
	{
		$view->js += array(
			'select2' => asset('packages/frozennode/frontend/js/jquery/select2/select2.js'),
			'jquery-ui-timepicker' => asset('packages/frozennode/frontend/js/jquery/jquery-ui-timepicker-addon.js'),
			'ckeditor' => asset('packages/frozennode/frontend/js/ckeditor/ckeditor.js'),
			'ckeditor-jquery' => asset('packages/frozennode/frontend/js/ckeditor/adapters/jquery.js'),
			'markdown' => asset('packages/frozennode/frontend/js/markdown.js'),
			'plupload' => asset('packages/frozennode/frontend/js/plupload/js/plupload.full.js'),
		);

		//localization js assets
		$locale = config('app.locale');

		if ($locale !== 'en')
		{
			$view->js += array(
				'plupload-l18n' => asset('packages/frozennode/frontend/js/plupload/js/i18n/'.$locale.'.js'),
				'timepicker-l18n' => asset('packages/frozennode/frontend/js/jquery/localization/jquery-ui-timepicker-'.$locale.'.js'),
				'datepicker-l18n' => asset('packages/frozennode/frontend/js/jquery/i18n/jquery.ui.datepicker-'.$locale.'.js'),
				'select2-l18n' => asset('packages/frozennode/frontend/js/jquery/select2/select2_locale_'.$locale.'.js'),
			);
		}

		//remaining js assets
		$view->js += array(
			'knockout' => asset('packages/frozennode/frontend/js/knockout/knockout-2.2.0.js'),
			'knockout-mapping' => asset('packages/frozennode/frontend/js/knockout/knockout.mapping.js'),
			'knockout-notification' => asset('packages/frozennode/frontend/js/knockout/KnockoutNotification.knockout.min.js'),
			'knockout-update-data' => asset('packages/frozennode/frontend/js/knockout/knockout.updateData.js'),
			'knockout-custom-bindings' => asset('packages/frozennode/frontend/js/knockout/custom-bindings.js'),
			'accounting' => asset('packages/frozennode/frontend/js/accounting.js'),
			'colorpicker' => asset('packages/frozennode/frontend/js/jquery/jquery.lw-colorpicker.min.js'),
			'history' => asset('packages/frozennode/frontend/js/history/native.history.js'),
			'frontend' => asset('packages/frozennode/frontend/js/frontend.js'),
			'settings' => asset('packages/frozennode/frontend/js/settings.js'),
		);
	}

	$view->js += array('page' => asset('packages/frozennode/frontend/js/page.js'));
});
