<x-layouts.app :title="__('Dashboard')">
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Encabezado -->
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Panel de Control</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Resumen de actividades y estado actual</p>
            </div>

            <!-- Tarjetas resumen -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Servicios esta semana -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-2xl p-6 transition transform hover:scale-[1.02]">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 mr-4">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Servicios esta semana</p>
                            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ $serviciosSemana }}
                            </h3>
                        </div>
                    </div>
                </div>

                <!-- Productos utilizados -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-2xl p-6 transition transform hover:scale-[1.02]">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 mr-4">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Productos utilizados</p>
                            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                                {{ $productosUtilizados->count() }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">En la última semana</p>
                        </div>
                    </div>
                </div>

                <!-- Estado de stock -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-2xl p-6 transition transform hover:scale-[1.02]">
                    <div class="flex items-center">
                        <div
                            class="p-3 rounded-full 
                            @if ($stockEstado === 'bajo') bg-red-100 dark:bg-red-900 
                            @else bg-green-100 dark:bg-green-900 @endif mr-4">
                            <svg class="w-6 h-6 
                                @if ($stockEstado === 'bajo') text-red-600 dark:text-red-300 
                                @else text-green-600 dark:text-green-300 @endif"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado de stock</p>
                            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                                @if ($stockEstado === 'bajo')
                                    ⚠️ Bajo
                                @else
                                    ✅ Estable
                                @endif
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $mensajeStock }}
                            </p>
                        </div>
                    </div>

                    @if ($stockEstado === 'bajo')
                        <div class="mt-4 bg-red-50 dark:bg-red-900/30 rounded-lg p-3">
                            <h4 class="text-sm font-semibold text-red-600 dark:text-red-400 mb-2">Productos en riesgo
                            </h4>
                            <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300 space-y-1">
                                @foreach ($productosBajoStock as $p)
                                    <li>{{ $p->nombre }} ({{ $p->stock }} unidades)</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tabla productos -->
            <div
                class="bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Productos utilizados</h3>

                <!-- Contenedor con altura fija y scroll -->
                <div class="overflow-x-auto rounded-lg max-h-40 overflow-y-auto">
                    <table class="min-w-full border-collapse">
                        <thead class="sticky top-0 z-10 bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase">
                                    Producto
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase">
                                    Cantidad
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productosUtilizados as $producto)
                                <tr
                                    class="odd:bg-gray-50 even:bg-white dark:odd:bg-gray-800 dark:even:bg-gray-900 hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $producto['nombre'] }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-blue-600 dark:text-blue-400 font-semibold">
                                        {{ $producto['total'] }} {{ $producto['unidad'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                                        No se utilizaron productos esta semana
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>



        </div>
    </div>
</x-layouts.app>
