<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'IndexController@homepage')->name('homepage');

Route::get('/products', 'ProductController@index')->name('products');

Route::post('/products/add', 'ProductController@store')->name('product.store'); 
