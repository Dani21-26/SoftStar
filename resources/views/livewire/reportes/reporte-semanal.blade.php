<div>
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Reporte Semanal de Servicios</h1>
        
        <!-- Filtros -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex space-x-4">
                <input type="date" wire:model="fechaInicio" class="border rounded px-3 py-2">
                <input type="date" wire:model="fechaFin" class="border rounded px-3 py-2">
            </div>
            
            <button wire:click="exportarExcel" class="bg-green-600 text-white px-4 py-2 rounded">
                Exportar Excel
            </button>
        </div>

        <!-- Resumen general -->
        <div class="mb-8">
            <h2 class="text-xl font-bold mb-4">Resumen: {{ date('d/m/Y', strtotime($fechaInicio)) }} al {{ date('d/m/Y', strtotime($fechaFin)) }}</h2>
            
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-100 p-4 rounded-lg">
                    <h3 class="font-semibold">Total Servicios</h3>
                    <p class="text-2xl">{{ $reporte['total_servicios'] ?? 0 }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded-lg">
                    <h3 class="font-semibold">Completados</h3>
                    <p class="text-2xl">{{ $reporte['servicios_completados'] ?? 0 }}</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded-lg">
                    <h3 class="font-semibold">Pendientes</h3>
                    <p class="text-2xl">{{ $reporte['servicios_pendientes'] ?? 0 }}</p>
                </div>
            </div>
            
            <!-- Tabla de técnicos -->
            <h3 class="font-bold mb-2">Servicios por Técnico</h3>
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="py-2 px-4">Técnico</th>
                            <th class="py-2 px-4">Total Servicios</th>
                            <th class="py-2 px-4">Completados</th>
                            <th class="py-2 px-4">Productos Utilizados</th>
                            <th class="py-2 px-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reporte['tecnicos'] ?? [] as $tecnicoData)
                            <tr class="border-b">
                                <td class="py-2 px-4">{{ $tecnicoData['tecnico']->name }}</td>
                                <td class="py-2 px-4 text-center">{{ $tecnicoData['total'] }}</td>
                                <td class="py-2 px-4 text-center">{{ $tecnicoData['completados'] }}</td>
                                <td class="py-2 px-4">
                                    @foreach($tecnicoData['productos'] ?? [] as $producto)
                                        <span class="inline-block bg-gray-100 px-2 py-1 rounded text-sm mr-1 mb-1">
                                            {{ $producto['nombre'] }} ({{ $producto['cantidad'] }})
                                        </span>
                                    @endforeach
                                </td>
                                <td class="py-2 px-4">
                                    <a href="{{ route('reportes.historial', ['tecnico' => $tecnicoData['tecnico']->id]) }}" 
                                       class="text-blue-600 hover:underline">
                                        Ver Historial
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center">No hay datos de técnicos</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Productos utilizados -->
            <h3 class="font-bold mb-2">Productos Utilizados</h3>
            <div class="flex flex-wrap gap-2 mb-6">
                @foreach($reporte['productos_utilizados'] ?? [] as $producto)
                    <div class="bg-gray-100 px-3 py-2 rounded">
                        {{ $producto['producto']->nombre }}: {{ $producto['cantidad'] }} unidades
                    </div>
                @endforeach
            </div>
            
            <!-- Lista de servicios -->
            <h3 class="font-bold mb-2">Detalle de Servicios</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="py-2 px-4">Código</th>
                            <th class="py-2 px-4">Cliente</th>
                            <th class="py-2 px-4">Técnico</th>
                            <th class="py-2 px-4">Estado</th>
                            <th class="py-2 px-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reporte['servicios'] ?? [] as $servicio)
                            <tr class="border-b">
                                <td class="py-2 px-4">{{ $servicio->codigo }}</td>
                                <td class="py-2 px-4">{{ $servicio->cliente }}</td>
                                <td class="py-2 px-4">{{ $servicio->tecnico->name ?? 'No asignado' }}</td>
                                <td class="py-2 px-4">
                                    <span class="px-2 py-1 rounded text-xs 
                                        {{ $servicio->estado == 'completado' ? 'bg-green-100 text-green-800' : 
                                           ($servicio->estado == 'cancelado' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $servicio->estado)) }}
                                    </span>
                                </td>
                                <td class="py-2 px-4">
                                    <button wire:click="verDetalleServicio({{ $servicio->id_servicio }})" 
                                            class="text-blue-600 hover:underline">
                                        Ver Detalle
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de detalle de servicio -->
    @if($detalleServicio)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Detalle del Servicio {{ $detalleServicio->codigo }}</h3>
                        <button wire:click="resetDetalle" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- ... (contenido del modal igual al anterior) ... -->
                </div>
            </div>
        </div>
    @endif
</div>