<?php

use App\Http\Controllers\Api\AssetScanController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/scan-asset', AssetScanController::class);
