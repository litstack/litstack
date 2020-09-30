<?php

use Illuminate\Support\Facades\Route;
use Lit\Http\Controllers\WelcomeController;

Route::get('/', WelcomeController::class);
