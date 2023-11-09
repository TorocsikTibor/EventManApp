<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
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
    return view('auth.login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::group(['prefix' => 'event'], function () {
        Route::get('/create', [EventController::class, 'showCreate'])->name('showCreate');
        Route::post('/create', [EventController::class, 'create'])->name('eventCreate');
        Route::post('/attend/{id}', [EventController::class, 'attend'])->name('attend');
        Route::get('/search', [EventController::class, 'search'])->name('search');

        Route::middleware(['owner'])->group(function() {
            Route::get('/update/{id}', [EventController::class, 'showUpdate'])->name('showUpdate');
            Route::post('/update/{id}', [EventController::class, 'update'])->name('eventUpdate');
            Route::delete('/delete/{id}', [EventController::class, 'delete'])->name('eventDelete');
        });
    });

});






