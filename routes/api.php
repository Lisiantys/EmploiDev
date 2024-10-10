<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\JobOfferController;
use App\Http\Controllers\Api\DeveloperController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\ResourceController;

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
    Route::middleware('isCompanyOrDeveloper')->group(function () {
        Route::get('/developers/profile', [DeveloperController::class, 'profile']); //OK
    });

    //Autorisation => Développeurs
    Route::middleware('isDeveloper')->group(function () {
        Route::get('/applications/check', [ApplicationController::class, 'checkExistingApplication']); //OK

        //Développeurs
        Route::put('/developers/{developer}', [DeveloperController::class, 'update']); //OK mais vour pour limage
        Route::delete('/developers/{developer}', [DeveloperController::class, 'destroy']); //OK
        
        //Candidatures
        Route::apiResource('applications', ApplicationController::class)->except(['update']); //OK, mais supprimer l'image du user , le cv et la lettre de motivation
    });

    //Autorisation => Entreprises
    Route::middleware('isCompany')->group(function () {
        //Entreprises
        Route::apiResource('companies', CompanyController::class)->except(['index', 'show', 'store']); //UPDATE ET DELETE //OK gerer l'update de l'image
        Route::post('/applications/{application}/accept', [ApplicationController::class, 'acceptApplication']); //OK
        Route::post('/applications/{application}/refuse', [ApplicationController::class, 'refuseApplication']); //OK
   
        //Offres d'emplois
        Route::get('/job-offers/{jobOffer}/applications', [JobOfferController::class, 'jobOfferApplications']); //OK
        Route::get('/company/job-offers', [JobOfferController::class, 'getCompanyJobOffers']); //OK
        Route::post('/job-offers', [JobOfferController::class, 'store']); //OK
    });

    //Autorisation => Entreprises et Administrateurs
    Route::middleware('isCompanyOrAdmin')->group(function () {
        //Offres d'emplois
        Route::delete('/job-offers/{jobOffer}', [JobOfferController::class, 'destroy']); //OK
    });

    //Autorisation => Administrateurs uniquement
    Route::middleware('isAdmin')->group(function () {
        Route::get('/admin/pending-job-offers', [AdminController::class, 'pendingJobOffers']); //OK
        Route::get('/admin/pending-developers', [AdminController::class, 'pendingDevelopers']); //OK

        // Routes pour valider les offres d'emploi et les développeurs
        Route::post('/admin/job-offers/{jobOffer}/validate', [AdminController::class, 'validateJobOffer']); //OK
        Route::post('/admin/developers/{developer}/validate', [AdminController::class, 'validateDeveloper']); //OK

        Route::delete('/admin/developers/{developer}', [AdminController::class, 'deleteDeveloper']); //OK
    });

    //Etre authentifié pour télécharger les cv et lettres
    Route::get('/developers/{developer}/cv', [DeveloperController::class, 'downloadCv']); //A modifier le code
    Route::get('/developers/{developer}/cover-letter', [DeveloperController::class, 'downloadCoverLetter']);//A modifier le code 

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

// Routes pour les ressources
Route::get('/programming-languages', [ResourceController::class, 'getProgrammingLanguages']);
Route::get('/types-contracts', [ResourceController::class, 'getTypesContracts']);
Route::get('/types-developers', [ResourceController::class, 'getTypesDevelopers']);
Route::get('/years-experiences', [ResourceController::class, 'getYearsExperiences']);
Route::get('/locations', [ResourceController::class, 'getLocations']);


//Développeurs
Route::get('/developers/filter', [DeveloperController::class, 'filterForm']); //OK
Route::apiResource('developers', DeveloperController::class)->only(['index', 'show']); //OK

//Offres d'emplois
Route::get('/job-offers/filter', [JobOfferController::class, 'filterForm']); //OK
Route::apiResource('job-offers', JobOfferController::class)->only(['index', 'show']); //OK

