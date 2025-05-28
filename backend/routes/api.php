<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyUploadFileController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use App\Http\Resources\LoggedInUserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        // Get the authenticated User

        return LoggedInUserResource::make($request->user());
    });

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);

    Route::prefix('/users/{user}')->group(function () {
        Route::get('/', [UserController::class, 'show']);
        Route::put('/', [UserController::class, 'update']);
        Route::delete('/', [UserController::class, 'destroy']);
    });

    Route::get('/companies', [CompanyController::class, 'index']);
    Route::post('/companies', [CompanyController::class, 'store']);

    Route::get('/files/{file}', [FileController::class, 'show']);
    Route::delete('/files/{file}', [FileController::class, 'destroy']);

    Route::prefix('/companies/{company}')->group(function () {
        Route::get('/', [CompanyController::class, 'show']);
        Route::put('/', [CompanyController::class, 'update']);
        Route::delete('/', [CompanyController::class, 'destroy']);

        Route::post('/upload', CompanyUploadFileController::class);
    });
});
