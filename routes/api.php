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

//Développeurs
Route::post('/developers/filter', [DeveloperController::class, 'filterForm']); //OK
Route::apiResource('developers', DeveloperController::class)->only(['index', 'show']); //OK

//Entreprises
Route::get('/companies/{company}', [CompanyController::class, 'show']);//OK

//Offres d'emplois
Route::get('/job-offers', [JobOfferController::class, 'index']); //OK
Route::get('/job-offers/{jobOffer}', [JobOfferController::class, 'show']); //OK
Route::post('/job-offers/filter', [JobOfferController::class, 'filterForm']); //OK

Route::middleware('web')->group(function () {
    Route::post('/login', [AuthController::class, 'login']); //OK 
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum'); //OK

    //Développeurs
    Route::post('/developers', [DeveloperController::class, 'store']); //OK
    Route::middleware('auth:sanctum')->group(function () {
        Route::put('/developers/{developer}', [DeveloperController::class, 'update']); // OK
        Route::delete('/developers/{developer}', [DeveloperController::class, 'destroy']); // OK
        Route::get('/developers/applications/{developer}', [DeveloperController::class, 'developerApplications']); // OK
    });

    //Entreprises
    Route::post('/companies', [CompanyController::class, 'store']); //OK 
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('companies', CompanyController::class)->except(['index', 'show', 'store']);//OK
        Route::get('/companies/{company}/job-offers', [CompanyController::class, 'jobOffersCompany']);//OK
        Route::get('/companies/{jobOffer}/applications', [CompanyController::class, 'jobOfferApplications']);//OK
    });

    //Offres d'emplois
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/job-offers', [JobOfferController::class, 'store']); //OK
        Route::delete('/job-offers/{jobOffer}', [JobOfferController::class, 'destroy']); //OK
    });
});
