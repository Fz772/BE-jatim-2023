<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\ValidationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'Login']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'Logout']);
        Route::post('/validations', [ValidationController::class, 'store']);
        Route::get('/validations', [ValidationController::class, 'index']);
        Route::get('/job_vacancies', [JobVacancyController::class, 'index']);
        Route::get('/job_vacancies/{id}', [JobVacancyController::class, 'show']);
        Route::post('/applications', [ApplicationController::class, 'store']);
        Route::get('/applications', [ApplicationController::class, 'index']);

    });
    
});
