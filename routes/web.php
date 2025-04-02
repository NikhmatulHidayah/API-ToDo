<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web_controller;

Route::get('/todo', function () {
    return view('todo-all');
});

