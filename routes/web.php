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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/event/create', [\App\Http\Controllers\EventController::class, 'showCreate'])->name('showCreate');
Route::post('/event/create', [\App\Http\Controllers\EventController::class, 'create'])->name('eventCreate');
Route::get('/event/update/{id}', [\App\Http\Controllers\EventController::class, 'showUpdate'])->name('showUpdate');
Route::post('/event/update/{id}', [\App\Http\Controllers\EventController::class, 'update'])->name('eventUpdate');
Route::delete('/event/delete/{id}', [\App\Http\Controllers\EventController::class, 'delete'])->name('eventDelete');
Route::post('/event/attend/{id}', [\App\Http\Controllers\EventController::class, 'attend'])->name('attend');
Route::get('/home/search', [\App\Http\Controllers\HomeController::class, 'search'])->name('search');

