<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Login / Register / forgot ...
Route::post('/register', 'Api\AuthUserController@register');
Route::post('/login', 'Api\AuthUserController@login');
Route::post('/forgotPassword', 'Api\AuthUserController@forgotPassword');
Route::post('/forgotPasswordStep2', 'Api\AuthUserController@forgotPassStep2');

// Contact
Route::post('/contact', 'Api\ContactController@create');
// Account
Route::get('/get-account/{user}', 'Api\AccountController@show');
Route::put('/update-account/{user}', 'Api\AccountController@update');
Route::delete('/delete-account/{user}', 'Api\AccountController@destroy');
Route::get('/getVille/{cp}', 'Api\AccountController@getVille');
Route::get('/getVilleIdFromCp/{id}', 'Api\AccountController@getVilleIdFromCp');
Route::post('/upload-avatar/{user}', 'Api\AccountController@uploadAvatar');
Route::get('/getAvatar/{user}', 'Api\AccountController@getAvatar');
// Portfolios
Route::get('/portfolios', 'Api\PortfoliosController@index');
Route::get('/portfolios/{portfolio}', 'Api\PortfoliosController@show');
Route::post('/create-portfolio', 'Api\PortfoliosController@store');
Route::get('/getLastPortfolio', 'Api\PortfoliosController@getLastPortfolio');
Route::put('/edit-portfolio/{portfolio}', 'Api\PortfoliosController@update');
Route::delete('/delete-portfolio/{portfolio}', 'Api\PortfoliosController@destroy');
Route::post('/uploads-portfolio/{portfolio}', 'Api\PortfoliosController@uploadsPortfolio');
Route::get('/getPortfolioImage/{portfolio}', 'Api\PortfoliosController@getPortfolioImage');