<div>
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Historial de Técnicos</h1>
        
        <!-- Filtros -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex space-x-4 items-center">
                <select wire:model="tecnicoId" wire:change="seleccionarTecnico" class="border rounded px-3 py-2">
                    <option value="">Seleccione un técnico</option>
                    @foreach($tecnicos as $tecnico)
                        <option value="{{ $tecnico->id }}">{{ $tecnico->name }}</option>
                    @endforeach
                </select>
                
                <input type="date" wire:model="fechaInicio" class="border rounded px-3 py-2">
                <input type="date" wire:model="fechaFin" class="border rounded px-3 py-2">
            </div>
            
            @if($tecnicoId)
                <button wire:click="exportarExcel" class="bg-green-600 text-white px-4 py-2 rounded">
                    Exportar Excel
                </button>
            @endif
        </div>

        @if($historial && $historial['tecnico'])
            <!-- Resumen del técnico -->
            <div class="mb-8">
                <h2 class="text-xl font-bold mb-4">Historial de {{ $historial['tecnico']->name }}</h2>
                <p class="mb-4">Período: {{ date('d/m/Y', strtotime($fechaInicio)) }} al {{ date('d/m/Y', strtotime($fechaFin)) }}</p>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-blue-100 p-4 rounded-lg">
                        <h3 class="font-semibold">Total Servicios</h3>
                        <p class="text-2xl">{{ $historial['total_servicios'] ?? 0 }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg">
                        <h3 class="font-semibold">Completados</h3>
                        <p class="text-2xl">{{ $historial['servicios_completados'] ?? 0 }}</p>
                    </div>
                </div>
                
                <!-- Historial por semanas -->
                @foreach($historial['semanas'] as $semana)
                    <div class="mb-8 border-b pb-6">
                        <h3 class="font-bold text-lg mb-2">Semana {{ $semana['semana'] }} ({{ $semana['inicio'] }} al {{ $semana['fin'] }})</h3>
                        
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="bg-gray-100 p-3 rounded">
                                <h4 class="font-semibold">Servicios</h4>
                                <p>{{ $semana['total_servicios'] }} ({{ $semana['servicios_completados'] }} completados)</p>
                            </div>
                            <div class="bg-gray-100 p-3 rounded">
                                <h4 class="font-semibold">Productos Utilizados</h4>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach($semana['productos_utilizados'] ?? [] as $producto)
                                        <span class="text-sm bg-white px-2 py-1 rounded">
                                            {{ $producto['nombre'] }} ({{ $producto['cantidad'] }})
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="bg-gray-100 p-3 rounded">
                                <h4 class="font-semibold">Eficiencia</h4>
                                <p>{{ $semana['total_servicios'] > 0 ? round(($semana['servicios_completados'] / $semana['total_servicios']) * 100, 2) : 0 }}%</p>
                            </div>
                        </div>
                        
                        <!-- Detalle de servicios de la semana -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th class="py-2 px-4">Código</th>
                                        <th class="py-2 px-4">Cliente</th>
                                        <th class="py-2 px-4">Fecha</th>
                                        <th class="py-2 px-4">Estado</th>
                                        <th class="py-2 px-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($semana['servicios'] as $servicio)
                                        <tr class="border-b">
                                            <td class="py-2 px-4">{{ $servicio->codigo }}</td>
                                            <td class="py-2 px-4">{{ $servicio->cliente }}</td>
                                            <td class="py-2 px-4">{{ $servicio->created_at->format('d/m/Y') }}</td>
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
                @endforeach
            </div>
        @else
            <div class="bg-gray-100 p-8 text-center rounded-lg">
                <p class="text-gray-600">Seleccione un técnico para ver su historial</p>
            </div>
        @endif
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