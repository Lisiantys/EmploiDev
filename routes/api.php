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


Route::post('login', [AuthController::class, 'login'])->name('login');

//Filtre de recherche offres d'emplois
Route::post('/job-offers/filter', [JobOfferController::class, 'filterForm']); // OK

//Filtre de recherche développeur
Route::post('/developers/filter', [DeveloperController::class, 'filterForm']);

Route::get('developers', [DeveloperController::class, 'index']);

// Grouper les routes sous le middleware Sanctum
Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    //Affichage des candidatures du développeur
    Route::get('/developers/{developer}/applications', [DeveloperController::class, 'developerApplications']);
    Route::apiResource('developers', DeveloperController::class)->except(['index']);

    //Affichage d'emplois de l'entreprise
    Route::get('companies/{company}/job_offers', [CompanyController::class, 'jobOffers']); //OK

    //Affichage des candidatures reçus sur une offre d'emploi
    Route::get('companies/{company}/job_offers/{jobOffer}/applications', [CompanyController::class, 'jobOfferApplications']); //OK
    Route::apiResource('companies', CompanyController::class); // OK

    Route::apiResource('job-offers', JobOfferController::class); //OK
    Route::apiResource('applications', ApplicationController::class); //OK


    //Routes ADMIN, définir un middleware...
    Route::get('/admin/pending-job-offers', [AdminController::class, 'pendingJobOffers']);
    Route::get('/admin/pending-developers', [AdminController::class, 'pendingDevelopers']);
    Route::put('/admin/job-offers/{id}/{action}', [AdminController::class, 'handleJobOffer']);
    Route::put('/admin/developers/{id}/{action}', [AdminController::class, 'handleDeveloper']);
});








