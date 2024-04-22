<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/developers/{developer}/applications', [DeveloperController::class, 'developerApplications']);
Route::post('/developers/filter', [DeveloperController::class, 'filterForm']); // OK
Route::apiResource('developers', DeveloperController::class); // OK

Route::apiResource('companies', CompanyController::class); // OK sauf create

Route::post('/job-offers/filter', [JobOfferController::class, 'filterForm']);
Route::apiResource('job-offers', JobOfferController::class);



Route::apiResource('applications', ApplicationController::class);


Route::get('/admin/pending-job-offers', [AdminController::class, 'pendingJobOffers']);
Route::get('/admin/pending-developers', [AdminController::class, 'pendingDevelopers']);
Route::put('/admin/job-offers/{id}/{action}', [AdminController::class, 'handleJobOffer']);
Route::put('/admin/developers/{id}/{action}', [AdminController::class, 'handleDeveloper']);
Route::get('companies/{company}/job-offers', [CompanyController::class, 'jobOffers']);
Route::get('companies/{company}/job-offers/{jobOffer}/applications', [CompanyController::class, 'jobOfferApplications']);

