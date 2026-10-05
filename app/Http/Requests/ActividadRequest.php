<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActividadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'       => ['required', 'string', 'min:5', 'max:150'],
            'descripcion'  => ['nullable', 'string', 'max:1000'],
            'estado'       => ['required', 'in:pendiente,proceso,completada'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin'    => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'       => 'El nombre de la actividad es obligatorio.',
            'nombre.min'            => 'El nombre debe tener al menos 5 caracteres.',
            'estado.in'             => 'El estado seleccionado no es válido.',
            'fecha_inicio.required' => 'Debes indicar una fecha de inicio.',
            'fecha_fin.after_or_equal' => 'La fecha final no puede ser anterior a la de inicio.',
        ];
    }
}