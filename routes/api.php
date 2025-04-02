<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api_controller;


Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

Route::post('/todo/create', [api_controller::class, 'store']);
Route::put('/todo/edit/{id}', [api_controller::class, 'edit']);
Route::get('/todo/all', action: [api_controller::class, 'all']);
Route::delete('/todo/delete/{id}', [api_controller::class, 'delete']);

