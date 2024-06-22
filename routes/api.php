<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceTimingController;
use App\Http\Controllers\LaundryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::middleware(['auth:sanctum'])->group(function () {
});




Route::post('/countries/{id}', [CountryController::class, 'getCountry']);
Route::post('/states/{country_id}', [StateController::class, 'getStates']);
Route::post('/cities/{state_id}', [CityController::class, 'getCities']);


///  AUTH CONTROLLER

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


////  SERVICES

Route::post('addService', [ServiceController::class, 'addService']);
Route::get('allService', [ServiceController::class, 'getAllServices']);
Route::put('updateService', [ServiceController::class, 'update']);
Route::delete('deleteService/{id}', [ServiceController::class, 'deleteService']);


////  CATEGORIES

Route::post('addCategory', [CategoryController::class, 'addCategory']);
Route::get('allCategory', [CategoryController::class, 'getAllCategory']);
Route::post('updateCategory', [CategoryController::class, 'updateCategory']);
Route::delete('deleteCategory/{id}', [CategoryController::class, 'deleteCategory']);

////  Service Timings

Route::post('add-service-timing', [ServiceTimingController::class, 'addServiceTiming']);
Route::get('service-timings', [ServiceTimingController::class, 'getAllServiceTiming']);
Route::delete('delete-service-timing/{id}', [ServiceTimingController::class, 'deleteServiceTiming']);
Route::post('update-service-timing', [ServiceTimingController::class, 'updateServiceTiming']);


// Laundries
Route::prefix('laundries')->group(function () {
Route::post('/register', [LaundryController::class, 'register']);
Route::post('/verify', [LaundryController::class, 'verify']);
Route::delete('delete/{id}', [LaundryController::class, 'delete']);
Route::get('all', [LaundryController::class, 'all']);
Route::post('update', [LaundryController::class, 'update']);



    
});

Route::middleware(['auth:sanctum'])->group(function () {
});
