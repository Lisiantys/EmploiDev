<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\UserController;
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




//Développeurs
//ajouter le auth a cette route /profile. cette rtoute la rename peut etre la mettre dans auth elle fait dev + company
Route::get('/developers/profile', [DeveloperController::class, 'profile']); // FRONT
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/developers/{developer}', [DeveloperController::class, 'update']); // OK //Excel //FRONT
    Route::delete('/developers/{developer}', [DeveloperController::class, 'destroy']); // OK //Excel //FRONT
   // Route::get('/developers/applications/{developer}', [DeveloperController::class, 'developerApplications']); // OK //Excel
});

//Entreprises
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('companies', CompanyController::class)->except(['index', 'show', 'store']);//OK //EXCEL //FRONT UPDATE ET DELETE
});

 //Offres d'emplois
 Route::middleware('auth:sanctum')->group(function () {
    Route::get('/job-offers/{jobOffer}/applications', [JobOfferController::class, 'jobOfferApplications']);//OK FRONT
    Route::get('/company/job-offers', [JobOfferController::class, 'getCompanyJobOffers']); // ok FRONT
    Route::post('/job-offers', [JobOfferController::class, 'store']); //OK //EXCEL //FRONT
    Route::delete('/job-offers/{jobOffer}', [JobOfferController::class, 'destroy']); //OK //Excel //FRONT
});

  //Candidatures
  Route::middleware('auth:sanctum')->group(function () {
    Route::get('/applications', [ApplicationController::class, 'index']); //OK //Excel //FRONT
    Route::post('/applications', [ApplicationController::class, 'store']); //OK //Excel //FRONT
    Route::get('/applications/check', [ApplicationController::class, 'checkExistingApplication']); //FRONT
    Route::post('/applications/{application}/accept', [ApplicationController::class, 'acceptApplication']); //OK //Excel
    Route::post('/applications/{application}/refuse', [ApplicationController::class, 'refuseApplication']); //OK Excel
    Route::get('/applications/{application}', [ApplicationController::class, 'show']); //OK //Excel //FRONT
    Route::delete('/applications/{application}', [ApplicationController::class, 'destroy']); //OK //Excel //FRONT
});


Route::middleware('web')->group(function () {
    Route::post('/login', [AuthController::class, 'login']); //OK //Excel //FRONT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum'); //OK //Excel //FRONT

    //Développeurs
    Route::post('/developers', [DeveloperController::class, 'store']); //OK //Excel //FRONT
  
    //Entreprises
    Route::post('/companies', [CompanyController::class, 'store']); //OK  //Excel //FRONT
   
    //Administrateurs
    Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
        Route::get('/admin/pending-job-offers', [AdminController::class, 'pendingJobOffers']);//OK
        Route::get('/admin/pending-developers', [AdminController::class, 'pendingDevelopers']);//OK
        Route::get('/admin/companies', [AdminController::class, 'handleCompanies']);//OK
        Route::get('/admin/developers', [AdminController::class, 'handleDevelopers']);//OK
        Route::post('/admin/filter-users', [AdminController::class, 'filterByEmail']); //OK
    });
});



// Routes publiques (ne nécessitent pas d'authentification)

//Route pour peuplé le formulaire de filtrage
Route::get('/programming-languages', [DeveloperController::class, 'getProgrammingLanguages']);  //OK //FRONT
Route::get('/types-contracts', [DeveloperController::class, 'getTypesContracts']);//OK  //FRONT
Route::get('/types-developers', [DeveloperController::class, 'getTypesDevelopers']); //OK //FRONT
Route::get('/years-experiences', [DeveloperController::class, 'getYearsExperiences']);//OK  //FRONT
Route::get('/locations', [DeveloperController::class, 'getLocations']);//OK  //FRONT

//Développeurs
Route::get('/developers/filter', [DeveloperController::class, 'filterForm']); //OK //Excel //FRONT
Route::apiResource('developers', DeveloperController::class)->only(['index', 'show']); //OK //Excel //FRONT

//Offres d'emplois
Route::get('/job-offers/filter', [JobOfferController::class, 'filterForm']); //OK //Excel //FRONT
Route::apiResource('job-offers', JobOfferController::class)->only(['index', 'show']); //OK //Excel //FRONT

//Entreprises
Route::get('/companies/{company}', [CompanyController::class, 'show']);//OK //EXcel //NE PAS LA FAIRE, profil inutile

Route::get('/developers/{developer}/cv', [DeveloperController::class, 'downloadCv']);//A modifier le code
Route::get('/developers/{developer}/cover-letter', [DeveloperController::class, 'downloadCoverLetter']);//A modifier le code
