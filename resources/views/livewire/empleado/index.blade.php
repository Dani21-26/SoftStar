<div>
    <!-- Encabezado y botón de añadir -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <div>
                <flux:heading size="xl" level="1">{{ __('Lista de Empleados') }}</flux:heading>
                <flux:separator variant="subtle" />
            </div>
            <flux:modal.trigger name="crear-empleado">
                <flux:button>Añadir Empleado</flux:button>
            </flux:modal.trigger>
        </div>
        <livewire:empleado.create />
        <livewire:empleado.edit />
    </div>

    <!-- Panel de búsqueda y filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <!-- Búsqueda -->
                <div class="w-full md:w-1/3">
                    <label for="search" class="sr-only">Buscar empleado</label>
                    <div class="relative">
                        <input id="search" type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Buscar por nombre o cargo..."
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

                    <!-- Filtro por estado - Versión mejorada -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm dark:text-gray-300">Estado:</span>
                        <select wire:model.live="estado" wire:change="$dispatch('filtro-cambiado')"
                            class="text-sm border rounded-lg dark:bg-gray-700 px-2 py-1">
                            <option value="">Todos</option>
                            <option value="activo">Activos</option>
                            <option value="inactivo">Inactivos</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de empleados -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap cursor-pointer"
                            wire:click="sortBy('id_empleado')">
                            ID {!! $sortField === 'id_empleado' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap cursor-pointer"
                            wire:click="sortBy('nombre')">
                            Nombre {!! $sortField === 'nombre' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap cursor-pointer"
                            wire:click="sortBy('cargo')">
                            Cargo {!! $sortField === 'cargo' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">Ubicación</th>
                        <th class="px-4 py-3 whitespace-nowrap">Teléfono</th>
                        <th class="px-4 py-3 whitespace-nowrap">Correo</th>
                        <th class="px-4 py-3 whitespace-nowrap cursor-pointer" wire:click="sortBy('estado')">
                            Estado {!! $sortField === 'estado' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($empleados as $empleado)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $empleado->id_empleado }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $empleado->nombre }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $empleado->cargo }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $empleado->ubicacion }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $empleado->telefono }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-300 break-all">
                                    <a href="mailto:{{ $empleado->correo }}"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                        {{ $empleado->correo }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $empleado->estado == 'activo'
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ ucfirst($empleado->estado) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button wire:click="edit({{ $empleado->id_empleado }})"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 hover:underline">
                                        Editar
                                    </button>
                                    <button wire:click="toggleStatus({{ $empleado->id_empleado }})"
                                        wire:confirm="¿Estás seguro de cambiar el estado de este empleado?"
                                        wire:loading.attr="disabled"
                                        class="{{ $empleado->estado == 'activo' ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }} hover:underline">
                                        <span wire:loading.remove
                                            wire:target="toggleStatus({{ $empleado->id_empleado }})">
                                            {{ $empleado->estado == 'activo' ? 'Desactivar' : 'Activar' }}
                                        </span>
                                        <span wire:loading wire:target="toggleStatus({{ $empleado->id_empleado }})">
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No se encontraron empleados que coincidan con los criterios de búsqueda
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if ($empleados->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                {{ $empleados->links() }}
            </div>
        @endif
    </div>
</div>
