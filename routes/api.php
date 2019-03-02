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
Route::post('/edit-upload-portfolio/{portfolio}/{number_filename}', 'Api\PortfoliosController@editUploadsPortfolio');
Route::post('/uploads-portfolio/{portfolio}', 'Api\PortfoliosController@uploadsPortfolio');
Route::get('/getPortfolioImage/{portfolio}', 'Api\PortfoliosController@getPortfolioImage');
Route::delete('/remove-images-portfolio/{portfolio}/{number_filename}', 'Api\PortfoliosController@removeImagesPortfolio');
// CV
Route::get('/cv', 'Api\CvController@index');
Route::get('/cv/{cv}', 'Api\CvController@show');
Route::get('/my-cv/{user_id}', 'Api\CvController@myCv');
Route::get('/my-group-competence/{competenceGroupId}', 'Api\CvController@myGroupCompetence');
Route::post('/create-groupe-competence', 'Api\CvController@addCompetenceGroup');
Route::put('/edit-groupe-competence/{competenceGroup}', 'Api\CvController@updateCompetenceGroup');
Route::get('/competence-groupe', 'Api\CvController@getCompetenceGroup');
Route::get('/competence-groupe/{competenceGroup}', 'Api\CvController@showCompetenceGroup');
Route::post('/create-cv', 'Api\CvController@store');
Route::put('/edit-cv/{cv}', 'Api\CvController@update');
Route::post('/add-formation/{cv}', 'Api\CvController@addFormation');
Route::post('/add-competence/{cv}', 'Api\CvController@addCompetence');
Route::post('/add-experience/{cv}', 'Api\CvController@addExperience');
Route::post('/add-competence-langue/{cv}', 'Api\CvController@addCompetenceLangue');
Route::post('/add-loisir/{cv}', 'Api\CvController@addLoisir');
Route::post('/upload-cv/{cv}', 'Api\CvController@uploadFile');
Route::delete('/remove-formation/{formation}', 'Api\CvController@removeFormation');
Route::delete('/remove-competence/{competence}', 'Api\CvController@removeCompetence');
Route::delete('/remove-experience/{experience}', 'Api\CvController@removeExperience');
Route::delete('/remove-competence-langue/{competenceLangue}', 'Api\CvController@removeCompetenceLangue');
Route::delete('/remove-loisir/{loisir}', 'Api\CvController@removeLoisir');
Route::delete('/delete-cv/{cv}', 'Api\CvController@destroy');
// BLOG
Route::get('/blog', 'Api\BlogController@index');
Route::post('/create-blog', 'Api\BlogController@store');
Route::get('/blog/{blog}', 'Api\BlogController@show');
Route::put('/edit-blog/{blog}', 'Api\BlogController@update');
Route::delete('/delete-blog/{blog}', 'Api\BlogController@destroy');
Route::post('/edit-upload-blog/{blog}/{number_filename}', 'Api\BlogController@editUploadsBlog');
Route::post('/uploads-blog/{blog}', 'Api\BlogController@uploadsBlog');
Route::get('/getBlogMedia/{blog}', 'Api\BlogController@getBlogMedia');
Route::delete('/remove-images-blog/{blog}/{number_filename}', 'Api\BlogController@removeImagesBlog');