<?php

use App\Http\Controllers\GoogleDriveControllerApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('google/uploadFile', [GoogleDriveControllerApi::class, 'uploadFile']);
Route::get('google/login', [GoogleDriveControllerApi::class, 'redirectToGoogle']);
Route::get('google/callback', [GoogleDriveControllerApi::class, 'handleCallback']);

