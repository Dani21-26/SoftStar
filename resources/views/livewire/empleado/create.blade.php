<div>
    <flux:modal name="crear-empleado" class="md:w-1/2">
        <form wire:submit.prevent="save" class="space-y-6">
            <!-- Encabezado -->
            <div class="flex justify-between items-center">
                <flux:heading size="lg">Nuevo Empleado</flux:heading>
                <button type="button" @click="$dispatch('close-modal', 'crear-empleado')"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
                    ✕
                </button>
            </div>

            <!-- Nombre Completo -->
            <flux:input label="Nombre Completo" placeholder="Ej: Juan Pérez" wire:model="nombre" required />
            @error('nombre')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror

            <!-- Cargo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cargo</label>
                <select wire:model="cargo" class="w-full border rounded p-2" required>
                    <option value="">Seleccione un cargo</option>
                    @foreach($cargosDisponibles as $cargoOption)
                        <option value="{{ $cargoOption }}">{{ $cargoOption }}</option>
                    @endforeach
                </select>
                @error('cargo')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>

            <!-- Ubicación -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ubicación</label>
                <select wire:model="ubicacion" class="w-full border rounded p-2" required>
                    <option value="">Seleccione ubicación</option>
                    @foreach($ubicacionesDisponibles as $ubicacionOption)
                        <option value="{{ $ubicacionOption }}">{{ $ubicacionOption }}</option>
                    @endforeach
                </select>
                @error('ubicacion')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>

            <!-- Teléfono -->
            <flux:input label="Teléfono" placeholder="Ej: 555-1234567" wire:model="telefono" type="tel" required />
            @error('telefono')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror

            <!-- Correo Electrónico -->
            <flux:input label="Correo Electrónico" placeholder="empleado@empresa.com" wire:model="correo" type="email" required />
            @error('correo')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror

            <!-- Botones -->
            <div class="flex gap-4 pt-4">
                <flux:button type="button" variant="outline" @click="$dispatch('close-modal', 'crear-empleado')" class="flex-1">
                    Cancelar
                </flux:button>
                <flux:button type="submit" variant="primary" class="flex-1" wire:loading.attr="disabled">
                    <span wire:loading.remove>Guardar</span>
                    <span wire:loading wire:target="save">Guardando...</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>