<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json(['status' => 'ok']));

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', fn () => request()->user());
    // TODO: add resource routes for modules (customers, subscriptions, etc.)
});
