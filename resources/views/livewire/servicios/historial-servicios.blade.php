<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tarjetas de Resumen -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Tarjeta Servicios Totales Completados -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 mr-4">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Total Completados</p>
                            <h3 class="text-2xl font-bold">{{ $totalCompletados }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Servicios Este Mes -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 mr-4">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Este Mes</p>
                            <h3 class="text-2xl font-bold">{{ $completadosEsteMes }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Productos Utilizados -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900 mr-4">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Productos Usados</p>
                            <h3 class="text-2xl font-bold">{{ $totalProductosUtilizados }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Filtro por fecha -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filtrar por fecha</label>
                        <div class="flex items-center space-x-2">
                            <input type="date" wire:model="fechaInicio"
                                   class="px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                            <span class="text-gray-600 dark:text-gray-300">a</span>
                            <input type="date" wire:model="fechaFin"
                                   class="px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                        </div>
                    </div>

                    <!-- Búsqueda -->
                    <div class="w-full md:w-64">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar servicios</label>
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                   class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                   placeholder="Buscar cliente...">
                            <div class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Servicios Completados -->
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-blue-500 dark:bg-blue-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Código</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Técnico</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Productos Utilizados</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Problema Real</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Fecha</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($detalles as $detalle)
                <tr class="odd:bg-gray-50 even:bg-white dark:odd:bg-gray-800 dark:even:bg-gray-700 hover:bg-blue-50 dark:hover:bg-blue-900 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                        {{ $detalle->servicio->codigo ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        {{ $detalle->servicio->cliente ?? 'Cliente no disponible' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        {{ $detalle->user->name ?? 'Técnico no asignado' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                        @if(!empty($detalle->productos_utilizados))
                            <ul class="list-disc pl-5">
                                @foreach($detalle->productos_utilizados as $productoId => $cantidad)
                                    @php
                                        $producto = App\Models\Producto::find($productoId);
                                    @endphp
                                    <li>{{ $producto->nombre ?? 'Producto eliminado' }} (x{{ $cantidad }})</li>
                                @endforeach
                            </ul>
                        @else
                            Ningún producto utilizado
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300 max-w-xs">
                        {{ $detalle->nota }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        {{ $detalle->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        No hay servicios completados disponibles.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
