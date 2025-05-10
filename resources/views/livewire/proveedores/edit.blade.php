<div>
    <div x-data="{ open: @entangle('showModal') }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Contenido del modal SIN fondo oscuro -->
            <div x-show="open" 
                class="inline-block bg-white dark:bg-gray-600 rounded-lg text-left overflow-hidden shadow-lg transform transition-all w-full max-w-lg">
                
                <form wire:submit.prevent="guardar" class="space-y-4 p-6">
                    <!-- Encabezado -->
                    <div class="flex justify-between items-center pb-2 border-b dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Editar Proveedor</h2>
                        <button type="button" @click="open = false" wire:click="cerrarModal"
                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 text-xl">
                            &times;
                        </button>
                    </div>

                    <!-- Campos del formulario -->
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Nombre de la Empresa -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre de la Empresa</label>
                            <input type="text" wire:model="nombre_empresa" 
                                class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600">
                            @error('nombre_empresa') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Contacto -->
                        <div>
                            <flux:input label="Contacto" 
                                        placeholder="Persona de contacto" 
                                        wire:model="contacto_nombre" 
                                        required />
                            @error('contacto_nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <flux:input label="Teléfono" 
                                        placeholder="Número de contacto" 
                                        wire:model="telefono" 
                                        type="tel" 
                                        required />
                            @error('telefono') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Correo Electrónico -->
                        <div>
                            <flux:input label="Correo Electrónico" 
                                        placeholder="correo@proveedor.com" 
                                        wire:model="correo"
                                        type="email" 
                                        required />
                            @error('correo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Dirección -->
                        <div>
                            <flux:input label="Dirección" 
                                        placeholder="Dirección completa" 
                                        wire:model="direccion" 
                                        required />
                            @error('direccion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Estado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Estado <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="estado" 
                                    class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600"
                                    required>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                            @error('estado') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="open = false" wire:click="cerrarModal"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>