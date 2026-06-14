<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoboothController;

Route::get('/', [PhotoboothController::class, 'index']);
Route::post('/themes/save', [PhotoboothController::class, 'saveTheme']);
Route::post('/themes/delete/{id}', [PhotoboothController::class, 'deleteTheme']);
