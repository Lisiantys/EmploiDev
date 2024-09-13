<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\JobOfferController;
use App\Http\Controllers\Api\DeveloperController;
use App\Http\Controllers\Api\ApplicationController;

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

// Routes publiques (ne nécessitent pas d'authentification)

Route::middleware('web')->group(function () {
    Route::post('login', [AuthController::class, 'login']); //OK mais à voir avec le front
    Route::post('developers', [DeveloperController::class, 'store']);//OK
    Route::post('companies', [CompanyController::class, 'store']); //OK
});
    

Route::post('/developers/filter', [DeveloperController::class, 'filterForm']); 

Route::apiResource('developers', DeveloperController::class)->only(['index', 'show']); // Affichage, création, et affichage individuel de développeurs

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');//A revoir / voir avec le front

    //Affichage des candidatures du développeur
    //Route::get('/developers/{developer}/applications', [DeveloperController::class, 'developerApplications']);
    Route::apiResource('developers', DeveloperController::class, ['except' => ['index', 'store', 'show']]);
});








