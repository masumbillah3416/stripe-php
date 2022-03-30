<?php

use App\Http\Controllers\PaymentsController;
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

Route::get('/payment', [PaymentsController::class, 'index']);
Route::get('/success', [PaymentsController::class, 'success']);
Route::get('/failed', [PaymentsController::class, 'failed']);
Route::post('/charge', [PaymentsController::class, 'charge']);
Route::get('/', function () {
    return view('welcome');
});
