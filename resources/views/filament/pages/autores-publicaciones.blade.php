<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($autores as $autorId => $publicaciones)
        @php
            $autor = $publicaciones->first();
            $nombreEstado = $autor->Estado ?? 'Desconocido';
            $estadoColor = match($nombreEstado) {
                'Activo' => 'bg-green-500',
                'Suspendido' => 'bg-orange-500',
                'Baja' => 'bg-red-500',
                default => 'bg-gray-500',
            };
            $estadoIcon = match($nombreEstado) {
                'Activo' => '✔️',
                'Suspendido' => '⏸️',
                'Baja' => '❌',
                default => '❓',
            };
        @endphp
        <div class="relative group shadow-xl p-8 transition-all duration-300 rounded-3xl bg-white/70 dark:bg-gray-900/70 backdrop-blur-lg border border-gray-200 dark:border-gray-700 hover:scale-105 hover:shadow-2xl">
            {{-- Foto y nombre --}}
            <div class="flex flex-col items-center mb-8">
                <div class="relative">
                    {{-- Badge Estado inside avatar, top-left --}}
                    <span class="absolute -top-2 -left-4 flex items-center gap-1 px-2 py-0.5 text-xs font-bold text-white rounded-full {{ $estadoColor }} shadow transition-colors duration-300 z-10">
                        <span>{{ $estadoIcon }} {{ $nombreEstado }}</span>
                    </span>
                    <img src="{{ asset('storage/' . $autor->imagen) }}"
                        alt="{{ $autor->nombres }}"
                        class="object-cover w-28 h-28 rounded-full border-4 border-white dark:border-gray-800 shadow-lg transition-transform duration-300 group-hover:scale-105">
                </div>
                <h2 class="mt-4 text-2xl font-extrabold text-gray-900 dark:text-white text-center">{{ $autor->nombres }}</h2>
            </div>

            {{-- Tabla de categorías con totales --}}
            <div class="overflow-x-auto">
                <table class="min-w-full rounded-xl bg-gray-100/80 dark:bg-gray-800/80">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Categoría</th>
                            <th class="px-4 py-2 text-right text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($publicaciones as $pub)
                        <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-200/60 dark:hover:bg-gray-700/60 transition-colors">
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm">{{ $pub->categoria }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-gray-200 text-sm text-right font-semibold">{{ $pub->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-8 text-center text-gray-500 dark:text-gray-400 text-xs">
        <span class="inline-block px-3 py-1 bg-white/60 dark:bg-gray-900/60 rounded-full shadow">Soporta modo claro y oscuro</span>
    </div>
</x-filament::page>
