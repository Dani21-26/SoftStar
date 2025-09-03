<div>
    <!-- Encabezado y botón de añadir -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <div>
                <flux:heading size="xl" level="1">{{ __('Lista de Proveedores') }}</flux:heading>
                <flux:separator variant="subtle" />
            </div>
            <flux:modal.trigger name="crear-proveedor">
                <flux:button>
                    Añadir Proveedor
                </flux:button>
            </flux:modal.trigger>
        </div>
        <livewire:proveedores.create />
        <livewire:proveedores.edit />
    </div>

    <!-- Panel de búsqueda y filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6  dark:text-white tracking-tight" >
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <!-- Búsqueda -->
                <div class="w-full md:w-1/3">
                    <label for="search" class="sr-only">Buscar proveedor</label>
                    <div class="relative">
                        <input id="search" type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Buscar por nombre o contacto..."
                            class="block w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:border-gray-600 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Filtros -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <!-- Items por página -->
                    <div class="flex items-center gap-2">
                        <label for="perPage" class="text-sm dark:text-gray-300">Mostrar:</label>
                        <select id="perPage" wire:model.live="perPage"
                            class="text-sm border rounded-lg dark:bg-gray-700 px-2 py-1 focus:ring-blue-500 focus:border-blue-500">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                        </select>
                    </div>

                    <!-- Filtro por estado -->
                    <div class="flex items-center gap-2">
                        <label for="estado" class="text-sm dark:text-gray-300">Estado:</label>
                        <select id="estado" wire:model.live="estado"
                            class="text-sm border rounded-lg dark:bg-gray-700 px-2 py-1 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos</option>
                            <option value="activo">Activos</option>
                            <option value="inactivo">Inactivos</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de proveedores -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700  dark:text-white tracking-tight">
                <thead class="bg-blue-500 dark:bg-blue-700">
                    <tr>

                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            <button wire:click="sortBy('nombre_empresa')" class="flex items-center">
                                Empresa
                            </button>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            <button wire:click="sortBy('contacto_nombre')" class="flex items-center">
                                Contacto
                            </button>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Teléfono
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Correo
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            <button wire:click="sortBy('estado')" class="flex items-center">
                                Estado
                            </button>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($proveedores as $proveedor)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $proveedor->nombre_empresa }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $proveedor->contacto_nombre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $proveedor->telefono }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500 dark:text-gray-300 break-all">
                                    <a href="mailto:{{ $proveedor->correo }}"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                        {{ $proveedor->correo }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $proveedor->estado == 'activo'
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ ucfirst($proveedor->estado) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button
                                        wire:click="$dispatch('abrirModalEdicion', {id: {{ $proveedor->id_proveedor }}})"
                                        class="text-blue-600 hover:text-blue-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>
                                    @if ($proveedor->estado == 'activo')
                                        <button wire:click="toggleStatus({{ $proveedor->id_proveedor }})"
                                            class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400"
                                            title="Desactivar">
                                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z"
                                                    clip-rule="evenodd" />
                                            </svg>

                                        </button>
                                    @endif


                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No se encontraron proveedores que coincidan con los criterios de búsqueda
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if ($proveedores->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                {{ $proveedores->links() }}
            </div>
        @endif
    </div>
</div>
