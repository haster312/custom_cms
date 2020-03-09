<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'blog'], function () {
    Route::get('/model', 'BlogController@modelObject');
    Route::get('/', 'BlogController@list');
    Route::post('/', 'BlogController@new');
    Route::put('/{id}', 'BlogController@update');

    Route::group(['prefix' => 'category'], function () {
        Route::get('/', 'BlogCategoryController@list');
        Route::post('/', 'BlogCategoryController@new');
        Route::put('/{id}', 'BlogCategoryController@update');
    });
});
