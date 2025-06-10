<div>
    <flux:modal name="edit-profile" class="md:w-1000">
        <form wire:submit.prevent="save" class="space-y-6 bg-white p-6 rounded-lg">
            <div>
                <flux:heading size="lg" class="text-gray-800">Nueva Herramienta</flux:heading>
            </div>

            <!-- Nombre -->
            <div>
                <flux:input label="Nombre" placeholder="Ej: Taladro profesional" wire:model.defer="nombre"
                    class="bg-white border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
            </div>

            <!-- Detalle -->
            <div>
                <flux:input label="Detalle" placeholder="Descripción del producto" wire:model.defer="detalle"
                    class="bg-white border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
            </div>

            <!-- Stock - Campo modificado -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                <div class="flex gap-2">
                    <input type="number" step="0.01" wire:model.defer="stock_cantidad"
                        class="flex-1 border border-gray-300 rounded-md p-2 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Cantidad">
                    <select wire:model.defer="stock_unidad"
                        class="border border-gray-300 rounded-md p-2 focus:border-blue-500 focus:ring-blue-500">
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

            <!-- Categoría (Select) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                <select wire:model="id_categoria" wire:change="actualizarUnidadMedida"
                    class="w-full border border-gray-300 rounded-md p-2 focus:border-blue-500 focus:ring-blue-500 bg-white">
                    <option value="">Seleccione una categoría</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Ubicación -->
            <div>
                <flux:input label="Ubicación" placeholder="Ej: Estantería A2" wire:model.defer="ubicacion"
                    class="bg-white border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
            </div>

            <!-- Precio -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
                <div class="flex gap-2">
                    <input type="number" step="0.01" wire:model.defer="precio"
                        class="flex-1 border border-gray-300 rounded-md p-2 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Ej: 1.5">
                    <select wire:model.defer="precio_unidad"
                        class="border border-gray-300 rounded-md p-2 focus:border-blue-500 focus:ring-blue-500">
                        <option value="1">COP</option>
                        <option value="1000">Miles de COP</option>
                        <option value="1000000">Millones de COP</option>
                    </select>
                </div>
                @error('precio')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Proveedor (Select) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                <select wire:model="id_proveedor"
                    class="w-full border border-gray-300 rounded-md p-2 focus:border-blue-500 focus:ring-blue-500 bg-white">
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

            <div class="flex pt-4">
                <flux:spacer />
                <flux:button type="submit" variant="primary" class="bg-blue-600 hover:bg-blue-700 text-white">
                    Guardar
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
