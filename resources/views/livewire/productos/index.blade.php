<div>
    <div class="relative mb-5 w-full">
        <flux:heading size="xl" level="1">{{ __('Lista de Productos') }}</flux:heading>
        <flux:separator variant="subtle" />
        <flux:modal.trigger name="edit-profile">
            <flux:button>Añadir Producto</flux:button>
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

                    <!-- Filtro por categoría -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm dark:text-gray-300">Categoría:</span>
                        <select wire:model.live="categoria"
                            class="text-sm border rounded-lg dark:bg-gray-700 px-2 py-1">
                            <option value="">Todas</option>
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat->id_categoria }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-blue-500 dark:bg-blue-700">
                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider""
                            wire:click="sortBy('nombre')">
                            Nombre {!! $sortField === 'nombre' ? ($sortDirection === 'asc' ? '&uarr;' : '&darr;') : '' !!}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider"">Stock
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider"">
                            Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider"">
                            Ubicación</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider""
                            wire:click="sortBy('precio')">
                            Precio {!! $sortField === 'precio' ? ($sortDirection === 'asc' ? '&uarr;' : '&darr;') : '' !!}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider"">
                            Proveedor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider"">
                            Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider"">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-4 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                {{ Str::limit($producto->nombre, 25) }}
                                @if ($producto->detalle)
                                    <span class="block text-xs text-gray-500 dark:text-gray-400">
                                        {{ Str::limit($producto->detalle, 30) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="font-semibold">{{ $producto->cantidad_stock }}</span>
                                <span class="text-xs text-gray-500">{{ $producto->unidad_stock }}</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                {{ $producto->categoria->nombre ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">{{ $producto->ubicacion }}</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                ${{ number_format($producto->precio, 0, ',', '.') }}
                                @if ($producto->precio >= 1000000)
                                    <span
                                        class="block text-xs text-gray-500">({{ number_format($producto->precio / 1000000, 2) }}M)</span>
                                @elseif($producto->precio >= 1000)
                                    <span
                                        class="block text-xs text-gray-500">({{ number_format($producto->precio / 1000, 1) }}K)</span>
                                @endif
                            </td>
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
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400" title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>

                                    @if ($producto->estado == 'activo')
                                        <button wire:click="toggleStatus({{ $producto->id }})"
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
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron productos
                            </td>
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
</div>
