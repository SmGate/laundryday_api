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


////  SERVICE

Route::post('addService', [ServiceController::class, 'addService']);
Route::get('allService', [ServiceController::class, 'getAllServices']);
Route::put('updateService', [ServiceController::class, 'update']);
Route::delete('deleteService/{id}', [ServiceController::class, 'deleteService']);


////  CATEGORY

Route::post('addCategory', [CategoryController::class, 'addCategory']);
Route::get('allCategory', [CategoryController::class, 'getAllCategory']);
Route::put('updateCategory/{id}', [CategoryController::class, 'updateCategory']);
Route::delete('deleteCategory/{id}', [CategoryController::class, 'deleteCategory']);



Route::middleware(['auth:sanctum'])->group(function () {
});
