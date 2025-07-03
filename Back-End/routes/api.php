<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AssuranceController;
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\PersonnelController;

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
Route::apiResource('clients', ClientController::class);
Route::apiResource('assurances', AssuranceController::class);
Route::apiResource('activites', ActiviteController::class);
Route::apiResource('personnels', PersonnelController::class); 
