<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

Route::post('/login', function (Request $request) {
    $credentials =$request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (auth()->attempt($credentials)) {
        return response()->json([
            'status' => 'success',
            'user'   => auth()->user(),
            'token'  => bin2hex(random_bytes(24)),
        ], 200);
    }

    return response()->json(['error' => 'Credenciales inválidas'], 401);
});

Route::get('/projects', [ProjectController::class, 'index']);
Route::post('/projects', [ProjectController::class, 'store']);
Route::get('/projects/{project}', [ProjectController::class, 'show']);
Route::put('/projects/{project}', [ProjectController::class, 'update']);
Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);