<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'App\Http\Controllers'], function () {

    //auth
    Route::post('login', 'UserController@login')->name('login');
    Route::post('user/create', 'UserController@store');

    Route::group(['middleware' => 'auth:sanctum', 'cors'], function () {
        // user
        Route::get('users', 'UserController@index');
        Route::get('user/{id}', 'UserController@show');
        Route::post('user/update/{id}', 'UserController@update');
        Route::get('user/delete/{id}', 'UserController@destroy');
        Route::delete('logout', 'UserController@logout');

        //brand
        Route::get('brands', 'BrandController@index');
        Route::get('brand/{id}', 'BrandController@show');
        Route::post('brand/create', 'BrandController@store');
        Route::post('brand/update/{id}', 'BrandController@update');
        Route::delete('brand/delete', 'BrandController@destroy');

        //branch
        Route::get('branches', 'BranchController@index');
        Route::get('branch/{id}', 'BranchController@show');
        Route::post('branch/create', 'BranchController@store');
        Route::post('branch/update/{id}', 'BranchController@update');
        Route::delete('branch/delete/{id}', 'BranchController@destroy');
        Route::get('countOfBranch/{brand}', 'BranchController@countOfBranch');

        //image upload
        Route::post('createImage', 'ImageController@create');
        // currency
        Route::get('getCurrency', 'CurrencyController@getCurrency');
    });
});

