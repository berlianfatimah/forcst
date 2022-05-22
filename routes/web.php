<?php

use App\Http\Controllers\ActualController;
use App\Http\Controllers\CalculateController;
use App\Http\Controllers\CalculateSESController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProvinceController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Auth::routes();
Route::middleware('auth')->group(function () {
    Route::get('/master', function () {
        return view('layouts/master');
    });
    Route::get('/', function () {
        return view('dash');
    });
    
    
    Route::resource('actual', ActualController::class);
    Route::resource('province', ProvinceController::class);
    Route::get('forecasting',[CalculateController::class, 'forecastingSelect']);
    // Route::get('forecastingSES',[CalculateController::class, 'forecastingSelect']);
    Route::post('calculate', [CalculateController::class, 'index']);
    // Route::post('calculateses', [CalculateController::class, 'indexSES']);
    
    
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});