<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//test route
Route::get('/test', function (Request $request) {
    return response()->json(['message' => 'Hello World!']);
});

Route::apiResource('contacts', ContactController::class);
Route::get('contacts', [ContactController::class, 'index']);
