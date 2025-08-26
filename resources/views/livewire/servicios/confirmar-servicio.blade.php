<div>
    <flux:modal name="confirmar-servicio" wire:model="showModal" class="md:w-1/2">
        <form wire:submit.prevent="confirmar" class="p-4 space-y-4">
            <!-- Encabezado -->
            <div class="flex justify-between items-center pb-4 border-b dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-800 dark:text-white">
                    Confirmar Servicio
                </h2>
            </div>

            <!-- Detalles del servicio -->
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Cliente:</p>
                    <p class="font-medium">{{ $servicioSeleccionado->cliente ?? '' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Falla Reportada:</p>
                    <p class="font-medium">{{ $servicioSeleccionado->falla_reportada ?? '' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Técnico:</p>
                    <p class="font-medium">{{ $servicioSeleccionado->tecnico->name ?? '' }}</p>

                </div>
            </div>

            
            <!-- Nota -->
            <div class="mt-6">
                <label class="block mb-1 font-semibold">
                    Nota del problema real <span class="text-red-600">*</span>
                </label>
                <textarea wire:model="nota" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    rows="3"></textarea>
                @error('nota')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Buscador de productos -->
            <div>
                <label class="block mb-2 font-semibold">Buscar producto</label>
                <input type="text" wire:model.live="search"
                    class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:border-gray-600"
                    placeholder="Escribe el nombre del producto...">

                <!-- Resultados -->
                @if (!empty($productos))
                    <div class="mt-2 border rounded-lg max-h-40 overflow-y-auto">
                        @foreach ($productos as $producto)
                            <div class="flex justify-between items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer"
                                wire:click="seleccionarProducto({{ $producto->id }})">
                                <span>{{ $producto->nombre }}</span>
                                <span class="text-sm text-gray-500">Stock: {{ $producto->stock }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Seleccionados -->
                @if (!empty($seleccionados))
                    <div class="mt-4 space-y-2">
                        @foreach ($seleccionados as $id => $item)
                            <div class="flex justify-between items-center border rounded-lg p-2">
                                <div>
                                    <p class="font-medium">{{ $item['nombre'] }}</p>
                                    <p class="text-sm text-gray-500">Stock: {{ $item['stock'] }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="number" min="1" max="{{ $item['stock'] }}"
                                        wire:change="actualizarCantidad({{ $id }}, $event.target.value)"
                                        value="{{ $item['cantidad'] }}" class="w-16 p-1 border rounded">
                                    <button type="button" wire:click="quitarProducto({{ $id }})"
                                        class="text-red-500 hover:text-red-700">
                                        ✕
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Botones -->
            <div class="flex gap-4 pt-4 border-t dark:border-gray-700">
                <button type="button" class="flex-1 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
                    x-on:click="$dispatch('close-modal', 'confirmar-servicio')">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded"
                    wire:loading.attr="disabled" wire:target="confirmar"
                    @if ($alertaActiva) disabled style="opacity:0.5;pointer-events:none;" @endif>
                    Confirmar Servicio
                </button>
            </div>
        </form>
    </flux:modal>
</div>
<script>
    document.addEventListener('livewire:update', () => {
        // Si el modal ya no está visible, remueve el foco
        const modal = document.querySelector('[wire\\:model=\"showModal\"]');
        if (!modal || modal.style.display === 'none' || !@js($showModal)) {
            document.activeElement.blur();
        }
    });
</script>
