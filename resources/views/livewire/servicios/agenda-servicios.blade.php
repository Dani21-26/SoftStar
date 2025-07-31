<div class="p-6 bg-gray shadow rounded-lg overflow-x-auto">
    <!-- Título y botón -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Agenda del Día</h2>
        <div class="w-full md:w-1/3 mb-4">
            <label for="fechaFiltrada" class="sr-only">Filtrar por fecha</label>
            <div class="relative">
                <input id="fechaFiltrada" type="date" wire:model.live.debounce.300ms="fechaFiltrada"
                    class="block w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
        <div class="w-full md:w-1/3 mb-4">
            <label for="tecnico" class="sr-only">Buscar técnico</label>
            <div class="relative">
                <input id="tecnico" type="text" wire:model.live.debounce.300ms="tecnico"
                    placeholder="Buscar por nombre del técnico..."
                    class="block w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
        
        
        
        <!-- Búsqueda por cliente -->
        <div class="w-full md:w-1/3 mb-4">
            <label for="search" class="sr-only">Buscar cliente</label>
            <div class="relative">
                <input id="search" type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por nombre del cliente..."
                    class="block w-full pl-4 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>



        <flux:modal.trigger name="crear-servicio">
            <flux:button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                + Agregar Servicio
            </flux:button>
        </flux:modal.trigger>
    </div>

    <!-- Modales -->
    <livewire:servicios.crear-servicio />


    <!-- Tabla de servicios -->
    <table class="min-w-full text-sm text-left border border-gray-200 rounded overflow-hidden">
        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Cliente</th>
                <th class="px-4 py-3">Router</th>
                <th class="px-4 py-3">Litebean</th>
                <th class="px-4 py-3">Falla Reportada</th>
                <th class="px-4 py-3">Prioridad</th>
                <th class="px-4 py-3">Técnico</th>
                <th class="px-4 py-3">Dirección</th>
                <th class="px-4 py-3">Estado</th>
                <th class="px-4 py-3">Fecha</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($servicios as $servicio)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $servicio->cliente }}</td>
                    <td class="px-4 py-2">{{ $servicio->router }}</td>
                    <td class="px-4 py-2">{{ $servicio->litebean }}</td>
                    <td class="px-4 py-2">{{ $servicio->falla_reportada }}</td>
                    <td class="px-4 py-2 capitalize">{{ $servicio->prioridad }}</td>
                    <td class="px-4 py-2">{{ $servicio->tecnico->nombre ?? 'Sin asignar' }}</td>
                    <td class="px-4 py-2">
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($servicio->direccion) }}"
                            target="_blank" class="text-blue-600 hover:underline break-words">
                            {{ $servicio->direccion }}
                        </a>
                    </td>
                    <td class="px-4 py-2 capitalize">{{ $servicio->estado }}</td>
                    <td class="px-4 py-2">{{ $servicio->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-2">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('servicios.editar', $servicio->id) }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                Editar
                            </a>

                            <button wire:click="eliminar({{ $servicio->id }})"
                                onclick="return confirm('¿Estás seguro de eliminar este servicio?')"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="px-4 py-4 text-center text-gray-500">
                        No hay servicios registrados hoy.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-6">
        {{ $servicios->links('pagination::tailwind') }}
    </div>
</div>
