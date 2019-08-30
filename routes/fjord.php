<?php



/*
 |
 |
 */

Route::group(['as' => 'fjord.'], function(){

    Route::group(['middleware' => 'fjord.auth'], function () {

        // Eloquent JS
        Route::resource('/eloquent', '\AwStudio\Fjord\EloquentJs\EloquentJsController');
        Route::post('/eloquent/destroy/{id}', '\AwStudio\Fjord\EloquentJs\EloquentJsController@destroy');
        Route::post('/eloquent/save-all', '\AwStudio\Fjord\EloquentJs\EloquentJsController@saveAll');

    });
});
