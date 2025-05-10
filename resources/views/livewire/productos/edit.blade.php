<div>
    <flux:modal name="edit-product" class="md:w-1000" wire:model="showModal">
        <form wire:submit.prevent="guardar" class="space-y-6">
            <!-- Encabezado -->
            <div class="flex justify-between items-center">
                <flux:heading size="lg">Editar Herramienta</flux:heading>
                <button type="button" wire:click="cerrarModal"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
                    ✕
                </button>
            </div>

            <!-- Campos del Formulario -->
            <!-- Nombre -->
            <div>
                <flux:input label="Nombre" placeholder="Ej: Taladro profesional" wire:model="nombre" required />
                @error('nombre')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Detalle -->
            <div>
                <flux:input label="Detalle" placeholder="Descripción del producto" wire:model="detalle" required />
                @error('detalle')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Stock -->
            <div>
                <flux:input label="Stock" placeholder="Cantidad disponible" type="number" min="0"
                    wire:model="stock" required />
                @error('stock')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Categoría -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Categoría <span class="text-red-500">*</span>
                </label>
                <select wire:model="id_categoria"
                    class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600" required>
                    <option value="">Seleccione una categoría</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
                @error('id_categoria')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Ubicación -->
            <div>
                <flux:input label="Ubicación" placeholder="Ej: Estantería A2" wire:model="ubicacion" required />
                @error('ubicacion')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Precio -->
            <div>
                <flux:input label="Precio" wire:model="precio" type="number" step="0.01" min="0" required />
                @error('precio')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Proveedor -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Proveedor <span class="text-red-500">*</span>
                </label>
                <select wire:model="id_proveedor"
                    class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600" required>
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
            <!-- Estado -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Estado <span class="text-red-500">*</span>
                </label>
                <select wire:model="estado" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600"
                    required>
                    <option value="activo" {{ $estado == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ $estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
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
