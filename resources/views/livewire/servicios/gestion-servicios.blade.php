<div class="space-y-6" x-data="{
    darkMode: window.matchMedia('(prefers-color-scheme: dark)').matches,
    openModal: false
}" :class="{ 'dark': darkMode }">
    <!-- Botón para nuevo servicio -->
    @if ($mostrarFormulario && !$esTecnico)
        <div class="flex justify-end">
            <flux:button @click="openModal = true" variant="primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Nuevo Servicio Técnico
            </flux:button>
        </div>
    @endif

    <!-- Modal para crear servicio -->
    <div x-show="openModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <!-- Encabezado -->
                <div class="flex justify-between items-center pb-4 border-b dark:border-gray-700">
                    <flux:heading size="lg">Registrar Nuevo Servicio Técnico</flux:heading>
                    <button @click="openModal = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit.prevent="crearServicio" class="space-y-5 mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Cliente -->
                        <div>
                            <flux:input label="Cliente" placeholder="Nombre completo del cliente" wire:model="cliente"
                                required />
                            @error('cliente')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Router -->
                        <div>
                            <flux:input label="Router" placeholder="Modelo del router" wire:model="router" required />
                            @error('router')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Litebean -->
                        <div>
                            <flux:input label="Litebean" placeholder="Código Litebean" wire:model="litebean" required />
                            @error('litebean')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Dirección -->
                        <div class="md:col-span-2">
                            <flux:input label="Dirección" placeholder="Dirección completa" wire:model="direccion"
                                required />
                            @error('direccion')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Falla Reportada -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Falla Reportada <span class="text-red-600">*</span>
                            </label>
                            <textarea wire:model="fallaReportada" rows="3"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Describa la falla reportada por el cliente" required></textarea>
                            @error('fallaReportada')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-4 pt-4 border-t dark:border-gray-700">
                        <flux:button type="button" variant="outline" @click="openModal = false" class="flex-1">
                            Cancelar
                        </flux:button>
                        <flux:button type="submit" variant="primary" class="flex-1" wire:loading.attr="disabled">
                            <span wire:loading.remove>Registrar Servicio</span>
                            <span wire:loading wire:target="crearServicio">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Registrando...
                            </span>
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Panel de servicios -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <!-- Encabezado con título y controles -->
        <div
            class="px-6 py-5 border-b dark:border-gray-700 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <flux:heading size="lg">
                    {{ $esTecnico ? 'Servicios Disponibles' : 'Gestión de Servicios Técnicos' }}</flux:heading>
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    {{ $esTecnico ? 'Servicios técnicos disponibles para asignación' : 'Lista completa de servicios técnicos' }}
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Filtro por estado (visible para todos) -->
                <div class="w-full sm:w-48">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filtrar por
                        estado</label>
                    <select wire:model.live="estadoFiltro"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                              dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Todos</option>
                        <option value="pendiente">Pendientes</option>
                        <option value="completado">Completados</option>
                        <option value="cancelado">Cancelados</option>
                    </select>
                </div>

                <!-- Búsqueda -->
                <div class="w-full sm:w-64">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar
                        servicios</label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search"
                            class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Buscar...">
                        <div class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de servicios -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Código</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Cliente</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Router</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Litebean</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Falla</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Estado</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Fecha</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($servicios as $servicio)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $servicio->codigo }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $servicio->cliente }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $servicio->router }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $servicio->litebean }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                    {{ $servicio->falla_reportada }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span @class([
                                    'px-2 py-1 text-xs font-semibold rounded-full',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' =>
                                        $servicio->estado === 'por_tomar',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' =>
                                        $servicio->estado === 'en_proceso',
                                    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' =>
                                        $servicio->estado === 'confirmado',
                                    'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' =>
                                        $servicio->estado === 'cancelado',
                                ])>
                                    {{ ucfirst(str_replace('_', ' ', $servicio->estado)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $servicio->created_at->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ $servicio->created_at->format('H:i') }}
                                </div>
                            </td>


                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <!-- Mostrar siempre Cancelar y Confirmar para servicios pendientes -->
                                    @if ($servicio->estado === 'pendiente')
                                        <button wire:click="abrirModalConfirmar({{ $servicio->id_servicio }})"
                                            class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 flex items-center ml-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Confirmar</span>
                                        </button>
                                        <button wire:click="cancelarServicio({{ $servicio->id_servicio }})"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 flex items-center ml-3"
                                            title="Cancelar servicio">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <span>Cancelar</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ $esTecnico ? 'No hay servicios disponibles' : 'No se encontraron servicios' }}
                                </h3>
                                <p class="mt-1 text-gray-500 dark:text-gray-400">
                                    {{ $esTecnico ? 'Todos los servicios han sido asignados' : 'Intenta ajustar los filtros de búsqueda' }}
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @include('livewire.servicios.modal-confirmar-servicio')
        </div>

        <!-- Paginación y resumen -->
        <div
            class="px-6 py-4 border-t dark:border-gray-700 flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400 mb-4 md:mb-0">
                Mostrando {{ $servicios->firstItem() ?? 0 }} - {{ $servicios->lastItem() ?? 0 }} de
                {{ $servicios->total() }} servicios
            </div>
            <div>
                {{ $servicios->links() }}
            </div>
        </div>
    </div>
</div>


