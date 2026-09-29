<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Actividad - TaskFlow Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 p-8 flex justify-center">
    <div class="w-full max-w-lg bg-slate-800 p-6 rounded-xl border border-slate-700 shadow-xl">
        <h2 class="text-xl font-bold text-indigo-400 mb-4">Crear Nueva Actividad</h2>

        <form action="{{ route('actividades.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-1">Nombre de la Actividad:</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}"
                       class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-indigo-500">
                @error('nombre')
                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Descripción:</label>
                <textarea name="descripcion" rows="3"
                          class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-indigo-500">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Estado Initial:</label>
                <select name="estado" class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-indigo-500">
                    <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_proceso" {{ old('estado') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                    <option value="completada" {{ old('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                </select>
                @error('estado')
                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Fecha Inicio:</label>
                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}"
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-indigo-500">
                    @error('fecha_inicio')
                        <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Fecha Fin (Opcional):</label>
                    <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}"
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-indigo-500">
                    @error('fecha_fin')
                        <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex justify-between items-center pt-2">
                <a href="{{ route('actividades.index') }}" class="text-sm text-slate-400 hover:underline">← Cancelar</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg font-bold text-white transition">
                    Guardar Actividad
                </button>
            </div>
        </form>
    </div>
</body>
</html>