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
Route::get('/developers/filter', [DeveloperController::class, 'filterForm']); //OK //Excel
Route::apiResource('developers', DeveloperController::class)->only(['index', 'show']); //OK //Excel

//Route pour peuplé le formulaire de filtrage
Route::get('/programming-languages', [DeveloperController::class, 'getProgrammingLanguages']);
Route::get('/types-contracts', [DeveloperController::class, 'getTypesContracts']);
Route::get('/types-developers', [DeveloperController::class, 'getTypesDevelopers']);
Route::get('/years-experiences', [DeveloperController::class, 'getYearsExperiences']);
Route::get('/locations', [DeveloperController::class, 'getLocations']);

//Entreprises
Route::get('/companies/{company}', [CompanyController::class, 'show']);//OK //EXcel

//Offres d'emplois
Route::get('/job-offers', [JobOfferController::class, 'index']); //OK //Excel
Route::get('/job-offers/{jobOffer}', [JobOfferController::class, 'show']); //OK //Excel
Route::post('/job-offers/filter', [JobOfferController::class, 'filterForm']); //OK //Excel

Route::middleware('web')->group(function () {
    Route::post('/login', [AuthController::class, 'login']); //OK //Excel
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum'); //OK //Excel

    //Développeurs
    Route::post('/developers', [DeveloperController::class, 'store']); //OK //Excel
    Route::middleware('auth:sanctum')->group(function () {
        Route::put('/developers/{developer}', [DeveloperController::class, 'update']); // OK //Excel
        Route::delete('/developers/{developer}', [DeveloperController::class, 'destroy']); // OK //Excel
        Route::get('/developers/applications/{developer}', [DeveloperController::class, 'developerApplications']); // OK //Excel
    });

    //Entreprises
    Route::post('/companies', [CompanyController::class, 'store']); //OK  //Excel
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('companies', CompanyController::class)->except(['index', 'show', 'store']);//OK //EXCEL
        Route::get('/companies/{company}/job-offers', [CompanyController::class, 'jobOffersCompany']);//OK //EXCEL
        Route::get('/companies/{jobOffer}/applications', [CompanyController::class, 'jobOfferApplications']);//OK //EXCEL
    });

    //Offres d'emplois
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/job-offers', [JobOfferController::class, 'store']); //OK //EXCEL
        Route::delete('/job-offers/{jobOffer}', [JobOfferController::class, 'destroy']); //OK //Excel
    });

    //Candidatures
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/applications', [ApplicationController::class, 'index']); //OK //Excel
        Route::post('/applications', [ApplicationController::class, 'store']); //OK //Excel
        Route::post('/applications/{application}/accept', [ApplicationController::class, 'acceptApplication']); //OK //Excel
        Route::post('/applications/{application}/refuse', [ApplicationController::class, 'refuseApplication']); //OK Excel
        Route::get('/applications/{application}', [ApplicationController::class, 'show']); //OK //Excel
        Route::delete('/applications/{application}', [ApplicationController::class, 'destroy']); //OK //Excel
    });

    //Administrateurs
    Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
        Route::get('/admin/pending-job-offers', [AdminController::class, 'pendingJobOffers']);//OK
        Route::get('/admin/pending-developers', [AdminController::class, 'pendingDevelopers']);//OK
        Route::get('/admin/companies', [AdminController::class, 'handleCompanies']);//OK
        Route::get('/admin/developers', [AdminController::class, 'handleDevelopers']);//OK
        Route::post('/admin/filter-users', [AdminController::class, 'filterByEmail']); //OK
    });
});
