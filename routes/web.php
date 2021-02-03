<?php

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

use App\Http\Controllers\ProcessosController;
use Illuminate\Http\Request;

//Route::get('/uses', ['uses' => 'UsersController@index']);
//Route::get('/users2', ['uses' => 'UsersController@index2']);
//Route::get('/processos2', [
//    'uses' => 'ProcessosController@processos',
//    'tipo' => 'distribuidos'
//]);
Route::resource('users', 'UsersController');
Route::resource('processos_grid', 'ProcessosController');
Route::resource('roles', 'RolesController');
Route::resource('dummy', 'DummyController');
//Route::resource('distribuidos/', 'ProcessosController');
Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/processos', 'HomeController@processos')->name('home');

Route::get('/processos/{processos}',
    function(Request $request,$processos){
        $controller = app()->make(ProcessosController::class, array($request));
        return $controller->callAction('carteira', array($processos));
    })
    ->name('distribuidos');

Route::get('processos/distribuidos/{carteira}',
    function(Request $request, $carteira){
        $controller = app()->make(ProcessosController::class, array($request));
        return $controller->callAction('distribuidos', array($carteira));
    })
    ->name('processos/distribuidos/');

//Route::get('/processos',
//    function(Request $request){
//        $controller = app()->make(ProcessosController::class, array($request));
//        return $controller->callAction('processos', array());
//    })
//    ->name('processos');


