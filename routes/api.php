<?php

use App\Http\Controllers\Api\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('company/show', [Company::class, 'show']);

Route::post('company/update', [Company::class, 'update']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
