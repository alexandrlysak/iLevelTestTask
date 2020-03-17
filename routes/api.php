<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'ApiAuthController@login');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'ApiAuthController@logout');
    });
});

Route::post('/get-categories-list', 'ApiController@getCategoriesListAction')->name('getCategoriesList');
Route::post('/get-category-products-list', 'ApiController@getCategoryProductsListAction')->name('getCategoryProductsList');

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('/add-category', 'ApiCategoryController@addCategoryAction');
    Route::post('/edit-category', 'ApiCategoryController@editCategoryAction');
    Route::post('/delete-category', 'ApiCategoryController@deleteCategoryAction');

    Route::post('/add-product', 'ApiProductController@addProductAction');
    Route::post('/edit-product', 'ApiProductController@editProductAction');
    Route::post('/delete-product', 'ApiProductController@deleteProductAction');
});


