<?php

use App\Http\Controllers\API\CvAnalysisController;
use App\Http\Controllers\API\GetCourseController;
use App\Http\Controllers\home\PaymentController;
use App\Http\Controllers\tracking\ActivityController;
use App\Http\Controllers\tracking\UserStudyInsightController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/payments/sepay/webhook', [PaymentController::class, 'webhook'])
    ->name('payments.sepay.webhook');

Route::post('/cv-analysis/search-courses', [CvAnalysisController::class, 'searchCourses'])
    ->name('cv-analysis.search-courses');
Route::post('/log-activity', [ActivityController::class, 'log']);
// Route::prefix('tracking')->group(function () {
//     Route::get('/user/{id}', [UserStudyInsightController::class, 'show']);
//     Route::get('/user/{id}/update', [UserStudyInsightController::class, 'updateUserInsight']);
// });
Route::get('/update-insights', [UserStudyInsightController::class, 'updateInsights']);

Route::get('/get-course', [GetCourseController::class, 'index']);
    
