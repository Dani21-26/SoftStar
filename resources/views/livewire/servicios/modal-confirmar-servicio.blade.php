<div>
    <!-- Modal para confirmar servicio -->
    <div x-show="$wire.modalConfirmar" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl max-h-screen overflow-y-auto">
            <div class="p-6">
                <!-- Encabezado -->
                <div class="flex justify-between items-center pb-4 border-b dark:border-gray-700">
                    <flux:heading size="lg">
                        Confirmar Servicio Técnico
                    </flux:heading>
                    <button @click="$wire.modalConfirmar = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
            
                </div>

                <!-- Detalles del servicio -->
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Cliente:</p>
                        <p class="font-medium">{{ $servicioSeleccionado->cliente ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Dirección:</p>
                        <p class="font-medium">{{ $servicioSeleccionado->direccion ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Falla Reportada:</p>
                        <p class="font-medium">{{ $servicioSeleccionado->falla_reportada ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Técnico Asignado:</p>
                        <p class="font-medium">{{ Auth::user()->empleado->nombre ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Formulario de confirmación -->
                <form wire:submit.prevent="confirmarServicio" class="mt-6 space-y-4">
                    <!-- Solución aplicada -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nota <span class="text-red-600">*</span>
                        </label>
                        <textarea wire:model="solucionAplicada" rows="3"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Describa la solución aplicada" required></textarea>
                        @error('solucionAplicada')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                    </div>
                    
                    <!-- Materiales utilizados -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Materiales Utilizados <span class="text-red-600">*</span>
                        </label>
                        <div class="space-y-3">
                            @foreach($todosProductos as $producto)
                                <div class="flex items-center justify-between p-3 border rounded-lg dark:border-gray-700">
                                    <div>
                                        <p class="font-medium">{{ $producto->nombre }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Stock: {{ $producto->stock }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <button type="button" 
                                            wire:click="decrementarProducto({{ $producto->id }})"
                                            class="px-2 py-1 bg-gray-200 rounded-lg dark:bg-gray-700 hover:bg-gray-300 transition"
                                            @disabled(($productosSeleccionados[$producto->id] ?? 0) <= 0)>
                                            -
                                        </button>
                                        <input type="number" 
                                            wire:model.live="productosSeleccionados.{{ $producto->id }}"
                                            min="0" 
                                            max="{{ $producto->stock }}"
                                            class="w-16 px-2 py-1 border rounded-lg text-center dark:bg-gray-800">
                                        <button type="button" 
                                            wire:click="incrementarProducto({{ $producto->id }})"
                                            class="px-2 py-1 bg-gray-200 rounded-lg dark:bg-gray-700 hover:bg-gray-300 transition"
                                            @disabled(($productosSeleccionados[$producto->id] ?? 0) >= $producto->stock)>
                                            +
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('productosSeleccionados')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-4 pt-4 border-t dark:border-gray-700">
                        <flux:button type="button" variant="outline" @click="$wire.modalConfirmar = false" class="flex-1">
                            Cancelar
                        </flux:button>
                        <flux:button type="submit" variant="primary" class="flex-1">
                            Confirmar Servicio
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>