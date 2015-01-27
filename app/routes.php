<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

/*
 * Drivers section 
 */
Route::any('/getDrivers', array(
    'uses' => 'DriversController@getDrivers',
    'as' => 'getDrivers'
));

Route::any('/loginDriver', array(
    'uses' => 'DriversController@loginDriver',
    'as' => 'loginDriver'
));

Route::any('/registerNewDriver', array(
    'uses' => 'DriversController@registerNewDriver',
    'as' => 'registerNewDriver'
));

Route::any('/getNearestDriver', array(
    'uses' => 'DriversController@getNearestDriver',
    'as' => 'getNearestDriver'
));

/*
 * Taxi Users section
 */

Route::any('/getTaxiUsers', array(
    'uses' => 'TaxiUsersController@getTaxiUsers',
    'as' => 'getTaxiUsers'
));

Route::any('/registerNewTaxiUser', array(
    'uses' => 'TaxiUsersController@registerNewTaxiUser',
    'as' => 'registerNewTaxiUser'
));

Route::any('/loginTaxiUser', array(
    'uses' => 'TaxiUsersController@loginTaxiUser',
    'as' => 'loginTaxiUser'
));


