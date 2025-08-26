<div>
    <flux:modal name="edit-product" class="md:w-1000" wire:model="showModal">
        <form wire:submit.prevent="guardar" class="space-y-6">
            <!-- Encabezado -->
            <div class="flex justify-between items-center ">
                <flux:heading size="lg">Editar Producto</flux:heading>
                <button type="button" wire:click="cerrarModal"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
                    
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

            <!-- Stock Modificado -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Stock <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-2">
                    <input type="number" step="0.01" min="0" wire:model="stock_cantidad"
                           class="flex-1 border rounded p-2 dark:bg-gray-700 dark:border-gray-600" 
                           placeholder="Cantidad" required>
                    <select wire:model="stock_unidad" 
                            class="border rounded p-2 dark:bg-gray-700 dark:border-gray-600">
                        <option value="unidades">Unidades</option>
                        <option value="metros">Metros</option>
                        <option value="rollos">Rollos</option>
                        <option value="juegos">Juegos</option>
                    </select>
                </div>
                @error('stock_cantidad')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
                @error('stock_unidad')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Categoría con detección automática -->
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

            <!-- Precio Modificado -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Precio <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-2">
                    <input type="number" step="0.01" min="0" wire:model="precio_cantidad"
                           class="flex-1 border rounded p-2 dark:bg-gray-700 dark:border-gray-600" 
                           placeholder="Ej: 1.5" required>
                    <select wire:model="precio_unidad" 
                            class="border rounded p-2 dark:bg-gray-700 dark:border-gray-600">
                        <option value="1">COP</option>
                        <option value="1000">Miles de COP</option>
                        <option value="1000000">Millones de COP</option>
                    </select>
                </div>
                @error('precio_cantidad')
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