<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MedicationController;
use App\Http\Controllers\Api\ReminderScheduleController;
use Illuminate\Support\Facades\Route;

// Public Routes (No JWT authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (Require JWT authentication)
Route::middleware('auth:api')->group(function () {
    // Auth Routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);

    // Medication Routes
    Route::apiResource('/medications', MedicationController::class)->except(['store']);
    Route::post('/medications', [MedicationController::class, 'store']);

    // ReminderSchedule Routes
    Route::post('/medications/{medication}/reminders', [ReminderScheduleController::class, 'store']);
    Route::get('/medications/{medication}/reminders', [ReminderScheduleController::class, 'index']);
    Route::get('/medications/{medication}/reminders/{reminder_schedule}', [ReminderScheduleController::class, 'show']);
});