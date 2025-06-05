<div>
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Historial de Técnicos</h1>
        
        <!-- Filtros -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                <select wire:model="tecnicoId" class="border rounded px-3 py-2 w-full md:w-64">
                    <option value="">Seleccione un técnico</option>
                    @foreach($tecnicos as $tecnico)
                        <option value="{{ $tecnico->id }}">{{ $tecnico->name }}</option>
                    @endforeach
                </select>
                
                <div class="flex gap-4">
                    <input type="date" wire:model="fechaInicio" class="border rounded px-3 py-2">
                    <input type="date" wire:model="fechaFin" class="border rounded px-3 py-2">
                </div>
            </div>
        </div>

        @if($tecnicoId)
            <!-- Resumen del técnico -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-semibold">Total Servicios</h3>
                    <p class="text-2xl">{{ $servicios->count() }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="font-semibold">Completados</h3>
                    <p class="text-2xl">{{ $servicios->where('estado', 'completado')->count() }}</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <h3 class="font-semibold">Eficiencia</h3>
                    <p class="text-2xl">
                        @php
                            $total = $servicios->count();
                            $completados = $servicios->where('estado', 'completado')->count();
                            echo $total > 0 ? round(($completados/$total)*100, 2).'%' : '0%';
                        @endphp
                    </p>
                </div>
            </div>

            <!-- Listado de servicios -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Código</th>
                            <th class="px-4 py-2 text-left">Cliente</th>
                            <th class="px-4 py-2 text-left">Fecha</th>
                            <th class="px-4 py-2 text-left">Estado</th>
                            <th class="px-4 py-2 text-left">Productos Usados</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($servicios as $servicio)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $servicio->codigo }}</td>
                                <td class="px-4 py-3">{{ $servicio->cliente }}</td>
                                <td class="px-4 py-3">{{ $servicio->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs 
                                        {{ $servicio->estado == 'completado' ? 'bg-green-100 text-green-800' : 
                                           ($servicio->estado == 'cancelado' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $servicio->estado)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($servicio->detalle && $servicio->detalle->productos_utilizados)
                                        @foreach($servicio->detalle->productos_utilizados as $productoId => $cantidad)
                                            @php
                                                $producto = \App\Models\Producto::find($productoId);
                                            @endphp
                                            @if($producto)
                                                <span class="inline-block bg-gray-100 px-2 py-1 rounded text-xs mr-1 mb-1">
                                                    {{ $producto->nombre }} ({{ $cantidad }})
                                                </span>
                                            @endif
                                        @endforeach
                                    @else
                                        <span class="text-gray-500 text-sm">Sin productos</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    No se encontraron servicios en el período seleccionado
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-gray-100 p-8 text-center rounded-lg">
                <p class="text-gray-600">Por favor seleccione un técnico para ver su historial</p>
            </div>
        @endif
    </div>
</div>