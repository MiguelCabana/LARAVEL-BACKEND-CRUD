<?php

use App\Http\Controllers\Api\EmpleadoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/


Route::get('/empleados',[EmpleadoController::class, 'index']);

Route::post('/empleados',[EmpleadoController::class, 'store']);

Route::get('/empleados/{id}',[EmpleadoController::class, 'show']);

Route::put('/empleados/{id}',[EmpleadoController::class, 'update']);

Route::patch('/empleados/{id}',[EmpleadoController::class, 'updatePartial']);

Route::delete('/empleados/{id}',[EmpleadoController::class, 'destroy']);