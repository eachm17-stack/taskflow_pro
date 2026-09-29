<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>TaskFlow Pro - Actividades</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 p-8">
    <div class="max-w-5xl mx-auto">
        
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-500/20 border-l-4 border-emerald-500 text-emerald-300 rounded-r-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-white">TaskFlow Pro</h1>
                <p class="text-slate-400 text-sm">Gestión Integral de Actividades y Tareas</p>
            </div>
            <a href="{{ route('actividades.create') }}" class="bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg font-bold transition text-sm">
                + Nueva Actividad
            </a>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            @forelse ($actividades as $actividad)
                <div class="bg-slate-800 p-5 rounded-xl border border-slate-700 flex flex-col justify-between shadow-lg">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-white">{{ $actividad->nombre }}</h3>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full 
                                {{ $actividad->estado === 'completada' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : ($actividad->estado === 'en_proceso' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-slate-700 text-slate-300') }}">
                                {{ strtoupper($actividad->estado) }}
                            </span>
                        </div>
                        <p class="text-slate-400 text-sm mb-3">{{ $actividad->descripcion ?? 'Sin descripción.' }}</p>
                        <p class="text-xs text-slate-500">📅 Inicio: {{ $actividad->fecha_inicio }}</p>
                    </div>

                    <div class="mt-4 pt-4 border-t border-slate-700/60 flex justify-between items-center">
                        <span class="text-xs text-slate-500">ID: #{{ $actividad->id }}</span>
                        <div class="flex gap-2">
                            <a href="{{ route('actividades.edit', $actividad) }}" class="text-xs bg-amber-500/20 text-amber-300 border border-amber-500/40 px-2.5 py-1 rounded hover:bg-amber-500/30">
                                Editar
                            </a>

                            <form action="{{ route('actividades.destroy', $actividad) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta actividad?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs bg-red-500/20 text-red-300 border border-red-500/40 px-2.5 py-1 rounded hover:bg-red-500/30">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-10 bg-slate-800/40 rounded-xl border border-dashed border-slate-700">
                    <p class="text-slate-400">No hay actividades registradas en el sistema.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $actividades->links() }}
        </div>
    </div>
</body>
</html>