<div class="space-y-6" x-data="{ darkMode: window.matchMedia('(prefers-color-scheme: dark)').matches }" :class="{ 'dark': darkMode }">
    <!-- Formulario para nuevo servicio -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Registrar Servicio Técnico</h2>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6 p-4 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-lg">
                ℹ️ {{ session('info') }}
            </div>
        @endif

        <form wire:submit.prevent="crearServicio" class="space-y-6">
            <!-- Sección de información básica -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cliente -->
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Cliente <span class="text-red-600">*</span>
                    </label>
                    <input type="text" wire:model="cliente"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                  @error('cliente') border-red-600 bg-red-50 dark:bg-red-900/20 @enderror"
                        placeholder="Nombre del cliente">
                    @error('cliente')
                        <span class="block mt-2 text-sm font-medium text-red-700 dark:text-red-400 bg-red-100 dark:bg-red-900/30 px-3 py-1.5 rounded-lg">
                            ⚠️ {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Dirección -->
                <div>
                    <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                        Dirección <span class="text-red-600">*</span>
                    </label>
                    <input type="text" wire:model="direccion"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                  @error('direccion') border-red-600 bg-red-50 dark:bg-red-900/20 @enderror"
                        placeholder="Dirección completa">
                    @error('direccion')
                        <span class="block mt-2 text-sm font-medium text-red-700 dark:text-red-400 bg-red-100 dark:bg-red-900/30 px-3 py-1.5 rounded-lg">
                            ⚠️ {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Falla reportada -->
            <div>
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    Falla Reportada <span class="text-red-600">*</span>
                </label>
                <textarea wire:model="fallaReportada" rows="4"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                 dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                 @error('fallaReportada') border-red-600 bg-red-50 dark:bg-red-900/20 @enderror"
                    placeholder="Describa la falla reportada por el cliente"></textarea>
                @error('fallaReportada')
                    <span class="block mt-2 text-sm font-medium text-red-700 dark:text-red-400 bg-red-100 dark:bg-red-900/30 px-3 py-1.5 rounded-lg">
                        ⚠️ {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Observaciones -->
            <div>
                <label class="block mb-2 font-medium text-gray-700 dark:text-gray-300">
                    Observaciones
                </label>
                <textarea wire:model="observaciones" rows="2"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    placeholder="Observaciones adicionales"></textarea>
            </div>

            <!-- Botón de enviar -->
            <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4 border-t pt-6 border-gray-200 dark:border-gray-700">
                <button type="submit"
                    class="px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 
                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                            transition-colors duration-200 dark:focus:ring-offset-gray-800 w-full sm:w-auto">
                    Registrar Solicitud de Servicio
                </button>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    <span class="text-red-600 font-medium">*</span> Campos obligatorios
                </span>
            </div>
        </form>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Servicios Técnicos</h2>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Filtro por estado -->
                <div>
                    <select wire:model.live="estadoFiltro" 
                        class="block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="por_tomar">Por tomar</option>
                        <option value="en_proceso">En proceso</option>
                        <option value="completado">Completado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                
                <!-- Búsqueda -->
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" 
                        class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Buscar...">
                    <div class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lista de servicios -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Falla</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($servicios as $servicio)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $servicio->codigo }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $servicio->cliente }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                {{ $servicio->falla_reportada }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span @class([
                                    'px-2 py-1 text-xs font-semibold rounded-full',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' => $servicio->estado === 'por_tomar',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' => $servicio->estado === 'en_proceso',
                                    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' => $servicio->estado === 'completado',
                                    'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' => $servicio->estado === 'cancelado',
                                ])>
                                    {{ ucfirst(str_replace('_', ' ', $servicio->estado)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $servicio->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="verDetalle({{ $servicio->id_servicio }})"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        Detalle
                                    </button>
                                    @if($servicio->estado === 'por_tomar')
                                        <button wire:click="cancelarServicio({{ $servicio->id_servicio }})"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 ml-2">
                                            Cancelar
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No se encontraron servicios
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $servicios->links() }}
        </div>
    </div>
</div>