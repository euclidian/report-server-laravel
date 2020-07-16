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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/allUser', 'Api\Web\Admin\UserController@allUser');
Route::get('/toAdmin/{id}', 'Api\Web\Admin\UserController@toAdmin');
Route::get('/generateToken/{id}', 'Api\Web\Admin\UserController@generateToken');
Route::get('/allPrinted', 'Api\Web\PrintApi@allPrinted');
Route::get('/allPrintTransaction', "Api\Web\PrintApi@allPrintTransaction");
Route::get('/download/{id}', "Api\Web\PrintApi@download");
Route::post('/addUser', 'Api\Web\Admin\UserController@addUser');

Route::get('/allJRXML', "Api\Web\JRXMLController@allJRXML");
Route::post('/addJRXML', "Api\Web\JRXMLController@addJRXML");
Route::get('/deleteJRXML/{id}', "Api\Web\JRXMLController@deleteJRXML");

Route::get('/getUser', 'Api\Web\ProfilController@getUser');
Route::post('/updatePassword', 'Api\Web\ProfilController@updatePassword');

Route::post('/client/print', "Api\Client\PrintApi@print");
Route::post('/client/print_client', "Api\Client\PrintApi@print");
Route::post('/client/print_transaction', "Api\Client\PrintApi@printTransaction");
Route::get('/client/allPrintTransaction', "Api\Client\PrintApi@allPrintTransaction");

Route::get('/client/allUnPrinted', "Api\Client\PrintApi@allUnPrinted");
Route::get('/client/download/{id}', "Api\Client\PrintApi@download");

Route::get('/client/allJRXML', "Api\Client\JRXMLController@allJRXML");

Route::get('/allJRXMLGallery', "Api\Web\JRXMLGalleryController@allJRXML");
Route::get('/detailJRXMLGallery/{id}', "Api\Web\JRXMLGalleryController@detailJRXML");
Route::post('/addJRXMLGallery', "Api\Web\Admin\JRXMLGalleryController@addJRXML");
Route::get('/deleteJRXMLGallery/{id}', "Api\Web\Admin\JRXMLGalleryController@deleteJRXML");
Route::post('/copyJRXMLGallery', "Api\Web\JRXMLGalleryController@copyJRXML");
Route::post('/searchJRXMLGallery', "Api\Web\JRXMLGalleryController@searchJRXML");

Route::get('/client/allUnPrinted', "Api\Client\PrintApi@allUnPrinted");
