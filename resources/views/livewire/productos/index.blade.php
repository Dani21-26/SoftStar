<div>
    <div class="relative mb-5 w-full">
        <flux:heading size="xl" level="1">{{ __('Lista de Herramientas') }}</flux:heading>
        <flux:separator variant="subtle" />
        <flux:modal.trigger name="edit-profile">
            <flux:button>Añadir </flux:button>
        </flux:modal.trigger>
        <livewire:productos.create />
        <livewire:productos.edit />
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <!-- Barra de búsqueda y filtros -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <!-- Búsqueda -->
                <div class="w-full md:w-1/3">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar productos..."
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                </div>

                <!-- Filtros -->
                <div class="flex items-center gap-4">
                    <!-- Items por página -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm dark:text-gray-300">Mostrar:</span>
                        <select wire:model.live="perPage" class="text-sm border rounded-lg dark:bg-gray-700 px-2 py-1">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                        </select>
                    </div>

                    <!-- Filtro por estado -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm dark:text-gray-300">Estado:</span>
                        <select wire:model.live="estado" class="text-sm border rounded-lg dark:bg-gray-700 px-2 py-1">
                            <option value="">Todos</option>
                            <option value="activo">Activos</option>
                            <option value="inactivo">Inactivos</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 whitespace-nowrap">ID</th>
                        <th class="px-4 py-3 whitespace-nowrap">Nombre</th>
                        <th class="px-4 py-3 whitespace-nowrap">Stock</th>
                        <th class="px-4 py-3 whitespace-nowrap">Categoría</th>
                        <th class="px-4 py-3 whitespace-nowrap">Ubicación</th>
                        <th class="px-4 py-3 whitespace-nowrap">Precio</th>
                        <th class="px-4 py-3 whitespace-nowrap">Proveedor</th>
                        <th class="px-4 py-3 whitespace-nowrap">Estado</th>
                        <th class="px-4 py-3 whitespace-nowrap">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-4">{{ $producto->id }}</td>
                            <td class="px-4 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                {{ Str::limit($producto->nombre, 20) }}
                            </td>
                            <td class="px-4 py-4">{{ $producto->stock }}</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                {{ $producto->categoria->nombre ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">{{ $producto->ubicacion }}</td>
                            <td class="px-4 py-4 whitespace-nowrap">${{ number_format($producto->precio, 2) }}</td>
                            <td class="px-4 py-4">
                                <div class="whitespace-nowrap">
                                    {{ $producto->proveedor->nombre_empresa ?? 'N/A' }}
                                    @if ($producto->proveedor && $producto->proveedor->contacto_nombre)
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">
                                            Contacto: {{ $producto->proveedor->contacto_nombre }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $producto->estado == 'activo' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ ucfirst($producto->estado) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex gap-2">
                                    <button wire:click="$dispatch('abrir-modal-edicion', { id: {{ $producto->id }} })"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                        Editar
                                    </button>
                                    <button wire:click="toggleStatus({{ $producto->id }})"
                                        class="text-{{ $producto->estado == 'activo' ? 'yellow' : 'green' }}-600 hover:text-{{ $producto->estado == 'activo' ? 'yellow' : 'green' }}-900 dark:text-{{ $producto->estado == 'activo' ? 'yellow' : 'green' }}-400">
                                        {{ $producto->estado == 'activo' ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center">No se encontraron productos</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $productos->links() }}
        </div>
    </div>
    @push('scripts')
        <script>
            // Confirmación de eliminación con SweetAlert
            window.addEventListener('swal:confirm', event => {
                Swal.fire({
                    title: event.detail.title,
                    text: event.detail.text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit('delete', event.detail.id);
                    }
                });
            });
        </script>
    @endpush
