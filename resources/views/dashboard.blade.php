<x-layouts.app :title="__('Dashboard')">
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tarjetas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Tarjeta Servicios esta semana -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 mr-4">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Servicios esta semana</p>
                            <h3 class="text-2xl font-bold">{{ $serviciosSemana }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Servicios Atrasados -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 dark:bg-red-900 mr-4">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Servicios atrasados</p>
                            <h3 class="text-2xl font-bold">{{ $serviciosAtrasados->count() }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">+5 días sin confirmar</p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Productos Utilizados -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 mr-4">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Productos utilizados</p>
                            <h3 class="text-2xl font-bold">{{ $productosUtilizados->count() }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Esta semana</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos y Tablas -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Gráfico de servicios por día -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 lg:col-span-2">
                    <h3 class="text-lg font-semibold mb-4">Servicios por día</h3>
                    <canvas id="serviciosChart" height="200"></canvas>
                </div>

                <!-- Tabla de productos utilizados -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Productos utilizados</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Producto</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Cantidad</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($productosUtilizados as $producto)
                                    <tr>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $producto['nombre'] }}
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $producto['total'] }} {{ $producto['unidad'] }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2"
                                            class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                                            No se utilizaron productos esta semana
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Lista de servicios atrasados -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Servicios pendientes (+5 días sin resolver)</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Código</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Cliente</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Fecha</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Días atraso</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($serviciosAtrasados as $servicio)
                                <tr
                                    class="@if ($servicio->estado == 'por_tomar') bg-red-50 dark:bg-red-900/20 @elseif($servicio->estado == 'en_proceso') bg-yellow-50 dark:bg-yellow-900/20 @endif">
                                    <td
                                        class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $servicio->codigo ?? '#' . $servicio->id_servicio }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $servicio->cliente }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $servicio->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $servicio->dias_atraso }} días
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
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
                                        <span
                                            class="px-2 py-1 rounded-full text-xs bg-{{ $estado['color'] }}-100 text-{{ $estado['color'] }}-800 dark:bg-{{ $estado['color'] }}-900/30 dark:text-{{ $estado['color'] }}-400">
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
            </div> @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Gráfico de servicios por día
                        new Chart(document.getElementById('serviciosChart'), {
                            type: 'bar',
                            data: {
                                labels: @json($diasSemana),
                                datasets: [{
                                    label: 'Servicios',
                                    data: @json($serviciosPorDia),
                                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                                    borderColor: 'rgba(59, 130, 246, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            stepSize: 1
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>
            @endpush
</x-layouts.app>
