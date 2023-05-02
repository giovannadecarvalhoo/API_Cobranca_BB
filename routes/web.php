<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('token', 'APIController@token')->name('token');
Route::get('registrar', 'APIController@registrar')->name('registrar');
Route::get('listar', 'APIController@listar')->name('listar');
Route::get('consultar', 'APIController@consultar')->name('consultar');
Route::get('baixar', 'APIController@baixar')->name('baixar');
Route::get('atualizar', 'APIController@atualizar')->name('atualizar');
