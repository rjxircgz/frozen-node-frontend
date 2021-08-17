<?php

use Illuminate\Support\Facades\Route;

/**
 * Routes
 */
Route::group(array('prefix' => config('frontend.uri'), 'middleware' => 'Frozennode\Frontend\Http\Middleware\ValidateFrontend'), function()
{
	//Frontend Dashboard
	Route::get('/', array(
		'as' => 'frontend_dashboard',
		'uses' => 'Frozennode\Frontend\FrontendController@dashboard',
	));

	//File Downloads
	Route::get('file_download', array(
		'as' => 'frontend_file_download',
		'uses' => 'Frozennode\Frontend\FrontendController@fileDownload'
	));

	//Custom Pages
	Route::get('page/{page}', array(
		'as' => 'frontend_page',
		'uses' => 'Frozennode\Frontend\FrontendController@page'
	));

	Route::group(array('middleware' => ['Frozennode\Frontend\Http\Middleware\ValidateSettings', 'Frozennode\Frontend\Http\Middleware\PostValidate']), function()
	{
		//Settings Pages
		Route::get('settings/{settings}', array(
			'as' => 'frontend_settings',
			'uses' => 'Frozennode\Frontend\FrontendController@settings'
		));

		//Display a settings file
		Route::get('settings/{settings}/file', array(
			'as' => 'frontend_settings_display_file',
			'uses' => 'Frozennode\Frontend\FrontendController@displayFile'
		));

		//Save Item
		Route::post('settings/{settings}/save', array(
			'as' => 'frontend_settings_save',
			'uses' => 'Frozennode\Frontend\FrontendController@settingsSave'
		));

		//Custom Action
		Route::post('settings/{settings}/custom_action', array(
			'as' => 'frontend_settings_custom_action',
			'uses' => 'Frozennode\Frontend\FrontendController@settingsCustomAction'
		));

		//Settings file upload
		Route::post('settings/{settings}/{field}/file_upload', array(
			'as' => 'frontend_settings_file_upload',
			'uses' => 'Frozennode\Frontend\FrontendController@fileUpload'
		));
	});

	//Switch locales
	Route::get('switch_locale/{locale}', array(
		'as' => 'frontend_switch_locale',
		'uses' => 'Frozennode\Frontend\FrontendController@switchLocale'
	));

	//The route group for all other requests needs to validate frontend, model, and add assets
	Route::group(array('middleware' => ['Frozennode\Frontend\Http\Middleware\ValidateModel', 'Frozennode\Frontend\Http\Middleware\PostValidate']), function()
	{
		//Model Index
		Route::get('{model}', array(
			'as' => 'frontend_index',
			'uses' => 'Frozennode\Frontend\FrontendController@index'
		));

		//New Item
		Route::get('{model}/new', array(
			'as' => 'frontend_new_item',
			'uses' => 'Frozennode\Frontend\FrontendController@item'
		));

		//Update a relationship's items with constraints
		Route::post('{model}/update_options', array(
			'as' => 'frontend_update_options',
			'uses' => 'Frozennode\Frontend\FrontendController@updateOptions'
		));

		//Display an image or file field's image or file
		Route::get('{model}/file', array(
			'as' => 'frontend_display_file',
			'uses' => 'Frozennode\Frontend\FrontendController@displayFile'
		));

		//Updating Rows Per Page
		Route::post('{model}/rows_per_page', array(
			'as' => 'frontend_rows_per_page',
			'uses' => 'Frozennode\Frontend\FrontendController@rowsPerPage'
		));

		//Get results
		Route::post('{model}/results', array(
			'as' => 'frontend_get_results',
			'uses' => 'Frozennode\Frontend\FrontendController@results'
		));

		//Custom Model Action
		Route::post('{model}/custom_action', array(
			'as' => 'frontend_custom_model_action',
			'uses' => 'Frozennode\Frontend\FrontendController@customModelAction'
		));

		//Get Item
		Route::get('{model}/{id}', array(
			'as' => 'frontend_get_item',
			'uses' => 'Frozennode\Frontend\FrontendController@item'
		));

		//File Uploads
		Route::post('{model}/{field}/file_upload', array(
			'as' => 'frontend_file_upload',
			'uses' => 'Frozennode\Frontend\FrontendController@fileUpload'
		));

		//Save Item
		Route::post('{model}/{id?}/save', array(
			'as' => 'frontend_save_item',
			'uses' => 'Frozennode\Frontend\FrontendController@save'
		));

		//Delete Item
		Route::post('{model}/{id}/delete', array(
			'as' => 'frontend_delete_item',
			'uses' => 'Frozennode\Frontend\FrontendController@delete'
		));

		//Custom Item Action
		Route::post('{model}/{id}/custom_action', array(
			'as' => 'frontend_custom_model_item_action',
			'uses' => 'Frozennode\Frontend\FrontendController@customModelItemAction'
		));
	});
});