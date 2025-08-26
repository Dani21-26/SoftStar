<x-layouts.app :title="__('Dashboard')">
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Tarjetas resumen -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Tarjeta Servicios esta semana -->
                <div class="bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700 rounded-xl p-6 transition hover:shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 mr-4">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 font-medium">Servicios esta semana</p>
                            <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $serviciosSemana }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Servicios atrasados -->
                <div class="bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700 rounded-xl p-6 transition hover:shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 dark:bg-red-900 mr-4">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 font-medium">Servicios atrasados</p>
                            <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $serviciosAtrasados->count() }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">+5 días sin confirmar</p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Productos utilizados -->
                <div class="bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700 rounded-xl p-6 transition hover:shadow-lg">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 mr-4">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 font-medium">Productos utilizados</p>
                            <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $productosUtilizados->count() }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Esta semana</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estado de Stock -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-md rounded-xl p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full 
                        @if ($stockEstado === 'bajo') bg-red-100 dark:bg-red-900 
                        @else bg-green-100 dark:bg-green-900 @endif mr-4">
                        <svg class="w-6 h-6 
                            @if ($stockEstado === 'bajo') text-red-600 dark:text-red-300 
                            @else text-green-600 dark:text-green-300 @endif"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 font-medium">Estado de stock</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white">
                            @if ($stockEstado === 'bajo')
                                ⚠️ Bajo
                            @else
                                ✅ Estable
                            @endif
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $mensajeStock }}
                        </p>
                    </div>
                </div>

                @if($stockEstado === 'bajo')
                    <div class="mt-4">
                        <h4 class="text-sm font-semibold text-red-600 dark:text-red-400 mb-2">Productos en riesgo:</h4>
                        <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300">
                            @foreach($productosBajoStock as $p)
                                <li>{{ $p->nombre }} ({{ $p->stock }} unidades)</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Tabla productos -->
            <div class="bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Productos utilizados</h3>
                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase">Producto</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($productosUtilizados as $producto)
                                <tr class="odd:bg-gray-50 even:bg-white dark:odd:bg-gray-800 dark:even:bg-gray-900 hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $producto['nombre'] }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $producto['total'] }} {{ $producto['unidad'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                                        No se utilizaron productos esta semana
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Servicios atrasados -->
            <div class="bg-white dark:bg-gray-800 shadow-md border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Servicios pendientes (+5 días sin resolver)</h3>
                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase">Código</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase">Cliente</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase">Fecha</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase">Días atraso</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($serviciosAtrasados as $servicio)
                                <tr class="odd:bg-gray-50 even:bg-white dark:odd:bg-gray-800 dark:even:bg-gray-900 hover:bg-red-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $servicio->codigo ?? '#' . $servicio->id }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                        {{ $servicio->cliente }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $servicio->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $servicio->dias_atraso }} días
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @php
                                            $estados = [
                                                'por_tomar' => ['color' => 'red', 'texto' => 'Por tomar'],
                                                'en_proceso' => ['color' => 'yellow', 'texto' => 'En proceso'],
                                            ];
                                            $estado = $estados[$servicio->estado] ?? [
                                                'color' => 'blue',
                                                'texto' => $servicio->estado,
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs bg-{{ $estado['color'] }}-100 text-{{ $estado['color'] }}-800 dark:bg-{{ $estado['color'] }}-900/30 dark:text-{{ $estado['color'] }}-400">
                                            {{ $estado['texto'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                                        ¡Excelente! No hay servicios pendientes atrasados
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
