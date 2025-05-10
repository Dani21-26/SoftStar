<x-layouts.app.sidebar :title="'Productos'">
    <flux:main>
        <div class="bg-white rounded-lg shadow overflow-hidden dark:bg-gray-800">
            <!-- Encabezado con botón de agregar -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('productos.create') }}" class="text-black bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    <i class="fas fa-plus mr-2"></i>Agregar Producto
                </a>
            </div>
            
            <div class="p-4">
                <!-- Barra de búsqueda y controles -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                    <!-- Búsqueda -->
                    <div class="w-full md:w-auto">
                        <label for="table-search" class="sr-only">Buscar</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="text" id="table-search" class="block w-full ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar productos...">
                        </div>
                    </div>

                    <!-- Selector de registros por página -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Mostrar:</span>
                        <select id="per-page-select" class="text-sm border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="20">20</option>
                        </select>
                        <span class="text-sm text-gray-700 dark:text-gray-300">registros</span>
                    </div>
                </div>

                <!-- Tabla de productos -->
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table id="productos-table" class="w-full">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3">Id</th>
                                <th scope="col" class="px-6 py-3">Nombre</th>
                                <th scope="col" class="px-6 py-3">Descripción</th>
                                <th scope="col" class="px-6 py-3">Stock</th>
                                <th scope="col" class="px-6 py-3">S-Mínimo</th>
                                <th scope="col" class="px-6 py-3">Ubicación</th>
                                <th scope="col" class="px-6 py-3">Precio</th>
                                <th scope="col" class="px-6 py-3">Proveedor</th>
                                <th scope="col" class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productos as $producto)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4">{{ $producto->id }}</td>
                                <td class="px-6 py-4">{{ $producto->nombre }}</td>
                                <td class="px-6 py-4">{{ $producto->descripcion }}</td>
                                <td class="px-6 py-4">{{ $producto->cantidad }}</td>
                                <td class="px-6 py-4">{{ $producto->stock_minimo }}</td>
                                <td class="px-6 py-4">{{ $producto->ubicacion }}</td>
                                <td class="px-6 py-4">{{ $producto->precio }}</td>
                                <td class="px-6 py-4">{{ $producto->proveedor_id }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <a href="{{ route('productos.edit', $producto->id) }}" class="text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-yellow-600 dark:hover:bg-yellow-700 focus:outline-none dark:focus:ring-yellow-800">
                                        Editar
                                    </a>
                                    <form action="{{ route('productos.destroy', $producto->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </flux:main>
</x-layouts.app.sidebar>