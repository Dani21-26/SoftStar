<div>
    <flux:modal name="crear-servicio"  wire:model="showModal" class="md:w-1/2">
        <form wire:submit.prevent="guardar" class="space-y-6">
            <!-- Encabezado -->
            <div class="flex justify-between items-center">
                <flux:heading size="lg">Nuevo Servicio</flux:heading>
                <button type="button" @click="$dispatch('close-modal', 'crear-servicio')"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400">

                </button>
            </div>

            <!-- Cliente -->
            <flux:input label="Cliente" wire:model="cliente" required />
            @error('cliente')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror

            <!-- Router -->
            <flux:input label="Router" wire:model="router" required />
            @error('router')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror

            <!-- Litebean -->
            <flux:input label="Litebean" wire:model="litebean" required />
            @error('litebean')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror

            <!-- Dirección -->
            <flux:input label="Dirección" wire:model="direccion" required />
            @error('direccion')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror

            <!-- Falla Reportada -->
            <flux:input label="Falla Reportada" wire:model="falla_reportada" required />
            @error('falla_reportada')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror

            <!-- Prioridad -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prioridad</label>
                <select wire:model="prioridad" class="w-full border rounded p-2" required>
                    <option value="baja">Baja</option>
                    <option value="media">Media</option>
                    <option value="alta">Alta</option>
                </select>
                @error('prioridad')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Técnico -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-700 mb-1">Técnico</label>
                <select wire:model="tecnico_id" class="w-full border rounded p-2">
                    <option value="">Selecciona un técnico</option>
                    @foreach ($tecnicos as $tecnico)
                        <option value="{{ $tecnico->id }}">{{ $tecnico->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Botones -->
            <div class="flex gap-4 pt-4">
                <flux:button type="button" variant="outline" @click="$dispatch('close-modal', 'crear-servicio')"
                    class="flex-1">
                    Cancelar
                </flux:button>
                <flux:button type="submit" variant="primary" class="flex-1" wire:loading.attr="disabled">
                    <span wire:loading.remove>Guardar</span>
                    <span wire:loading wire:target="guardar">Guardando...</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
