<?php

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

//Authentification
Route::middleware('auth:sanctum')->group(function () {

    //Autorisation => Entreprises et Développeurs
    Route::middleware(['isCompany', 'isDeveloper'])->group(function () {
        Route::get('/developers/profile', [DeveloperController::class, 'profile']);
    });

    //Autorisation => Développeurs
    Route::middleware('isDeveloper')->group(function () {
        Route::get('/applications/check', [ApplicationController::class, 'checkExistingApplication']);

        //Développeurs
        Route::put('/developers/{developer}', [DeveloperController::class, 'update']);
        Route::delete('/developers/{developer}', [DeveloperController::class, 'destroy']);
        
        //Candidatures
        Route::apiResource('applications', ApplicationController::class)->except(['update']);
    });

    //Autorisation => Entreprises
    Route::middleware('isCompany')->group(function () {
        //Entreprises
        Route::apiResource('companies', CompanyController::class)->except(['index', 'show', 'store']); //UPDATE ET DELETE
        Route::post('/applications/{application}/accept', [ApplicationController::class, 'acceptApplication']);
        Route::post('/applications/{application}/refuse', [ApplicationController::class, 'refuseApplication']);     
   
        //Offres d'emplois
        Route::get('/job-offers/{jobOffer}/applications', [JobOfferController::class, 'jobOfferApplications']);
        Route::get('/company/job-offers', [JobOfferController::class, 'getCompanyJobOffers']);
        Route::post('/job-offers', [JobOfferController::class, 'store']);
    });

    //Autorisation => Entreprises et Administrateurs
    Route::middleware(['isCompany', 'isAdmin'])->group(function () {
        //Offres d'emplois
        Route::delete('/job-offers/{jobOffer}', [JobOfferController::class, 'destroy']);
    });

   
    //Autorisation => Administrateurs uniquement
    Route::middleware('isAdmin')->group(function () {
        Route::get('/admin/pending-job-offers', [AdminController::class, 'pendingJobOffers']);
        Route::get('/admin/pending-developers', [AdminController::class, 'pendingDevelopers']);

        // Routes pour valider les offres d'emploi et les développeurs
        Route::post('/admin/job-offers/{jobOffer}/validate', [AdminController::class, 'validateJobOffer']);
        Route::post('/admin/developers/{developer}/validate', [AdminController::class, 'validateDeveloper']);

        Route::delete('/admin/developers/{developer}', [AdminController::class, 'deleteDeveloper']);
    });
});

Route::middleware('web')->group(function () {
    //Authentification
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');

    //Développeurs
    Route::post('/developers', [DeveloperController::class, 'store']);

    //Entreprises
    Route::post('/companies', [CompanyController::class, 'store']);
});

// Routes publiques 

//Route pour peupler le resourcesStore
Route::get('/programming-languages', [DeveloperController::class, 'getProgrammingLanguages']); 
Route::get('/types-contracts', [DeveloperController::class, 'getTypesContracts']);
Route::get('/types-developers', [DeveloperController::class, 'getTypesDevelopers']);
Route::get('/years-experiences', [DeveloperController::class, 'getYearsExperiences']);
Route::get('/locations', [DeveloperController::class, 'getLocations']);

//Développeurs
Route::get('/developers/filter', [DeveloperController::class, 'filterForm']);
Route::apiResource('developers', DeveloperController::class)->only(['index', 'show']);
Route::get('/developers/{developer}/cv', [DeveloperController::class, 'downloadCv']); //A modifier le code
Route::get('/developers/{developer}/cover-letter', [DeveloperController::class, 'downloadCoverLetter']);//A modifier le code 

//Offres d'emplois
Route::get('/job-offers/filter', [JobOfferController::class, 'filterForm']);
Route::apiResource('job-offers', JobOfferController::class)->only(['index', 'show']);

