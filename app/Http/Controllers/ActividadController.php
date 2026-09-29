<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Http\Requests\ActividadRequest;

class ActividadController extends Controller
{
    public function index()
    {
        $actividades = Actividad::latest()->paginate(6);
        return view('actividades.index', compact('actividades'));
    }

    public function create()
    {
        return view('actividades.create');
    }

    public function store(ActividadRequest $request)
    {
        Actividad::create($request->validated());

        return redirect()
            ->route('actividades.index')
            ->with('success', '¡Actividad creada exitosamente!');
    }

    public function edit(Actividad $actividade)
    {
        return view('actividades.edit', ['actividad' => $actividade]);
    }

    public function update(ActividadRequest $request, Actividad $actividade)
    {
        $actividade->update($request->validated());

        return redirect()
            ->route('actividades.index')
            ->with('success', 'Actividad actualizada con éxito.');
    }

    public function destroy(Actividad $actividade)
    {
        $actividade->delete();

        return redirect()
            ->route('actividades.index')
            ->with('success', 'Actividad eliminada del sistema.');
    }
}