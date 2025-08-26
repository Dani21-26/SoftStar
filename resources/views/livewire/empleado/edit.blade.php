<div>
    <flux:modal name="edit-empleado" class="md:w-1/2" wire:model="showModal">
        <form wire:submit.prevent="guardar" class="space-y-6">
            <!-- Encabezado -->
            <div class="flex justify-between items-center">
                <flux:heading size="lg">Editar Empleado</flux:heading>
                <button type="button" wire:click="cerrarModal"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
                    ✕
                </button>
            </div>

            <!-- Nombre -->
            <flux:input label="Nombre Completo" placeholder="Ej: Juan Pérez" wire:model="nombre" required />
            @error('nombre')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror

            <!-- Cargo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Cargo <span class="text-red-500">*</span>
                </label>
                <select wire:model="cargo" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600"
                    required>
                    <option value="">Seleccione un cargo</option>
                    @foreach ($cargosDisponibles as $cargoOption)
                        <option value="{{ $cargoOption }}" @selected($cargoOption == $cargo)>
                            {{ $cargoOption }}
                        </option>
                    @endforeach
                </select>
                @error('cargo')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Ubicación -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Ubicación <span class="text-red-500">*</span>
                </label>
                <select wire:model="ubicacion" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600"
                    required>
                    <option value="">Seleccione ubicación</option>
                    @foreach ($ubicacionesDisponibles as $ubicacionOption)
                        <option value="{{ $ubicacionOption }}" @selected($ubicacionOption == $ubicacion)>
                            {{ $ubicacionOption }}
                        </option>
                    @endforeach
                </select>
                @error('ubicacion')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Teléfono -->
            <flux:input label="Teléfono" placeholder="Ej: 555-1234567" wire:model="telefono" type="tel" required />
            @error('telefono')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror

            <!-- Correo -->
            <flux:input label="Correo Electrónico" placeholder="empleado@empresa.com" wire:model="correo" type="email"
                required />
            @error('correo')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror

            <!-- Estado -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Estado <span class="text-red-500">*</span>
                </label>
                <select wire:model="estado" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600"
                    required>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
                @error('estado')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>


            <!-- Botones -->
            <div class="flex gap-4 pt-4">
                <flux:button type="button" variant="outline" wire:click="cerrarModal" class="flex-1">
                    Cancelar
                </flux:button>
                <flux:button type="submit" variant="primary" class="flex-1" wire:loading.attr="disabled">
                    <span wire:loading.remove>Actualizar</span>
                    <span wire:loading wire:target="guardar">Guardando...</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
