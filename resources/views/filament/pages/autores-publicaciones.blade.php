<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6">
        @foreach($autores as $autorId => $publicaciones)
        <div class="relative shadow-lg p-6 hover:shadow-xl transition-shadow duration-300"
            style="border-radius: 20px; background-color: rgba(0, 100, 0, 0.9);">

            {{-- Badge Estado --}}
            @php
                $autor = $publicaciones->first();
                $nombreEstado = $autor->Estado ?? 'Desconocido';
            @endphp

            <span
                @class(['absolute' , 'top-4' , 'right-20' , 'px-3' , 'py-1' , 'text-xs' , 'font-semibold' , 'text-white' , 'rounded-full' , 
                'bg-green-500'=> $nombreEstado === 'Activo',
                'bg-orange-500' => $nombreEstado === 'Suspendido',
                'bg-red-500' => $nombreEstado == 'Baja',
                'bg-gray-500' => ! in_array($nombreEstado, ['Activo', 'Suspendido', 'Baja']),
                ])
                >
                {{ $nombreEstado }}
            </span>


            {{-- Foto y nombre --}}
            <div class="flex items-center space-x-4 mb-6">
                <img src="{{ asset('storage/' . $publicaciones->first()->imagen) }}"
                    alt="{{ $publicaciones->first()->nombres }}"
                    class="object-cover"
                    style="width: 100px; height: 100px; margin: 10px; border-radius: 50%;">
                <h2 class="text-xl font-semibold text-white">{{ $publicaciones->first()->nombres }}</h2>
            </div>

            {{-- Tabla de categorías con totales --}}
            <div class="overflow-x-auto">
                <table class="min-w-full bg-gray-700 rounded-xl">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-300 text-sm uppercase">Categoría</th>
                            <th class="px-4 py-2 text-right text-gray-300 text-sm uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($publicaciones as $pub)
                        <tr class="border-t border-gray-600">
                            <td class="px-4 py-2 text-gray-200 text-sm">{{ $pub->categoria }}</td>
                            <td class="px-4 py-2 text-gray-200 text-sm text-right">{{ $pub->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        @endforeach
    </div>
</x-filament::page>