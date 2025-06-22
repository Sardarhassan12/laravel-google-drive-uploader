<?php

use App\Http\Controllers\GoogleDriveController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('google/login', [GoogleDriveController::class, 'redirectToGoogle'])->name('google.login');
Route::get('google/callback', [GoogleDriveController::class, 'handleCallBack']);
Route::get('google/upload-form', [GoogleDriveController::class, 'displayUploadForm']);
Route::post('google/upload', [GoogleDriveController::class, 'uploadFilee']);

