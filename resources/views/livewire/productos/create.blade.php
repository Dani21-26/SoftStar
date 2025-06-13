<div>
    <flux:modal name="edit-profile" class="md:w-1000">
        <form wire:submit.prevent="save" class="space-y-6 bg-white dark:bg-gray-900 p-6 rounded-lg">
            <div>
                <flux:heading size="lg" class="text-gray-800 dark:text-gray-100">Nueva Herramienta</flux:heading>
            </div>

            <!-- Nombre -->
            <div>
                <flux:input label="Nombre" placeholder="Ej: Taladro profesional" wire:model.defer="nombre"
                    class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500" />
            </div>

            <!-- Detalle -->
            <div>
                <flux:input label="Detalle" placeholder="Descripción del producto" wire:model.defer="detalle"
                    class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500" />
            </div>

            <!-- Stock -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Stock</label>
                <div class="flex gap-2">
                    <input type="number" step="0.01" wire:model.defer="stock_cantidad"
                        class="flex-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Cantidad">
                    <select wire:model.defer="stock_unidad"
                        class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 rounded-md p-2 focus:border-blue-500 focus:ring-blue-500">
                        <option value="unidades">Unidades</option>
                        <option value="metros">Metros</option>
                        <option value="rollos">Rollos</option>
                        <option value="juegos">Juegos</option>
                    </select>
                </div>
                @error('stock')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Categoría -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Categoría</label>
                <select wire:model="id_categoria"
                    class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 rounded-md p-2 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Seleccione una categoría</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>

            </div>

            <!-- Ubicación -->
            <div>
                <flux:input label="Ubicación" placeholder="Ej: Estantería A2" wire:model.defer="ubicacion"
                    class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500" />
            </div>

            <!-- Precio -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Precio</label>
                <div class="flex gap-2">
                    <input type="number" step="0.01" wire:model.defer="precio"
                        class="flex-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md p-2 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Ej: 1.5">
                    <select wire:model.defer="precio_unidad"
                        class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 rounded-md p-2 focus:border-blue-500 focus:ring-blue-500">
                        <option value="1">COP</option>
                        <option value="1000">Miles de COP</option>
                        <option value="1000000">Millones de COP</option>
                    </select>
                </div>
                @error('precio')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Proveedor -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Proveedor</label>
                <select wire:model="id_proveedor"
                    class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 rounded-md p-2 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Seleccione un proveedor</option>
                    @foreach ($proveedores as $proveedor)
                        <option value="{{ $proveedor->id_proveedor }}">{{ $proveedor->nombre_empresa }}</option>
                    @endforeach
                </select>
                @error('id_proveedor')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botón -->
            <div class="flex pt-4">
                <flux:spacer />
                <flux:button type="submit" variant="primary" class="bg-blue-600 hover:bg-blue-700 text-white">
                    Guardar
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
