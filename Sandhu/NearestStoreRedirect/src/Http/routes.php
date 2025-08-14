<?php

use Illuminate\Support\Facades\Route;
use Sandhu\NearestStoreRedirect\Http\Controllers\LocationController;

Route::get('/nearest-store', [LocationController::class, 'getNearestStore']);
