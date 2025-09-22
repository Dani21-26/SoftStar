<div class="p-6 bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
    <!-- Encabezado y filtros -->
    <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">📅 Agenda del Día</h2>

        <div class="w-full lg:w-3/4">
            <!-- Grid responsivo para filtros -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Fecha -->
                <div class="relative">
                    <input id="fechaFiltrada" type="date" wire:model.live.debounce.300ms="fechaFiltrada"
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" />
                </div>

                <!-- Técnico -->
                <div class="relative">
                    <input id="tecnico" type="text" wire:model.live.debounce.300ms="tecnico"
                        placeholder="Buscar técnico..."
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" />
                </div>

                <!-- Cliente -->
                <div class="sm:col-span-1 lg:col-span-2 relative">
                    <input id="search" type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Buscar cliente..."
                        class="block w-full pl-3 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" />
                </div>
            </div>
        </div>

        <!-- Botón agregar -->
        <div class="w-full lg:w-auto flex justify-start lg:justify-end">
            <flux:modal.trigger name="crear-servicio">
                <flux:button
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-md transition">
                    + Agregar Servicio
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    <!-- Modales -->
    <livewire:servicios.crear-servicio />
    <livewire:servicios.confirmar-servicio />

    <!-- Tabla -->
    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
        <table class="min-w-[900px] w-full text-sm text-left">
            <thead
                class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase text-xs font-semibold tracking-wide">
                <tr>
                    <th class="px-4 py-3">Cliente</th>
                    <th class="px-4 py-3">Router</th>
                    <th class="px-4 py-3">Litebean</th>
                    <th class="px-4 py-3">Reporte</th>
                    <th class="px-4 py-3">Prioridad</th>
                    <th class="px-4 py-3">Técnico</th>
                    <th class="px-4 py-3">Dirección</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($servicios as $servicio)
                    <tr
                        class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-900 hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                        <td class="px-4 py-3 align-top font-medium text-gray-900 dark:text-white">
                            {{ $servicio->cliente }}</td>
                        <td class="px-4 py-3 align-top text-gray-700 dark:text-gray-300">{{ $servicio->router }}</td>
                        <td class="px-4 py-3 align-top text-gray-700 dark:text-gray-300">{{ $servicio->litebean }}</td>
                        <td class="px-4 py-3 align-top text-gray-700 dark:text-gray-300">
                            {{ $servicio->falla_reportada }}</td>

                        <!-- Prioridad con badge -->
                        <td class="px-4 py-3 align-top capitalize">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-semibold 
                                @if ($servicio->prioridad === 'alta') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                @elseif($servicio->prioridad === 'media') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                @else bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 @endif">
                                {{ $servicio->prioridad }}
                            </span>
                        </td>

                        <!-- Técnico -->
                        <td class="px-4 py-3 align-top text-gray-700 dark:text-gray-300">
                            {{ $servicio->tecnico->name ?? 'Sin asignar' }}
                        </td>

                        <!-- Dirección -->
                        <td class="px-4 py-3 align-top max-w-xs whitespace-normal break-words">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($servicio->direccion) }}"
                                target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">
                                {{ $servicio->direccion }}
                            </a>
                        </td>

                        <!-- Estado con badge -->
                        <td class="px-4 py-3 align-top capitalize">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-semibold 
                                @if ($servicio->estado === 'pendiente') bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                                @elseif($servicio->estado === 'en proceso') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                @else bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 @endif">
                                {{ $servicio->estado }}
                            </span>
                        </td>

                        <td class="px-4 py-3 align-top text-gray-700 dark:text-gray-300">
                            {{ $servicio->created_at->format('d/m/Y') }}
                        </td>

                        <!-- Acciones -->
                        <td class="px-4 py-3 align-top">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('servicios.editar', $servicio->id) }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-xs font-medium transition shadow-sm"
                                    @if ($servicio->estado !== 'pendiente') disabled style="opacity:0.5;pointer-events:none;" @endif>
                                    Editar
                                </a>
                                <button
                                    onclick="if (confirm('¿Estás seguro de eliminar este servicio?')) { @this.call('eliminar', {{ $servicio->id }}) }"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs font-medium transition shadow-sm"
                                    @if ($servicio->estado !== 'pendiente') disabled style="opacity:0.5;pointer-events:none;" @endif>
                                    Eliminar
                                </button>


                                @if ($servicio->estado === 'pendiente')
                                    <flux:modal.trigger name="confirmar-servicio"
                                        wire:click="abrirModal({{ $servicio->id }})" wire:loading.attr="disabled">
                                        <flux:button
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-xs font-medium shadow-sm">
                                            Confirmar
                                        </flux:button>
                                    </flux:modal.trigger>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                            No hay servicios registrados hoy.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-6">
        {{ $servicios->links('pagination::tailwind') }}
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('open-modal', data => {
            window.dispatchEvent(new CustomEvent('open-modal', {
                detail: data
            }));
        });

        Livewire.on('close-modal', data => {
            window.dispatchEvent(new CustomEvent('close-modal', {
                detail: data
            }));
        });
    });
</script>
