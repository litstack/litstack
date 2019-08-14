<?php

use Illuminate\Support\Str;

/*
 |
 |
 */

Route::group(['as' => 'fjord.'], function(){
    $ns = '\AwStudio\Fjord\Http\Controllers\\';

    Route::get('login', 'FjordAuthController@login')->name('login');
    Route::post('login', 'FjordAuthController@postLogin')->name('postLogin');

    Route::group(['middleware' => 'fjord.auth'], function () use ($ns) {
        Route::view('/', 'fjord::app')->name('dashboard');
        Route::put('/order', 'FjordController@order');

        // Eloquent JS
        Route::resource('/eloquent', 'FjordEloquentController');
        Route::post('/eloquent/destroy/{id}', 'FjordEloquentController@destroy');
        Route::post('/eloquent/save-all', 'FjordEloquentController@saveAll');

        // Relations
        Route::put('/relation', 'FjordRelationsController@updateHasOne');
        Route::post('/relations', 'FjordRelationsController@index');
        Route::post('/relations/store', 'FjordRelationsController@store');
        Route::delete('/relations/{index}', 'FjordRelationsController@delete');

        Route::put('/media/attributes', 'FjordMediaController@attributes');
        Route::resource('/media', 'FjordMediaController')->only(['store', 'destroy']);

        Route::resource('/model-content', "ModelContentController");

        // Repeatables CRUD
        Route::resource('/repeatables', "FjordRepeatableController");

        // Pages
        Route::get('/pages/{page}', 'FjordPageController@show');

        // Page Content
        Route::put('/pages/{page}/page-content/{field_name}', "FjordPageContentController@update");

        Route::get('/fjord-users', 'FjordUserController@index')->name('users');

        /**
         * Make resourceful routes for all crudable models
         * that are defined in config/fjord-crud.php
         */

        foreach(Fjord::cruds() as $key => $crud) {
            Route::resource("/{$crud}", "\\App\\Http\\Controllers\\Fjord\\" . ucfirst(Str::singular($crud)) . "Controller")->except(['show']);
        }

        Route::post('logout', 'FjordAuthController@logout')->name('logout');
    });
});
