<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'blog'], function () {
    Route::get('/model', 'BlogController@modelObject');
    Route::get('/paginate', 'BlogController@paginate');
    Route::get('/{id}', 'BlogController@detail')->where('id', '[0-9]+');
    Route::post('/', 'BlogController@new');
    Route::put('/{id}', 'BlogController@update')->where('id', '[0-9]+');
    Route::delete('/{id}', 'BlogController@delete')->where('id', '[0-9]+');

    Route::group(['prefix' => 'category'], function () {
        Route::get('/paginate', 'BlogCategoryController@paginate');
        Route::get('/', 'BlogCategoryController@list');
        Route::post('/', 'BlogCategoryController@new');
        Route::put('/{id}', 'BlogCategoryController@update')->where('id', '[0-9]+');
    });
});
