<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use App\Models\User;

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    event(new Registered($user));
    auth()->login($user);

    $token = bin2hex(random_bytes(24));

    return response()->json([
        'status' => 'success',
        'message' => 'Usuario registrado exitosamente',
        'user' => $user,
        'token' => $token,
    ], 201);
});

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
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

Route::post('/logout', function (Request $request) {
    auth()->logout();
    return response()->json([
        'status' => 'success',
        'message' => 'Sesión cerrada exitosamente',
    ], 200);
});

Route::get('/projects', function () {
    return response()->json([
        ['id' => 1, 'title' => 'Proyecto TaskSaas Principal', 'status' => 'Activo'],
        ['id' => 2, 'title' => 'Módulo de Gestión de Tareas', 'status' => 'Activo'],
    ]);
});