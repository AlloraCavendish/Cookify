<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RecommendationController;

Route::post('/recommendations', [RecommendationController::class, 'getRecommendations']);