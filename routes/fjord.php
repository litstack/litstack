<?php

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
        Route::put('/media/attributes', $ns.'FjordMediaController@attributes');
        Route::resource('/media', $ns.'FjordMediaController')->only(['store', 'destroy']);


        /**
         * Make resourceful routes for all crudable models
         * that are defined in config/fjord-crud.php
         */
        if(config('fjord-crud') !== null){
            foreach (config('fjord-crud') as $field => $content) {
                Route::resource('/'.lcfirst(str_plural($field)), 'Fjord\\'.ucfirst(str_singular($field)).'Controller')->except(['show']);
            }
        }

        Route::post('logout', $ns.'FjordAuthController@logout')->name('logout');
    });
});
