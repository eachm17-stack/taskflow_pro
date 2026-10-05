<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Actividad - TaskFlow Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 p-8 flex justify-center">
    <div class="w-full max-w-lg bg-slate-800 p-6 rounded-xl border border-slate-700 shadow-xl">
        <h2 class="text-xl font-bold text-amber-400 mb-4">Editar Actividad</h2>

        <form action="{{ route('actividades.update', $actividad) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold mb-1">Nombre de la Actividad:</label>
                <input type="text" name="nombre" value="{{ old('nombre', $actividad->nombre) }}"
                       class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-indigo-500">
                @error('nombre')
                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Descripción:</label>
                <textarea name="descripcion" rows="3"
                          class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-indigo-500">{{ old('descripcion', $actividad->descripcion) }}</textarea>
                @error('descripcion')
                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Estado:</label>
                <select name="estado" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-indigo-500">
                    <option value="pendiente" {{ old('estado', $actividad->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="proceso" {{ old('estado', $actividad->estado) == 'proceso' ? 'selected' : '' }}>Proceso</option>
                    <option value="completada" {{ old('estado', $actividad->estado) == 'completada' ? 'selected' : '' }}>Completada</option>
                </select>
                @error('estado')
                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Fecha Inicio:</label>
                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $actividad->fecha_inicio) }}"
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-indigo-500">
                    @error('fecha_inicio')
                        <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Fecha Fin (Opcional):</label>
                    <input type="date" name="fecha_fin" value="{{ old('fecha_fin', $actividad->fecha_fin) }}"
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-indigo-500">
                    @error('fecha_fin')
                        <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex justify-between items-center pt-2">
                <a href="{{ route('actividades.index') }}" class="text-sm text-slate-400 hover:underline">← Cancelar</a>
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 px-4 py-2 rounded-lg font-bold text-white transition">
                    Actualizar Actividad
                </button>
            </div>
        </form>
    </div>
</body>
</html>