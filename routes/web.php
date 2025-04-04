<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web_controller;

Route::get('/', function () {
    return view('todo-all');
});

