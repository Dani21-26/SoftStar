<div>
    <flux:modal name="edit-profile" class="md:w-1000">
        <form wire:submit.prevent="save" class="space-y-6">
            <div>
                <flux:heading size="lg">Nueva Herramienta</flux:heading>
            </div>

            <!-- Nombre -->
            <flux:input label="Nombre" placeholder="Ej: Taladro profesional" wire:model.defer="nombre" />

            <!-- Detalle -->
            <flux:input label="Detalle" placeholder="Descripción del producto" wire:model.defer="detalle" />

            <!-- Stock -->
            <flux:input label="Stock" placeholder="Cantidad disponible" type="number" wire:model.defer="stock" />

            <!-- Categoría (Select) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoría</label>
                <select wire:model="id_categoria" class="w-full border rounded p-2">
                    <option value="">Seleccione una categoría</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>

            </div>

            <!-- Ubicación -->
            <flux:input label="Ubicación" placeholder="Ej: Estantería A2" wire:model.defer="ubicacion" />

            <!-- Precio -->
            <div>
                <flux:input label="Precio" wire:model.defer="precio" type="number" step="0.01" />
                @error('precio')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Proveedor (Select) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Proveedor</label>
                <select wire:model="id_proveedor" class="w-full border rounded p-2">
                    <option value="">Seleccione un proveedor</option>
                    @foreach ($proveedores as $proveedor)
                        <option value="{{ $proveedor->id_proveedor }}">
                            {{ $proveedor->nombre_empresa }}
                        </option>
                    @endforeach
                </select>
                @error('id_proveedor')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Guardar</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
