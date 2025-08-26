<div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ...Tarjetas de resumen eliminadas... -->

            <!-- Filtros -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Filtro por fecha -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filtrar por fecha</label>
                        <div class="flex items-center space-x-2">
                            <input type="date" wire:model="fechaInicio" class="px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                            <span class="text-gray-600 dark:text-gray-300">a</span>
                            <input type="date" wire:model="fechaFin" class="px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                        </div>
                    </div>
                    <!-- Búsqueda por cliente -->
                    <div class="w-full md:w-64">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar cliente</label>
                        <input type="text" wire:model.live.debounce.300ms="search" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Buscar cliente...">
                    </div>
                </div>
            </div>

            <!-- Lista de Servicios Completados -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-blue-500 dark:bg-blue-700">
                            <tr>
                                
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Dirección</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Técnico</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Nota</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($detalles as $detalle)
                                <tr class="odd:bg-gray-50 even:bg-white dark:odd:bg-gray-800 dark:even:bg-gray-700 hover:bg-blue-50 dark:hover:bg-blue-900 transition-colors">
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $detalle->servicio->cliente ?? 'Cliente no disponible' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $detalle->servicio->direccion ?? 'Sin dirección' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $detalle->user->name ?? 'Técnico no asignado' }}
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
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        No hay servicios completados disponibles.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
