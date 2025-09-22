<div>
    <flux:modal name="confirmar-servicio" wire:model="showModal" class="md:w-1/2">
        <form wire:submit.prevent="confirmar" class="p-6 space-y-6 bg-white dark:bg-gray-800 rounded-lg">
            <!-- Encabezado -->
            <div class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                    Confirmar Servicio
                </h2>
            </div>

            <!-- Detalles del servicio -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Cliente:</p>
                    <p class="font-medium text-gray-900 dark:text-gray-100">
                        {{ $servicioSeleccionado->cliente ?? '' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Falla Reportada:</p>
                    <p class="font-medium text-gray-900 dark:text-gray-100">
                        {{ $servicioSeleccionado->falla_reportada ?? '' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Técnico:</p>
                    <p class="font-medium text-gray-900 dark:text-gray-100">
                        {{ $servicioSeleccionado->tecnico->name ?? 'No asignado' }}
                    </p>
                </div>
            </div>

            <!-- Nota -->
            <div>
                <label class="block mb-1 font-semibold text-gray-800 dark:text-gray-200">
                    Nota del problema real <span class="text-red-600">*</span>
                </label>
                <textarea 
                    wire:model="nota"
                    rows="3"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-2"
                ></textarea>
                @error('nota')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Buscador de productos -->
            <div>
                <label class="block mb-2 font-semibold text-gray-800 dark:text-gray-200">Buscar producto</label>
                <input 
                    type="text" 
                    wire:model.live="search"
                    placeholder="Escribe el nombre del producto..."
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                />

                <!-- Resultados -->
                @if (!empty($productos))
                    <div class="mt-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 shadow max-h-48 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($productos as $producto)
                            <div 
                                class="flex justify-between items-center p-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700"
                                wire:click="seleccionarProducto({{ $producto->id }})"
                            >
                                <span class="text-gray-800 dark:text-gray-100">{{ $producto->nombre }}</span>
                                <span class="text-xs text-gray-500">Stock: {{ $producto->stock }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Seleccionados -->
                @if (!empty($seleccionados))
                    <div class="mt-4 space-y-3">
                        @foreach ($seleccionados as $id => $item)
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $item['nombre'] }}</p>
                                    <p class="text-xs text-gray-500">Stock: {{ $item['stock'] }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input 
                                        type="number" 
                                        min="1" 
                                        max="{{ $item['stock'] }}"
                                        value="{{ $item['cantidad'] }}"
                                        wire:change="actualizarCantidad({{ $id }}, $event.target.value)"
                                        class="w-16 text-sm px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100"
                                    />
                                    <button 
                                        type="button" 
                                        wire:click="quitarProducto({{ $id }})"
                                        class="text-red-500 hover:text-red-700 text-lg"
                                    >
                                        ✕
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Botones -->
            <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                
                <button 
                    type="submit" 
                    wire:loading.attr="disabled" 
                    wire:target="confirmar"
                    @if ($alertaActiva) disabled style="opacity:0.5;pointer-events:none;" @endif
                    class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium"
                >
                    Confirmar Servicio
                </button>
            </div>
        </form>
    </flux:modal>
</div>

<script>
    document.addEventListener('livewire:update', () => {
        const modal = document.querySelector('[wire\\:model="showModal"]');
        if (!modal || modal.style.display === 'none' || !@js($showModal)) {
            document.activeElement.blur();
        }
    });
</script>
