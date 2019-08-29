<?php



/*
 |
 |
 */

Route::group(['as' => 'fjord.'], function(){

    Route::group(['middleware' => 'fjord.auth'], function () {

        // Eloquent JS
        Route::resource('/eloquent', 'EloquentJs\EloquentJsController');
        Route::post('/eloquent/destroy/{id}', 'EloquentJs\EloquentJsController@destroy');
        Route::post('/eloquent/save-all', 'EloquentJs\EloquentJsController@saveAll');

    });
});
