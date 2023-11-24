<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CookieController;
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

Route::get('/calculate', function () {
    return view('calculate');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/calculate-highest-score', [CookieController::class, 'calculateHighestScore']);
Route::get('/get-ingredients', [CookieController::class, 'getIngredients']);

