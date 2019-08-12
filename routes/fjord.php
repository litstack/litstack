<?php

use Illuminate\Support\Str;

/*
 |
 |
 */

Route::group(['as' => 'fjord.'], function(){
    $ns = '\AwStudio\Fjord\Http\Controllers\\';

    Route::get('login', $ns.'FjordAuthController@login')->name('login');
    Route::post('login', $ns.'FjordAuthController@postLogin')->name('postLogin');

    Route::group(['middleware' => 'fjord.auth'], function () use ($ns) {
        Route::view('/', 'fjord::app')->name('dashboard');
        Route::put('/order', $ns.'FjordController@order');

        // Eloquent JS
        Route::resource('/eloquent', $ns.'FjordEloquentController');
        Route::post('/eloquent/destroy/{id}', $ns.'FjordEloquentController@destroy');
        Route::post('/eloquent/save-all', $ns.'FjordEloquentController@saveAll');

        // Relations
        Route::post('/relations', $ns.'FjordRelationsController@index');
        Route::post('/relations/store', $ns.'FjordRelationsController@store');
        Route::delete('/relations/{index}', $ns.'FjordRelationsController@delete');

        Route::put('/media/attributes', $ns.'FjordMediaController@attributes');
        Route::resource('/media', $ns.'FjordMediaController')->only(['store', 'destroy']);

        Route::resource('/model-content', "{$ns}ModelContentController");

        // Repeatables CRUD
        Route::resource('/repeatables', "{$ns}FjordRepeatableController");

        // Pages
        Route::get('/pages/{page}', $ns.'FjordPageController@show');

        // Page Content
        Route::put('/pages/{page}/page-content/{field_name}', "{$ns}FjordPageContentController@update");

        Route::get('/fjord-users', $ns.'FjordUserController@index')->name('users');

        /**
         * Make resourceful routes for all crudable models
         * that are defined in config/fjord-crud.php
         */

        foreach(Fjord::cruds() as $crud) {
            Route::resource("/{$crud}", "Fjord\\" . ucfirst(Str::singular($crud)) . "Controller")->except(['show']);
        }

        Route::post('logout', $ns.'FjordAuthController@logout')->name('logout');
    });
});
