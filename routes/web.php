<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActividadController;


Route::get('/', function () {
    return redirect()->route('actividades.index');
});

Route::resource('actividades', ActividadController::class);